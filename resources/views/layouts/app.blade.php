<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COFIHO V2')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f3f5f9; color: #1e293b; }
        .sidebar { width: 250px; height: 100vh; background: #172337; color: white; position: fixed; top: 0; left: 0; overflow-y: auto; display: flex; flex-direction: column; }
        .brand { padding: 28px 22px; font-size: 24px; font-weight: 700; border-bottom: 1px solid #334155; }
        .brand span { color: #38bdf8; }
        .menu { padding: 20px 12px; flex: 1; }
        .menu a { display: block; padding: 13px 16px; margin-bottom: 5px; color: #cbd5e1; text-decoration: none; border-radius: 8px; font-size: 14px; transition: 0.2s; }
        .menu a:hover, .menu a.active { background: #2563eb; color: white; }
        .sidebar-footer { padding: 16px 12px; border-top: 1px solid #334155; }
        .user-name { color: #fff; font-size: 14px; font-weight: 600; overflow-wrap: anywhere; }
        .user-role { color: #94a3b8; font-size: 12px; margin-top: 4px; }
        .logout-button { width: 100%; padding: 11px 14px; margin-top: 14px; border: 1px solid #475569; border-radius: 8px; background: transparent; color: #fff; cursor: pointer; text-align: left; font: inherit; font-size: 14px; }
        .logout-button:hover { background: #334155; }
        .main { margin-left: 250px; min-height: 100vh; }
        .topbar { background: white; padding: 22px 30px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .topbar h1 { font-size: 21px; font-weight: 600; }
        .topbar .system { color: #64748b; font-size: 13px; }
        .content { padding: 30px; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 3px 12px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card h2 { font-size: 19px; margin-bottom: 18px; }
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: #f1f5f9; color: #334155; text-align: left; padding: 13px; }
        td { padding: 13px; border-bottom: 1px solid #e2e8f0; }
        tr:hover td { background: #f8fafc; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 10px 16px; border: none; border-radius: 7px; text-decoration: none; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        
/* Formularios COFIHO */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 7px;
    margin-bottom: 18px;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}

.form-group input,
.form-group select,
.form-group textarea {
    display: block;
    width: 100%;
    max-width: 520px;
    padding: 11px 13px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background: #ffffff;
    color: #1e293b;
    font-family: inherit;
    font-size: 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.form-group input::placeholder {
    color: #94a3b8;
}

.card h3 {
    font-size: 16px;
    color: #172337;
    margin: 24px 0 16px;
}

.card p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 18px;
}

.card hr {
    border: 0;
    border-top: 1px solid #e2e8f0;
    margin: 24px 0;
}

.btn:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

        @media (max-width: 768px) {
            .sidebar { position: relative; width: 100%; height: auto; }
            .menu { display: flex; flex-wrap: wrap; gap: 5px; }
            .main { margin-left: 0; }
            .topbar, .content { padding: 18px; }
        }

/* ===== Componentes compartidos (mejoras visuales) ===== */
.badge { display:inline-block; padding:5px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
.badge-ok { background:#dcfce7; color:#166534; }
.badge-warn { background:#fef3c7; color:#92400e; }
.badge-bad { background:#fee2e2; color:#991b1b; }
.badge-info { background:#dbeafe; color:#1d4ed8; }
.badge-neutral { background:#e2e8f0; color:#334155; }

.btn-sm { font-size:12px; padding:8px 11px; white-space:nowrap; }
.btn-secundario { background:#e2e8f0; color:#334155; }
.btn-secundario:hover { background:#cbd5e1; }
.btn-peligro { background:#dc2626; }
.btn-peligro:hover { background:#b91c1c; }
.acciones { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
.acciones form { margin:0; }

.filtros { display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; margin-bottom:16px; padding:14px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; }
.filtros .filtro { display:flex; flex-direction:column; gap:5px; }
.filtros label { font-size:12px; font-weight:600; color:#475569; }
.filtros input, .filtros select { padding:9px 11px; border:1px solid #cbd5e1; border-radius:7px; font:inherit; font-size:13px; min-width:170px; background:#fff; }
.filtros input:focus, .filtros select:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
.filtros .conteo { margin-left:auto; font-size:12px; color:#64748b; }
.filtros .limpiar { padding:9px 13px; border:1px solid #cbd5e1; border-radius:7px; background:#fff; color:#334155; cursor:pointer; font:inherit; font-size:13px; }
.filtros .limpiar:hover { background:#f1f5f9; }

.stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; margin-bottom:20px; }
.stat { padding:16px 18px; border-radius:10px; background:#f1f5f9; }
.stat .stat-valor { font-size:26px; font-weight:700; color:#172337; }
.stat .stat-etiqueta { font-size:12px; color:#64748b; margin-top:2px; }

.charts { display:grid; grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); gap:20px; margin-bottom:20px; }
.chart-box { position:relative; height:300px; }

dialog.modal { border:none; border-radius:12px; padding:24px; max-width:460px; width:92%; box-shadow:0 20px 50px rgba(0,0,0,0.25); }
dialog.modal::backdrop { background:rgba(15,23,42,0.55); }
dialog.modal h3 { margin-top:0; }
dialog.modal .form-group input, dialog.modal .form-group select, dialog.modal .form-group textarea { max-width:100%; }
.modal-acciones { display:flex; gap:8px; justify-content:flex-end; margin-top:8px; }

/* COFIHO V2 - identidad visual corporativa; conserva clases y funcionalidades existentes */
:root{--cofiho-navy:#101c32;--cofiho-blue:#2563eb;--cofiho-border:#e5ebf3}
body{font-family:Inter,'Segoe UI',Arial,sans-serif;background:#f5f7fb;color:#18243b;font-size:14px}
.sidebar{width:258px;background:linear-gradient(180deg,#14223b 0%,#0d182c 100%);border-right:1px solid #263652;box-shadow:5px 0 28px #0e1c3410;z-index:10}
.brand{min-height:96px;padding:23px 19px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #ffffff16;font-size:18px;letter-spacing:.02em}
.brand-symbol{width:44px;height:44px;display:grid;place-items:center;border-radius:13px;background:linear-gradient(135deg,#60a5fa,#2563eb);color:white!important;font-size:22px;box-shadow:0 8px 20px #0003}
.brand-word{color:white!important;display:block;line-height:1.1}.brand-word em{font-style:normal;color:#7db5ff}.brand-word small{display:block;color:#93a5c2;font-size:9px;letter-spacing:.17em;font-weight:600;margin-top:7px}
.menu{padding:27px 12px 16px}.menu-caption{font-size:10px;color:#7187a8;font-weight:800;letter-spacing:.17em;padding:0 15px;margin-bottom:14px}
.menu a{display:flex;align-items:center;gap:13px;padding:12px 14px;margin-bottom:5px;color:#bdcbe0;border-radius:10px;font-size:13px;font-weight:550;min-height:44px}
.menu a:hover{background:#ffffff12;color:#fff}.menu a.active{background:linear-gradient(95deg,#2563eb,#1c50c5);box-shadow:0 7px 18px #06132b4a;color:white}
.nav-icon{width:19px;height:19px;flex-shrink:0;opacity:.88}.sidebar-footer{padding:17px 13px 20px;border-top:1px solid #ffffff19}
.user-block{display:flex;align-items:center;gap:11px}.user-avatar{width:38px;height:38px;flex-shrink:0;border-radius:11px;background:#284b83;color:#e4efff;display:grid;place-items:center;font-weight:800;font-size:16px}.user-name{font-size:12px}.user-role{font-size:10px;letter-spacing:.05em;margin-top:5px}
.logout-button{border-color:#ffffff24;border-radius:10px;text-align:center;font-size:12px;font-weight:600;padding:12px;margin-top:17px}.logout-button:hover{background:#ffffff14}
.main{margin-left:258px}.topbar{min-height:91px;padding:19px 32px;border-bottom:1px solid var(--cofiho-border);box-shadow:0 2px 14px #17243b05}.heading-kicker{display:block;color:#8b9bb1;font-size:10px;font-weight:800;letter-spacing:.13em;margin-bottom:7px}.topbar h1{font-size:22px;letter-spacing:-.035em;font-weight:750;color:#12213a}.system{display:inline-flex;align-items:center;gap:9px;background:#f4f7fc;border:1px solid #e6ecf5;border-radius:30px;padding:10px 14px;font-size:12px}.system-dot{width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 4px #dcfce7}
.content{padding:30px 32px;max-width:1800px;margin:auto}.card{border:1px solid #e8edf5;border-radius:16px;padding:26px;box-shadow:0 8px 30px #1c2e4a0b;margin-bottom:24px}.card h2{color:#14233c;letter-spacing:-.025em;font-size:19px}
.table-responsive{border-radius:10px}table{font-size:13px}th{background:#f3f6fb;color:#53657f;text-transform:uppercase;font-size:10px;letter-spacing:.065em;font-weight:800;padding:15px 14px}td{padding:15px 14px;border-bottom:1px solid #edf1f7}tr:hover td{background:#f8faff}
.btn{border-radius:9px;padding:11px 16px;font-weight:650;font-size:12px;box-shadow:0 3px 10px #2563eb18}.btn-sm{padding:8px 11px;font-size:11px}.badge{font-size:10px;letter-spacing:.025em;padding:6px 10px}.form-group input,.form-group select,.form-group textarea{border-radius:9px}
@media(max-width:768px){.sidebar{width:100%;height:auto;position:relative}.brand{min-height:75px}.menu{padding:15px 12px;display:flex;gap:6px;flex-wrap:wrap}.menu-caption{width:100%}.menu a{margin:0;padding:10px 12px}.main{margin-left:0}.topbar{padding:18px;min-height:75px}.topbar h1{font-size:19px}.system{display:none}.content{padding:18px}.card{padding:18px}}


        /* Notificaciones temporales COFIHO */
        .cofiho-alert { display:flex; align-items:center; justify-content:space-between; gap:14px; padding:14px 18px; border-radius:10px; margin-bottom:20px; border:1px solid; transition:opacity .4s ease,transform .4s ease,margin .4s ease,padding .4s ease; }
        .cofiho-alert-success { background:#dcfce7; color:#166534; border-color:#86efac; }
        .cofiho-alert-error { background:#fee2e2; color:#991b1b; border-color:#fca5a5; }
        .cofiho-alert-close { border:0; background:transparent; color:inherit; font-size:22px; line-height:1; cursor:pointer; padding:2px 5px; opacity:.75; }
        .cofiho-alert-close:hover { opacity:1; }
        .cofiho-alert.is-hiding { opacity:0; transform:translateY(-10px); }
        @media (prefers-reduced-motion:reduce) { .cofiho-alert { transition:none; } }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/halloween.css') }}">
    @stack('styles')
</head>
<body>
    @php
        $usuarioActual = auth()->user();
        $rolActual = $usuarioActual?->rol?->nombre;
    @endphp

    <aside class="sidebar">
        <div class="brand"><span class="brand-symbol">C</span><span class="brand-word">COFIHO <em>V2</em><small>GESTIÓN AUTOMOTRIZ</small></span></div>

        <nav class="menu" aria-label="Menú principal"><div class="menu-caption">MENÚ PRINCIPAL</div>
            @if(in_array($rolActual, ['ADMINISTRADOR', 'VENDEDOR', 'INSPECTOR'], true))
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="5" rx="1.5"/><rect x="13" y="10" width="8" height="11" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/></svg><span>Inicio</span></a>
            @endif
            @if(in_array($rolActual, ['ADMINISTRADOR', 'VENDEDOR', 'INSPECTOR'], true))
                <a href="{{ route('vehiculos.index') }}" class="{{ request()->routeIs('vehiculos.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 17h14l-1.5-7h-11L5 17Z"/><path d="m7 10 2-4h6l2 4"/><path d="M5 17v2m14-2v2M7 14h.01M17 14h.01"/></svg><span>Vehículos</span></a>
                <a href="{{ route('inventario.index') }}" class="{{ request()->routeIs('inventario.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21V9l9-6 9 6v12"/><path d="M9 21v-8h6v8M3 10h18"/></svg><span>Inventario</span></a>
            @endif

            @if(in_array($rolActual, ['ADMINISTRADOR', 'VENDEDOR'], true))
                <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg><span>Clientes</span></a>
            @endif

            @if($rolActual === 'ADMINISTRADOR')
                <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><circle cx="12" cy="10" r="2"/><path d="M8.5 16a4 4 0 0 1 7 0"/></svg><span>Usuarios</span></a>
            @endif

            @if(in_array($rolActual, ['ADMINISTRADOR', 'VENDEDOR'], true))
                <a href="{{ route('reservas.index') }}" class="{{ request()->routeIs('reservas.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18m-12 5 2 2 4-4"/></svg><span>Reservas</span></a>
                <a href="{{ route('ventas.index') }}" class="{{ request()->routeIs('ventas.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18M7 15h3"/></svg><span>Ventas</span></a>
            @endif

            @if($rolActual === 'ADMINISTRADOR')
                <a href="{{ route('finanzas.index') }}" class="{{ request()->routeIs('finanzas.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="6" width="18" height="15" rx="2"/><path d="M3 10h18M16 15h3"/></svg><span>Finanzas</span></a>
            @endif

            @if(in_array($rolActual, ['ADMINISTRADOR', 'INSPECTOR'], true))
                <a href="{{ route('inspecciones.index') }}" class="{{ request()->routeIs('inspecciones.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="4" width="14" height="18" rx="2"/><path d="M9 4.5V3h6v1.5M9 13l2 2 4-4"/></svg><span>Inspecciones</span></a>
            @endif

            @if($rolActual === 'ADMINISTRADOR')
                <a href="{{ route('reportes.index') }}" class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18M7 16v-4m5 4V7m5 9v-6"/></svg><span>Reportes</span></a>
            @endif
        </nav>

        @auth
            <div class="sidebar-footer">
                <div class="user-block"><span class="user-avatar">{{ strtoupper(substr($usuarioActual->nombre ?? "U", 0, 1)) }}</span><div><div class="user-name">{{ $usuarioActual->nombre }}</div>
                <div class="user-role">{{ $rolActual ?? 'Sin rol asignado' }}</div></div></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="logout-button" type="submit">Cerrar sesión</button>
                </form>
            </div>
        @endauth
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="page-heading"><span class="heading-kicker">COFIHO / PANEL DE CONTROL</span><h1>@yield('page-title', 'Panel de administración')</h1></div>
            <span class="system"><span class="system-dot"></span> Sistema de Gestión COFIHO</span>
        </header>
        
<main class="content">

    @if (session('success'))
        <div class="cofiho-alert cofiho-alert-success" role="status" data-auto-dismiss="5000">
            <span>{{ session('success') }}</span>
            <button type="button" class="cofiho-alert-close" aria-label="Cerrar notificación">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="cofiho-alert cofiho-alert-error" role="alert" data-auto-dismiss="5000">
            <span>{{ session('error') }}</span>
            <button type="button" class="cofiho-alert-close" aria-label="Cerrar notificación">&times;</button>
        </div>
    @endif

    @yield('content')

</main>

    </div>

    <script>
    /* Filtros automáticos para cualquier <table data-filtrable data-filtros="Estado,Rol"> */
    (function () {
        document.querySelectorAll('table[data-filtrable]').forEach(function (tabla) {
            var cuerpo = tabla.tBodies[0];
            if (!cuerpo) { return; }

            var filas = Array.prototype.filter.call(cuerpo.rows, function (fila) {
                return !(fila.cells.length === 1 && fila.cells[0].colSpan > 1);
            });
            if (filas.length === 0) { return; }

            var encabezados = Array.prototype.map.call(tabla.tHead.rows[0].cells, function (th) {
                return th.textContent.trim().toLowerCase();
            });

            var barra = document.createElement('div');
            barra.className = 'filtros';

            var buscar = document.createElement('div');
            buscar.className = 'filtro';
            buscar.innerHTML = '<label>Buscar</label><input type="search" placeholder="Escribe para filtrar...">';
            barra.appendChild(buscar);
            var inputBuscar = buscar.querySelector('input');

            var selects = [];
            (tabla.dataset.filtros || '').split(',').forEach(function (nombre) {
                nombre = nombre.trim();
                if (!nombre) { return; }
                var col = encabezados.indexOf(nombre.toLowerCase());
                if (col < 0) { return; }

                var valores = [];
                filas.forEach(function (fila) {
                    var v = fila.cells[col].textContent.trim();
                    if (v && valores.indexOf(v) < 0) { valores.push(v); }
                });
                valores.sort();

                var caja = document.createElement('div');
                caja.className = 'filtro';
                var etiqueta = document.createElement('label');
                etiqueta.textContent = nombre;
                var select = document.createElement('select');
                select.innerHTML = '<option value="">Todos</option>';
                valores.forEach(function (v) {
                    var op = document.createElement('option');
                    op.value = v;
                    op.textContent = v;
                    select.appendChild(op);
                });
                caja.appendChild(etiqueta);
                caja.appendChild(select);
                barra.appendChild(caja);
                selects.push({ col: col, el: select });
            });

            var limpiar = document.createElement('button');
            limpiar.type = 'button';
            limpiar.className = 'limpiar';
            limpiar.textContent = 'Limpiar';
            barra.appendChild(limpiar);

            var conteo = document.createElement('span');
            conteo.className = 'conteo';
            barra.appendChild(conteo);

            var contenedor = tabla.closest('.table-responsive') || tabla;
            contenedor.parentNode.insertBefore(barra, contenedor);

            function aplicar() {
                var texto = inputBuscar.value.trim().toLowerCase();
                var visibles = 0;
                filas.forEach(function (fila) {
                    var ok = !texto || fila.textContent.toLowerCase().indexOf(texto) >= 0;
                    selects.forEach(function (s) {
                        if (ok && s.el.value && fila.cells[s.col].textContent.trim() !== s.el.value) { ok = false; }
                    });
                    fila.style.display = ok ? '' : 'none';
                    if (ok) { visibles++; }
                });
                conteo.textContent = 'Mostrando ' + visibles + ' de ' + filas.length;
            }

            inputBuscar.addEventListener('input', aplicar);
            selects.forEach(function (s) { s.el.addEventListener('change', aplicar); });
            limpiar.addEventListener('click', function () {
                inputBuscar.value = '';
                selects.forEach(function (s) { s.el.value = ''; });
                aplicar();
            });
            aplicar();
        });
    })();
    </script>
    <script>
    document.querySelectorAll('.cofiho-alert[data-auto-dismiss]').forEach(function (alerta) {
        var cerrando = false;
        function cerrar() {
            if (cerrando) return;
            cerrando = true;
            alerta.classList.add('is-hiding');
            window.setTimeout(function () { alerta.remove(); }, 400);
        }
        var boton = alerta.querySelector('.cofiho-alert-close');
        if (boton) boton.addEventListener('click', cerrar);
        window.setTimeout(cerrar, Number(alerta.dataset.autoDismiss) || 5000);
    });
    </script>
    @stack('scripts')

    <script src="{{ asset('js/halloween.js') }}" defer></script>
</body>
</html>
