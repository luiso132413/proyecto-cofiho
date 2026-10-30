<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        $oracle = DB::connection('oracle');

        $ventasConfirmadas = $oracle->table('VENTA')->where('ESTADO', 'CONFIRMADA');
        $totalVentas = (clone $ventasConfirmadas)->count();
        $montoVentas = (float) (clone $ventasConfirmadas)->sum('TOTAL');

        // Mantener la misma fuente de datos del reporte FIFO.
        $inventarioFIFO = $oracle->table('V_INVENTARIO_FIFO')->get();
        $vehiculosDisponibles = $inventarioFIFO->count();
        $valorInventario = (float) $inventarioFIFO->sum('precio_lista');

        // Rangos reales de antigüedad, sin valores provisionales.
        $inventarioAntiguedad = $oracle->table('V_REPORTE_ANTIGUEDAD')->get();
        $rangos = [
            'normal' => $inventarioAntiguedad->filter(fn ($v) => (int) $v->dias_inventario <= 60)->count(),
            'alerta' => $inventarioAntiguedad->filter(fn ($v) => (int) $v->dias_inventario > 60 && (int) $v->dias_inventario <= 90)->count(),
            'critica' => $inventarioAntiguedad->filter(fn ($v) => (int) $v->dias_inventario > 90)->count(),
        ];

        $conciliaciones = $oracle->table('V_CONCILIACION_FINANCIERA')->get();
        $conciliadas = $conciliaciones->filter(
            fn ($registro) => strtoupper((string) $registro->estado_conciliacion) === 'CONCILIADA'
        )->count();

        // Compatible con Oracle 11g: se agrupa por mes con TRUNC.
        $ventasMensuales = $oracle->table('VENTA')
            ->selectRaw("TO_CHAR(TRUNC(FECHA_VENTA, 'MM'), 'YYYY-MM') AS MES, SUM(TOTAL) AS MONTO")
            ->where('ESTADO', 'CONFIRMADA')
            ->whereRaw("FECHA_VENTA >= ADD_MONTHS(TRUNC(SYSDATE, 'MM'), -5)")
            ->groupByRaw("TRUNC(FECHA_VENTA, 'MM')")
            ->orderByRaw("TRUNC(FECHA_VENTA, 'MM')")
            ->get()
            ->keyBy('mes');

        // La vista usa $meses->keys() como etiquetas y values() como montos.
        $meses = collect();
        for ($retroceso = 5; $retroceso >= 0; $retroceso--) {
            $fecha = now()->startOfMonth()->subMonths($retroceso);
            $clave = $fecha->format('Y-m');
            $meses->put(
                $fecha->locale('es')->translatedFormat('M Y'),
                (float) ($ventasMensuales->get($clave)->monto ?? 0)
            );
        }

        $indicadores = [
            'ventas' => $totalVentas,
            'ingresos' => $montoVentas,
            'disponibles' => $vehiculosDisponibles,
            'criticos' => $rangos['critica'],
            'valor_inventario' => $valorInventario,
            'conciliadas' => $conciliadas,
            'pendientes' => $conciliaciones->count() - $conciliadas,
            'total_conciliaciones' => $conciliaciones->count(),
        ];

        return view('reportes.index', compact('indicadores', 'rangos', 'meses'));
    }


    public function inventario(\Illuminate\Http\Request $request)
    {
        $oracle = DB::connection('oracle');
        $vista = $request->query('vista', 'fifo');
        if (!in_array($vista, ['fifo', 'antiguedad'], true)) {
            $vista = 'fifo';
        }

        $predios = $oracle->table('PREDIO')->select('ID_PREDIO', 'NOMBRE')->get()->keyBy('id_predio');
        $nombresPredios = $predios->map(fn ($p) => $p->nombre);

        // Datos descriptivos complementarios; no modifica las vistas Oracle.
        $vehiculosBase = $oracle->table('VEHICULO')->get()->keyBy('id_vehiculo');
        $modelos = $oracle->table('MODELO')->get()->keyBy('id_modelo');
        $marcas = $oracle->table('MARCA')->get()->keyBy('id_marca');
        $descripciones = $vehiculosBase->map(function ($vehiculo) use ($modelos, $marcas) {
            $modelo = $modelos->get($vehiculo->id_modelo ?? null);
            $marca = $marcas->get($modelo->id_marca ?? null);
            return [
                'marca' => $marca->nombre ?? '',
                'modelo' => $modelo->nombre ?? '',
                'anio' => $vehiculo->anio ?? $vehiculo->anio_fabricacion ?? null,
            ];
        });

        $detallesVehiculos = $descripciones;

        if ($vista === 'fifo') {
    $vehiculos = $oracle->table('V_INVENTARIO_FIFO')
        ->orderBy('FECHA_INGRESO', 'asc')
        ->orderBy('ID_VEHICULO', 'asc')
        ->get();

    return view('reportes.inventario', compact(
        'vista',
        'vehiculos',
        'predios',
        'detallesVehiculos'
    ));
}

        $inventario = $oracle->table('V_REPORTE_ANTIGUEDAD')->get();
        $resumenPredios = $inventario->groupBy('id_predio')->map(function ($items, $idPredio) use ($nombresPredios) {
            return [
                'predio' => $nombresPredios->get($idPredio, 'Predio ' . $idPredio),
                'dias_0_30' => $items->where('rango_antiguedad', '0-30 DIAS')->count(),
                'dias_31_60' => $items->where('rango_antiguedad', '31-60 DIAS')->count(),
                'dias_61_90' => $items->where('rango_antiguedad', '61-90 DIAS')->count(),
                'dias_mas_90' => $items->where('rango_antiguedad', 'MAS DE 90 DIAS')->count(),
                'total' => $items->count(),
                'valor' => $items->sum('precio_lista'),
            ];
        })->values();

        return view('reportes.inventario', compact('vista', 'inventario', 'resumenPredios', 'predios', 'detallesVehiculos'));
    }

    public function ventas(\Illuminate\Http\Request $request)
    {
        $datos = $request->validate([
            'desde' => ['nullable', 'date_format:Y-m-d'],
            'hasta' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:desde'],
            'estado' => ['nullable', 'in:TODOS,CONFIRMADA'],
        ]);

        $desde = $datos['desde'] ?? '';
        $hasta = $datos['hasta'] ?? '';
        $estado = $datos['estado'] ?? 'CONFIRMADA';
        $oracle = DB::connection('oracle');

        $consulta = $oracle->table('VENTA as v')
            ->leftJoin('CLIENTE as c', 'c.ID_CLIENTE', '=', 'v.ID_CLIENTE')
            ->leftJoin('USUARIO as u', 'u.ID_USUARIO', '=', 'v.ID_USUARIO')
            ->leftJoin('PERSONA as p', 'p.ID_PERSONA', '=', 'u.ID_PERSONA')
            ->select('v.ID_VENTA', 'v.ID_CLIENTE', 'v.ID_USUARIO', 'v.FECHA_VENTA', 'v.ESTADO', 'v.TOTAL',
                'c.NOMBRE as CLIENTE_NOMBRE', 'c.APELLIDO as CLIENTE_APELLIDO',
                'u.NOMBRE as USUARIO_NOMBRE', 'p.NOMBRES as PERSONA_NOMBRES', 'p.APELLIDOS as PERSONA_APELLIDOS');

        if ($desde !== '') {
            $consulta->whereRaw("v.FECHA_VENTA >= TO_DATE(?, 'YYYY-MM-DD')", [$desde]);
        }
        if ($hasta !== '') {
            $consulta->whereRaw("v.FECHA_VENTA < TO_DATE(?, 'YYYY-MM-DD') + 1", [$hasta]);
        }
        if ($estado !== 'TODOS') {
            $consulta->where('v.ESTADO', $estado);
        }

        $ventas = $consulta->orderBy('v.ID_VENTA', 'desc')->get();
        $idsVentas = $ventas->pluck('id_venta')->all();
        $detallesPorVenta = collect();
        if (count($idsVentas) > 0) {
            // Fragmentos de 900 IDs: compatibles con el límite IN de Oracle 11g.
            foreach (array_chunk($idsVentas, 900) as $grupoIds) {
                $detalles = $oracle->table('DETALLE_VENTA as d')
                    ->leftJoin('VEHICULO as ve', 've.ID_VEHICULO', '=', 'd.ID_VEHICULO')
                    ->leftJoin('MODELO as mo', 'mo.ID_MODELO', '=', 've.ID_MODELO')
                    ->leftJoin('MARCA as ma', 'ma.ID_MARCA', '=', 'mo.ID_MARCA')
                    ->whereIn('d.ID_VENTA', $grupoIds)
                    ->select('d.ID_VENTA', 'd.ID_VEHICULO', 've.VIN', 've.PLACA', 've.ANIO',
                        'mo.NOMBRE as MODELO_NOMBRE', 'ma.NOMBRE as MARCA_NOMBRE')
                    ->get();
                foreach ($detalles as $detalle) {
                    $id = $detalle->id_venta;
                    if (!$detallesPorVenta->has($id)) {
                        $detallesPorVenta->put($id, collect());
                    }
                    $detallesPorVenta->get($id)->push($detalle);
                }
            }
        }

        $porVendedor = $ventas->groupBy('id_usuario')->map(function ($grupo, $id) use ($detallesPorVenta) {
            $primera = $grupo->first();
            $nombre = trim(($primera->persona_nombres ?? '') . ' ' . ($primera->persona_apellidos ?? ''));
            if ($nombre === '') {
                $nombre = $primera->usuario_nombre ?: 'Usuario #' . $id;
            }
            return [
                'nombre' => $nombre,
                'ventas' => $grupo->count(),
                'vehiculos' => $grupo->sum(fn ($venta) => $detallesPorVenta->get($venta->id_venta, collect())->count()),
                'monto' => (float) $grupo->sum('total'),
            ];
        })->sortByDesc('monto');

        $meses = $ventas->groupBy(function ($venta) {
            return \Carbon\Carbon::parse($venta->fecha_venta)->format('Y-m');
        })->map(fn ($grupo) => round((float) $grupo->sum('total'), 2))->sortKeys();

        $totalVehiculos = $detallesPorVenta->sum(fn ($items) => $items->count());

        return view('reportes.ventas', compact('ventas', 'porVendedor', 'meses',
            'desde', 'hasta', 'estado', 'detallesPorVenta', 'totalVehiculos'));
    }

    public function clientes(\Illuminate\Http\Request $request)
    {
        $datos = $request->validate([
            'desde' => ['nullable', 'date_format:Y-m-d'],
            'hasta' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:desde'],
            'buscar' => ['nullable', 'string', 'max:100'],
        ]);

        $desde = $datos['desde'] ?? '';
        $hasta = $datos['hasta'] ?? '';
        $buscar = trim($datos['buscar'] ?? '');
        $oracle = DB::connection('oracle');

        $clientes = $oracle->table('CLIENTE')
            ->select('ID_CLIENTE', 'NOMBRE', 'APELLIDO', 'EMAIL', 'TELEFONO', 'ACTIVO')
            ->orderBy('NOMBRE')->orderBy('APELLIDO')->get();

        $consulta = $oracle->table('VENTA')
            ->select('ID_VENTA', 'ID_CLIENTE', 'FECHA_VENTA', 'TOTAL')
            ->where('ESTADO', 'CONFIRMADA');
        if ($desde !== '') {
            $consulta->whereRaw("FECHA_VENTA >= TO_DATE(?, 'YYYY-MM-DD')", [$desde]);
        }
        if ($hasta !== '') {
            $consulta->whereRaw("FECHA_VENTA < TO_DATE(?, 'YYYY-MM-DD') + 1", [$hasta]);
        }
        $ventas = $consulta->orderBy('FECHA_VENTA', 'desc')->get();

        $cantidadVehiculosPorVenta = collect();
        foreach (array_chunk($ventas->pluck('id_venta')->all(), 900) as $ids) {
            $detalles = $oracle->table('DETALLE_VENTA')
                ->select('ID_VENTA', 'ID_VEHICULO')
                ->whereIn('ID_VENTA', $ids)->get();
            foreach ($detalles as $detalle) {
                $id = $detalle->id_venta;
                $cantidadVehiculosPorVenta->put($id, $cantidadVehiculosPorVenta->get($id, 0) + 1);
            }
        }

        $ventasPorCliente = $ventas->groupBy('id_cliente');
        $registros = $clientes->map(function ($cliente) use ($ventasPorCliente, $cantidadVehiculosPorVenta) {
            $compras = $ventasPorCliente->get($cliente->id_cliente, collect());
            return (object) [
                'id_cliente' => $cliente->id_cliente,
                'nombre' => trim($cliente->nombre . ' ' . $cliente->apellido),
                'email' => $cliente->email,
                'telefono' => $cliente->telefono,
                'activo' => $cliente->activo,
                'ventas' => $compras->count(),
                'vehiculos' => $compras->sum(fn ($venta) => $cantidadVehiculosPorVenta->get($venta->id_venta, 0)),
                'monto' => (float) $compras->sum('total'),
                'ultima_compra' => $compras->first()->fecha_venta ?? null,
            ];
        });

        if ($buscar !== '') {
            $registros = $registros->filter(function ($cliente) use ($buscar) {
                return mb_stripos($cliente->nombre, $buscar) !== false
                    || mb_stripos((string) $cliente->email, $buscar) !== false
                    || (string) $cliente->id_cliente === $buscar;
            });
        }

        $registros = $registros->sortByDesc('monto')->values();
        $compradores = $registros->where('ventas', '>', 0)->count();
        $ventasConfirmadas = $registros->sum('ventas');
        $vehiculosVendidos = $registros->sum('vehiculos');
        $ingresos = (float) $registros->sum('monto');
        $recurrentes = $registros->where('ventas', '>', 1)->count();

        return view('reportes.clientes', compact('registros', 'compradores',
            'ventasConfirmadas', 'vehiculosVendidos', 'ingresos', 'recurrentes',
            'desde', 'hasta', 'buscar'));
    }

   public function inventarioFIFO()
{
    $vehiculos = DB::connection('oracle')
        ->table('V_INVENTARIO_FIFO')
        ->orderBy('FECHA_INGRESO', 'asc')
        ->orderBy('ID_VEHICULO', 'asc')
        ->get();

    return view('reportes.inventario_fifo', compact('vehiculos'));
}

    public function conciliacionFinanciera()
    {
        $transacciones = DB::connection('oracle')
            ->table('V_CONCILIACION_FINANCIERA')
            ->get();

        return view(
            'reportes.conciliacion_financiera',
            compact('transacciones')
        );
    }

    
