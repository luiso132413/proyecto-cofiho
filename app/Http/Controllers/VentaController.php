<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Reserva;
use App\Services\VentaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class VentaController extends Controller
{
    /**
     * RF-04: solo permite comercializar usados con su inspeccion mas reciente
     * APROBADA y un avaluo APROBADO vinculado a esa misma inspeccion.
     * Oracle sigue validando definitivamente al procesar la venta.
     */
    private function filtrarAptosRF04($query)
    {
        return $query->where(function ($q) {
            $q->where('VEHICULO.CONDICION', 'NUEVO')
              ->orWhere(function ($usados) {
                  $usados->where('VEHICULO.CONDICION', 'USADO')
                      ->whereExists(function ($sub) {
                          $sub->selectRaw('1')
                              ->from('INSPECCION as I')
                              ->whereColumn('I.ID_VEHICULO', 'VEHICULO.ID_VEHICULO')
                              ->where('I.RESULTADO', 'APROBADA')
                              ->whereNotExists(function ($posterior) {
                                  $posterior->selectRaw('1')
                                      ->from('INSPECCION as I2')
                                      ->whereColumn('I2.ID_VEHICULO', 'I.ID_VEHICULO')
                                      ->whereRaw('(I2.FECHA_INSPECCION > I.FECHA_INSPECCION OR (I2.FECHA_INSPECCION = I.FECHA_INSPECCION AND I2.ID_INSPECCION > I.ID_INSPECCION))');
                              })
                              ->whereExists(function ($avaluo) {
                                  $avaluo->selectRaw('1')
                                      ->from('AVALUO as A')
                                      ->whereColumn('A.ID_VEHICULO', 'I.ID_VEHICULO')
                                      ->whereColumn('A.ID_INSPECCION', 'I.ID_INSPECCION')
                                      ->where('A.ESTADO', 'APROBADO');
                              });
                      });
              });
        });
    }

    public function index()
    {
        $ventas = Venta::with('detalles')->orderBy('id_venta', 'desc')->get();
        $clientes = Cliente::where('activo', 1)->orderBy('nombre')->orderBy('apellido')->get();
        $clientesHistoricos = Cliente::all()->keyBy('id_cliente');

        $vehiculos = $this->filtrarAptosRF04(Vehiculo::with('modelo'))
            ->where('estado', 'DISPONIBLE')
            ->orderBy('id_vehiculo')
            ->get();

        $reservas = Reserva::with('vehiculo.modelo')
            ->whereIn('estado', ['PENDIENTE', 'CONFIRMADA'])
            ->where('fecha_expiracion', '>', DB::raw('SYSDATE'))
            ->whereHas('vehiculo', fn ($q) => $this->filtrarAptosRF04($q)->where('estado', 'RESERVADO'))
            ->orderBy('id_reserva', 'desc')
            ->get();

        $metodosPago = DB::connection('oracle')->table('METODO_PAGO')
            ->where('ACTIVO', 1)
            ->orderBy('ID_METODO_PAGO')
            ->get();

        // Evitar depender de relaciones no declaradas en el modelo Venta.
        $usuarios = DB::connection('oracle')->table('USUARIO')->get()->keyBy('id_usuario');
        $personas = DB::connection('oracle')->table('PERSONA')->get()->keyBy('id_persona');
        $vehiculosHistoricos = Vehiculo::with('modelo')->get()->keyBy('id_vehiculo');

        return view('ventas.index', compact(
            'ventas', 'clientes', 'clientesHistoricos', 'vehiculos',
            'reservas', 'metodosPago', 'usuarios', 'personas', 'vehiculosHistoricos'
        ));
    }

    public function show($id)
    {
        $venta = Venta::with(['detalles', 'reserva', 'transacciones', 'detallesFinancieros'])->findOrFail($id);
        $clienteVenta = Cliente::find($venta->id_cliente);
        $usuarioVenta = DB::connection('oracle')->table('USUARIO')->where('ID_USUARIO', $venta->id_usuario)->first();
        $personaVenta = $usuarioVenta && isset($usuarioVenta->id_persona)
            ? DB::connection('oracle')->table('PERSONA')->where('ID_PERSONA', $usuarioVenta->id_persona)->first()
            : null;
        $vehiculosVenta = Vehiculo::with('modelo')
            ->whereIn('id_vehiculo', $venta->detalles->pluck('id_vehiculo')->all())
            ->get()->keyBy('id_vehiculo');

        return view('ventas.show', compact('venta', 'clienteVenta', 'usuarioVenta', 'personaVenta', 'vehiculosVenta'));
    }

    public function store(Request $request, VentaService $ventaService)
    {
        $datos = $request->validate([
            'id_cliente' => ['required', 'integer', Rule::exists('oracle.CLIENTE', 'ID_CLIENTE')->where('ACTIVO', 1)],
            'id_vehiculo' => ['required', 'integer', 'min:1'],
            'id_metodo_pago' => ['required', 'integer', 'min:1'],
            'id_reserva' => ['nullable', 'integer', 'min:1'],
        ]);

        try {
            $idVenta = $ventaService->procesarVenta(
                (int) $datos['id_cliente'],
                (int) $datos['id_vehiculo'],
                (int) Auth::user()->id_usuario,
                (int) $datos['id_metodo_pago'],
                isset($datos['id_reserva']) ? (int) $datos['id_reserva'] : null
            );

            return redirect()->route('ventas.index')
                ->with('success', "Venta {$idVenta} procesada correctamente.");
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->withErrors([
                'venta' => 'No fue posible procesar la venta. Verifica los datos y el estado del vehículo.',
            ]);
        }
    }
}
