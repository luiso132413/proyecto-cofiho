<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reserva confirmada | COFIHO V2</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f1f5fb;color:#14233e;font-family:system-ui,-apple-system,Segoe UI,sans-serif}
        header{background:linear-gradient(110deg,#112443,#1c447f);color:white;padding:24px;text-align:center;font-weight:800;letter-spacing:.07em}
        main{max-width:720px;margin:48px auto;padding:0 18px 35px}.panel{background:white;border:1px solid #dce5f2;border-radius:20px;padding:36px;box-shadow:0 12px 38px rgba(20,40,70,.07)}
        .check{width:66px;height:66px;border-radius:50%;background:#dcfce7;color:#15803d;display:grid;place-items:center;font-size:36px;margin:0 auto 15px}
        h1{text-align:center;color:#15366c;margin:0 0 10px;font-size:27px}.intro{text-align:center;color:#64748b;line-height:1.6;margin:0 0 28px}
        .section{font-weight:800;color:#173968;font-size:15px;margin:24px 0 10px}.details{border:1px solid #e3eaf4;border-radius:12px;overflow:hidden}
        .item{display:flex;justify-content:space-between;gap:20px;padding:13px 16px;border-bottom:1px solid #e8eef6}.item:last-child{border-bottom:0}
        .item span{color:#64748b}.item strong{text-align:right;overflow-wrap:anywhere}.status{color:#b45309}.notice{margin-top:22px;padding:15px;border-radius:10px;background:#eff6ff;color:#264a78;font-size:14px;line-height:1.6}
        .btn{display:block;margin-top:24px;background:#2563eb;color:#fff;text-decoration:none;text-align:center;padding:15px 18px;border-radius:10px;font-weight:800}.btn:hover{background:#1d4ed8}
        footer{text-align:center;color:#7b8ba3;font-size:12px;margin-top:22px}@media(max-width:520px){.panel{padding:23px 17px}.item{flex-direction:column;gap:3px}.item strong{text-align:left}h1{font-size:23px}}
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ secure_asset('css/halloween.css') }}">
</head>
<body>
<header>COFIHO V2 · CATÁLOGO PÚBLICO</header>
<main>
    <div class="panel">
        <div class="check">✓</div>
        <h1>¡Reserva registrada exitosamente!</h1>
        <p class="intro">Tu solicitud se registró en nuestro sistema. No se realizó ningún pago.</p>

        <div class="section">Información de la reserva</div>
        <div class="details">
            <div class="item"><span>Número de reserva</span><strong>#{{ $reserva->id_reserva }}</strong></div>
            <div class="item"><span>Estado actual</span><strong class="status">{{ $reserva->estado }}</strong></div>
            <div class="item"><span>Canal</span><strong>{{ $reserva->canal }}</strong></div>
            <div class="item"><span>Fecha de reserva</span><strong>{{ $reserva->fecha_reserva }}</strong></div>
            <div class="item"><span>Fecha de vencimiento</span><strong>{{ $reserva->fecha_expiracion }}</strong></div>
            <div class="item"><span>Enganche registrado</span><strong>Q {{ number_format((float) ($reserva->monto_enganche ?? 0), 2) }}</strong></div>
        </div>

        <div class="section">Vehículo y cliente</div>
        <div class="details">
            <div class="item"><span>Cliente</span><strong>{{ trim(($cliente->nombre ?? '') . ' ' . ($cliente->apellido ?? '')) ?: 'No disponible' }}</strong></div>
            <div class="item"><span>Vehículo</span><strong>#{{ $reserva->id_vehiculo }}{{ $vehiculo ? ' · ' . $vehiculo->vin : '' }}</strong></div>
            @if($vehiculo)
                <div class="item"><span>Placa</span><strong>{{ $vehiculo->placa ?? 'No asignada' }}</strong></div>
                <div class="item"><span>Condición</span><strong>{{ $vehiculo->condicion }}</strong></div>
            @endif
        </div>

        <div class="notice">La reserva tiene una vigencia definida por Oracle (normalmente 15 minutos). Si permanece pendiente al vencerse, el proceso automático podrá liberar el vehículo. Conserva el número de reserva para cualquier consulta.</div>
        <a class="btn" href="{{ route('catalogo.index') }}">← Volver al catálogo</a>
    </div>
    <footer>COFIHO V2 · Demostración académica</footer>
    
</main>
<script src="{{ secure_asset('js/halloween.js') }}" defer></script>
</body>
</html>