public function antiguedadInventario()
{
    $inventario = DB::connection('oracle')
        ->table('V_REPORTE_ANTIGUEDAD')
        ->get();

    $predios = DB::connection('oracle')
        ->table('PREDIO')
        ->select('ID_PREDIO', 'NOMBRE')
        ->get()
        ->keyBy('id_predio');

    $resumenPredios = $inventario
        ->groupBy('id_predio')
        ->map(function ($vehiculos, $idPredio) use ($predios) {
            return [
                'predio' => $predios->get($idPredio)->nombre
                    ?? 'Predio ' . $idPredio,
                'dias_0_30' => $vehiculos->where('rango_antiguedad', '0-30 DIAS')->count(),
                'dias_31_60' => $vehiculos->where('rango_antiguedad', '31-60 DIAS')->count(),
                'dias_61_90' => $vehiculos->where('rango_antiguedad', '61-90 DIAS')->count(),
                'dias_mas_90' => $vehiculos->where('rango_antiguedad', 'MAS DE 90 DIAS')->count(),
                'total' => $vehiculos->count(),
                'valor' => $vehiculos->sum('precio_lista'),
            ];
        })
        ->values();

    return view(
        'reportes.antiguedad_inventario',
        compact('inventario', 'resumenPredios')
    );
}

}
