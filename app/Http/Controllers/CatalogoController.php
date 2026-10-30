<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogoController extends Controller
{
    private function disponibles()
    {
        return Vehiculo::with(['modelo.marca', 'tipoVehiculo', 'inventarios.predio'])
            ->where('estado', 'DISPONIBLE')
            ->whereHas('inventarios', function ($q) {
                $q->whereNull('fecha_salida')->whereHas('predio');
            })
            ->where(function ($q) {
                $q->where('condicion', 'NUEVO')
                  ->orWhere(function ($usados) {
                      $usados->where('condicion', 'USADO')
                          ->whereRaw(<<<'SQL'
EXISTS (
    SELECT 1
    FROM INSPECCION I
    WHERE I.ID_VEHICULO = VEHICULO.ID_VEHICULO
      AND I.RESULTADO = 'APROBADA'
      AND NOT EXISTS (
          SELECT 1
          FROM INSPECCION I2
          WHERE I2.ID_VEHICULO = I.ID_VEHICULO
            AND (
                I2.FECHA_INSPECCION > I.FECHA_INSPECCION
                OR (
                    I2.FECHA_INSPECCION = I.FECHA_INSPECCION
                    AND I2.ID_INSPECCION > I.ID_INSPECCION
                )
            )
      )
      AND EXISTS (
          SELECT 1
          FROM AVALUO A
          WHERE A.ID_VEHICULO = I.ID_VEHICULO
            AND A.ID_INSPECCION = I.ID_INSPECCION
            AND A.ESTADO = 'APROBADO'
      )
)
SQL);
                  });
            });
    }

    public function index(Request $request)
    {
        // Oracle determina el orden FIFO a partir del ingreso vigente al predio.
        // Se consulta por separado para evitar referencias correlacionadas
        // incompatibles con el alias generado por Eloquent/Oracle.
        $idsFIFO = DB::connection('oracle')
    ->table('INVENTARIO_PREDIO')
    ->select('ID_VEHICULO')
    ->selectRaw('MIN(FECHA_INGRESO) AS FECHA_FIFO')
    ->whereNull('FECHA_SALIDA')
    ->groupBy('ID_VEHICULO')
    ->orderBy('FECHA_FIFO', 'asc')
    ->orderBy('ID_VEHICULO', 'asc')
    ->get()
    ->map(function ($fila) {
        $datos = array_change_key_case(
            (array) $fila,
            CASE_LOWER
        );

        return (int) $datos['id_vehiculo'];
    });

        $posicionesFIFO = $idsFIFO->flip()->all();

        $vehiculos = $this->disponibles()->get()
            ->sortBy(fn ($v) => $posicionesFIFO[(string) $v->id_vehiculo] ?? PHP_INT_MAX)
            ->values();
        $vehiculos = $vehiculos->filter(function ($v) use ($request) {
            $marca = (string) ($v->modelo?->marca?->nombre ?? '');
            $modelo = (string) ($v->modelo?->nombre ?? '');
            $busqueda = trim((string) $request->query('buscar', ''));
            if ($busqueda !== '' && stripos($marca . ' ' . $modelo . ' ' . $v->anio, $busqueda) === false) return false;
            if ($request->filled('condicion') && $v->condicion !== $request->query('condicion')) return false;
            return true;
        })->values();
        return view('catalogo.index', compact('vehiculos'));
    }

    public function show(Request $request, int $id)
    {
        $vehiculo = $this->disponibles()->where('id_vehiculo', $id)->firstOrFail();
        $inventarioActual = $vehiculo->inventarios->whereNull('fecha_salida')->sortByDesc('id_inventario')->first();
        $clientePublico = null;
        if ((int) $request->session()->get('reserva_publica.vehiculo_id') === $id
            && $request->session()->has('reserva_publica.cliente_id')) {
            $clientePublico = DB::connection('oracle')->table('CLIENTE')
                ->where('ID_CLIENTE', (int) $request->session()->get('reserva_publica.cliente_id'))
                ->where('ACTIVO', 1)
                ->first(['ID_CLIENTE', 'NOMBRE', 'APELLIDO']);
        }
        return view('catalogo.show', compact('vehiculo', 'inventarioActual', 'clientePublico'));
    }
}
