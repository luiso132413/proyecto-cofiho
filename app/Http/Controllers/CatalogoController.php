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
            });
    }

    public function index(Request $request)
    {
        $vehiculos = $this->disponibles()->orderBy('id_vehiculo', 'desc')->get();
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
