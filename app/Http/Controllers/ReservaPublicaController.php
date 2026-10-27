<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;
use App\Services\ReservaService;

class ReservaPublicaController extends Controller
{
    // Este paso registra al cliente; NO crea todavía una reserva.
    public function registrarCliente(Request $request, int $id)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'dpi' => ['required', 'digits:13', Rule::unique('oracle.CLIENTE', 'DPI')],
            'nit' => ['nullable', 'string', 'max:20', Rule::unique('oracle.CLIENTE', 'NIT')],
            'email' => ['required', 'email', 'max:150'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'sitio_web' => ['nullable', 'max:0'], // campo trampa para bots
        ]);

        $db = DB::connection('oracle');
        $vehiculo = $db->table('VEHICULO')
            ->where('ID_VEHICULO', $id)
            ->where('ESTADO', 'DISPONIBLE')
            ->exists();

        if (!$vehiculo) {
            return back()->withErrors(['reserva' => 'El vehículo ya no está disponible.']);
        }

        try {
            $idCliente = $db->transaction(function () use ($db, $datos) {
                $db->table('CLIENTE')->insert([
                    'NOMBRE' => trim($datos['nombre']),
                    'APELLIDO' => trim($datos['apellido']),
                    'TIPO_CLIENTE' => 'INDIVIDUAL',
                    'DPI' => $datos['dpi'],
                    'NIT' => $datos['nit'] ?? null,
                    'EMAIL' => $datos['email'],
                    'TELEFONO' => $datos['telefono'],
                    'DIRECCION' => $datos['direccion'] ?? null,
                    'FECHA_REGISTRO' => DB::raw('SYSDATE'),
                    'ACTIVO' => 1,
                ]);
                $registro = $db->selectOne('SELECT SQ_CLIENTE.CURRVAL AS ID FROM DUAL');
                return (int) ($registro->id ?? $registro->ID);
            });
        } catch (Throwable $e) {
            report($e);
            return back()->withInput($request->except('dpi', 'nit'))
                ->withErrors(['registro' => 'No fue posible completar el registro. Verifica los datos.']);
        }

        $request->session()->regenerate();
        $request->session()->put('reserva_publica.cliente_id', $idCliente);
        $request->session()->put('reserva_publica.vehiculo_id', $id);
        $request->session()->put('reserva_publica.verificado', true); // Solo simulación académica; no es verificación real

        return redirect()->route('catalogo.show', $id)
            ->with('registro_publico_ok', 'Datos registrados. Puedes confirmar la reserva de demostración.');
    }

    // Acceso de demostración: NO equivale a autenticación real.
    public function identificarCliente(Request $request, int $id)
    {
        $datos = $request->validate([
            'dpi' => ['required', 'digits:13'],
            'email' => ['required', 'email', 'max:150'],
        ]);
        $db = DB::connection('oracle');
        $cliente = $db->table('CLIENTE')
            ->where('DPI', $datos['dpi'])
            ->whereRaw('LOWER(EMAIL) = ?', [mb_strtolower(trim($datos['email']))])
            ->where('ACTIVO', 1)
            ->first(['ID_CLIENTE']);
        if (!$cliente) {
            return back()->withErrors(['identificacion' => 'No coinciden los datos de un cliente activo.'])->withInput($request->only('email'));
        }
        $disponible = $db->table('VEHICULO')->where('ID_VEHICULO', $id)->where('ESTADO', 'DISPONIBLE')->exists();
        if (!$disponible) return redirect()->route('catalogo.index')->with('error', 'El vehículo ya no está disponible.');
        $request->session()->regenerate();
        $request->session()->put('reserva_publica.cliente_id', (int) $cliente->id_cliente);
        $request->session()->put('reserva_publica.vehiculo_id', $id);
        $request->session()->put('reserva_publica.verificado', true); // Simulación, no usar en producción
        return redirect()->route('catalogo.show', $id)->with('registro_publico_ok', 'Cliente encontrado. Confirma tu reserva de demostración.');
    }

    public function confirmar(Request $request, int $id, ReservaService $reservaService)
    {
        $clienteId = (int) $request->session()->get('reserva_publica.cliente_id', 0);
        if (!$clienteId || (int) $request->session()->get('reserva_publica.vehiculo_id') !== $id
            || $request->session()->get('reserva_publica.verificado') !== true) {
            return redirect()->route('catalogo.show', $id)->withErrors(['reserva' => 'Primero identifícate para continuar.']);
        }
        $db = DB::connection('oracle');
        if (!$db->table('CLIENTE')->where('ID_CLIENTE', $clienteId)->where('ACTIVO', 1)->exists()) {
            $request->session()->forget('reserva_publica');
            return back()->withErrors(['reserva' => 'El cliente no está activo.']);
        }
        try {
            // El procedimiento PL/SQL debe validar disponibilidad y bloquear concurrencia.
            $idReserva = $reservaService->crearReserva($clienteId, $id, null, 'WEB');
            $reserva = $db->table('RESERVA')->where('ID_RESERVA', $idReserva)->first();
            if (!$reserva || strtoupper(trim($reserva->estado)) !== 'PENDIENTE'
                || !$reserva->fecha_expiracion) {
                // No confirmar un resultado que no respeta las reglas del negocio.
                report(new \RuntimeException('Revisar estado/vencimiento de reserva pública ' . $idReserva));
            }
            $request->session()->forget('reserva_publica');
            $request->session()->put('reserva_publica_ultima', [
                'id' => $idReserva, 'cliente_id' => $clienteId,
            ]);
            return redirect()->route('catalogo.reserva.confirmada', ['id' => $idReserva]);
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors(['reserva' => 'No fue posible crear la reserva. Verifica la disponibilidad o revisa el log de Laravel.']);
        }
    }

    public function confirmada(Request $request, int $id)
    {
        $ultima = $request->session()->get('reserva_publica_ultima');
        if (!is_array($ultima) || (int) ($ultima['id'] ?? 0) !== $id) {
            return redirect()->route('catalogo.index')
                ->with('error', 'La confirmación solo está disponible en la sesión que realizó la reserva.');
        }

        $db = DB::connection('oracle');
        $reserva = $db->table('RESERVA')
            ->where('ID_RESERVA', $id)
            ->where('ID_CLIENTE', (int) $ultima['cliente_id'])
            ->first();
        if (!$reserva) {
            return redirect()->route('catalogo.index')
                ->with('error', 'No se encontró la reserva solicitada.');
        }

        $vehiculo = $db->table('VEHICULO')
            ->where('ID_VEHICULO', (int) $reserva->id_vehiculo)
            ->first();
        $cliente = $db->table('CLIENTE')
            ->where('ID_CLIENTE', (int) $reserva->id_cliente)
            ->first();

        return view('catalogo.reserva-confirmada', compact('reserva', 'vehiculo', 'cliente'));
    }
}
