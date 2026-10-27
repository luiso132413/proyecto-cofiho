@extends('layouts.app')
@section('title', 'Finanzas - COFIHO V2')
@section('page-title', 'Gestión Financiera')
@section('content')
    <style>
        .fin-wrap {
            display: grid;
            gap: 20px
        }

        .fin-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap
        }

        .fin-heading h2 {
            font-size: 21px;
            margin: 0 0 6px;
            color: #14233d
        }

        .fin-muted {
            font-size: 13px;
            color: #71809a;
            margin: 0
        }

        .fin-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px
        }

        .fin-stat {
            padding: 20px;
            border: 1px solid #e6ecf5;
            border-radius: 14px;
            background: white;
            box-shadow: 0 4px 16px #14233d06
        }

        .fin-stat .label {
            font-size: 12px;
            color: #71809a;
            font-weight: 700
        }

        .fin-stat .value {
            font-size: 24px;
            font-weight: 800;
            color: #14233d;
            margin-top: 10px
        }

        .fin-stat .hint {
            font-size: 11px;
            color: #8795aa;
            margin-top: 5px
        }

        .fin-panel {
            background: #fff;
            border: 1px solid #e6ecf5;
            border-radius: 15px;
            padding: 22px;
            box-shadow: 0 4px 18px #14233d06
        }

        .fin-panel h3 {
            font-size: 16px;
            margin: 0 0 6px;
            color: #14233d
        }

        .fin-filters {
            display: grid;
            grid-template-columns: 2fr repeat(2, 1fr) auto;
            gap: 12px;
            align-items: end;
            margin: 20px 0 14px;
            padding: 16px;
            background: #f7f9fd;
            border: 1px solid #e6ecf5;
            border-radius: 12px
        }

        .fin-field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 7px;
            color: #42516b
        }

        .fin-field input,
        .fin-field select {
            width: 100%;
            height: 40px;
            padding: 0 11px;
            border: 1px solid #dbe3ef;
            border-radius: 9px;
            background: white;
            color: #14233d;
            min-width: 0
        }

        .fin-action {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            padding: 11px 15px;
            border-radius: 9px;
            background: #2563eb;
            color: white !important;
            text-decoration: none;
            border: 0;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap
        }

        .fin-action.secondary {
            background: #edf2fa;
            color: #334664 !important
        }

        .fin-table-scroll {
            overflow-x: auto
        }

        .fin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 850px
        }

        .fin-table th {
            padding: 13px 12px;
            background: #f2f5fa;
            color: #536582;
            text-transform: uppercase;
            letter-spacing: .05em;
            text-align: left;
            font-size: 10px;
            white-space: nowrap
        }

        .fin-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #edf1f7;
            color: #34425b
        }

        .fin-table tbody tr:hover {
            background: #f8faff
        }

        .fin-table .money {
            font-weight: 800;
            color: #14233d;
            white-space: nowrap
        }

        .fin-pill {
            display: inline-block;
            border-radius: 99px;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: 800;
            background: #edf2fa;
            color: #536582
        }

        .fin-pill.green {
            background: #dcfce7;
            color: #166534
        }

        .fin-pill.red {
            background: #fee2e2;
            color: #991b1b
        }

        .fin-pill.blue {
            background: #dbeafe;
            color: #1d4ed8
        }

        .fin-pill.amber {
            background: #fef3c7;
            color: #92400e
        }

        .fin-pagination { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-top:18px; }
        .fin-pages { display:flex; gap:6px; flex-wrap:wrap; }
        .fin-page-btn { border:1px solid #dbe3ef; background:white; border-radius:8px; padding:8px 12px; color:#334664; cursor:pointer; font-weight:700; }
        .fin-page-btn.active { background:#2563eb; color:white; border-color:#2563eb; }
        .fin-page-btn:disabled { opacity:.45; cursor:not-allowed; }
        .fin-count {
            color: #71809a;
            font-size: 12px;
            margin-bottom: 12px
        }

        .fin-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px
        }

        .fin-detail-item {
            border: 1px solid #e6ecf5;
            background: #f8fafd;
            border-radius: 10px;
            padding: 15px;
            min-width: 0
        }

        .fin-detail-item span {
            display: block;
            color: #71809a;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px
        }

        .fin-detail-item strong {
            display: block;
            color: #14233d;
            font-size: 14px;
            overflow-wrap: anywhere
        }

        .fin-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap
        }

        .fin-empty {
            text-align: center;
            padding: 35px !important;
            color: #71809a !important
        }

        @media(max-width:1100px) {
            .fin-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .fin-filters {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:650px) {

            .fin-grid,
            .fin-filters,
            .fin-detail-grid {
                grid-template-columns: 1fr
            }

            .fin-panel {
                padding: 15px
            }
        }
    </style>
    @php
        $aprobadas = $transacciones->filter(fn($t) => strtoupper(trim((string) $t->estado)) === 'APROBADA');
        $pagos = $aprobadas->filter(fn($t) => strtoupper(trim((string) $t->tipo)) === 'PAGO')->sum('monto');
        $enganches = $aprobadas->filter(fn($t) => strtoupper(trim((string) $t->tipo)) === 'ENGANCHE')->sum('monto');
        $devoluciones = $aprobadas
            ->filter(fn($t) => strtoupper(trim((string) $t->tipo)) === 'DEVOLUCION')
            ->sum('monto');
        $neto = $pagos + $enganches - $devoluciones;
    @endphp
    <div class="fin-wrap">
        <div class="fin-heading">
            <div>
                <h2>Resumen financiero</h2>
                <p class="fin-muted">Consulta las operaciones registradas y su estado de aprobación.</p>
            </div><span class="fin-pill blue">{{ $transacciones->count() }} transacciones</span>
        </div>
        <div class="fin-grid">
            <div class="fin-stat">
                <div class="label">Pagos aprobados</div>
                <div class="value">Q {{ number_format($pagos, 2) }}</div>
                <div class="hint">Transacciones de tipo PAGO</div>
            </div>
            <div class="fin-stat">
                <div class="label">Enganches aprobados</div>
                <div class="value">Q {{ number_format($enganches, 2) }}</div>
                <div class="hint">Transacciones de tipo ENGANCHE</div>
            </div>
            <div class="fin-stat">
                <div class="label">Devoluciones aprobadas</div>
                <div class="value">Q {{ number_format($devoluciones, 2) }}</div>
                <div class="hint">Montos devueltos</div>
            </div>
            <div class="fin-stat">
                <div class="label">Flujo neto registrado</div>
                <div class="value">Q {{ number_format($neto, 2) }}</div>
                <div class="hint">Pagos + enganches − devoluciones; no equivale a conciliación</div>
            </div>
        </div>
        <div class="fin-panel">
            <h3>Historial de transacciones</h3>
            <p class="fin-muted">Filtra por identificador, tipo o estado de la operación.</p>
            <div class="fin-filters">
                <div class="fin-field"><label for="buscarFinanza">Buscar transacción</label><input type="search"
                        id="buscarFinanza" placeholder="ID de transacción, venta o reserva" oninput="filtrarFinanzas()">
                </div>
                <div class="fin-field"><label for="filtroTipo">Tipo</label><select id="filtroTipo"
                        onchange="filtrarFinanzas()">
                        <option value="">Todos los tipos</option>
                        @foreach ($transacciones->pluck('tipo')->filter()->unique()->sort() as $tipo)
                            <option value="{{ $tipo }}">{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fin-field"><label for="filtroEstado">Estado</label><select id="filtroEstado"
                        onchange="filtrarFinanzas()">
                        <option value="">Todos los estados</option>
                        @foreach ($transacciones->pluck('estado')->filter()->unique()->sort() as $estado)
                            <option value="{{ $estado }}">{{ $estado }}</option>
                        @endforeach
                    </select></div>
                <button type="button" class="fin-action secondary" onclick="limpiarFiltrosFinanzas()">Limpiar
                    filtros</button>
            </div>
            <div id="contadorFinanzas" class="fin-count">Mostrando {{ $transacciones->count() }} transacciones</div>
            <div class="fin-table-scroll">
                <table id="tablaFinanzas" class="fin-table">
                    <thead>
                        <tr>
                            <th>Transacción</th>
                            <th>Venta</th>
                            <th>Reserva</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transacciones as $transaccion)
                            @php
                                $tipo = strtoupper(trim((string) $transaccion->tipo));
                                $estado = strtoupper(trim((string) $transaccion->estado));
                            @endphp
                            <tr class="fila-finanza" data-transaccion="{{ $transaccion->id_transaccion }}"
                                data-venta="{{ $transaccion->id_venta ?? '' }}"
                                data-reserva="{{ $transaccion->id_reserva ?? '' }}" data-tipo="{{ $transaccion->tipo }}"
                                data-estado="{{ $transaccion->estado }}">
                                <td><strong>#{{ $transaccion->id_transaccion }}</strong></td>
                                <td>{{ $transaccion->id_venta ?? '—' }}</td>
                                <td>{{ $transaccion->id_reserva ?? '—' }}</td>
                                <td><span
                                        class="fin-pill {{ $tipo === 'PAGO' ? 'blue' : ($tipo === 'ENGANCHE' ? 'green' : ($tipo === 'DEVOLUCION' ? 'amber' : '')) }}">{{ $transaccion->tipo }}</span>
                                </td>
                                <td class="money">{{ $tipo === 'DEVOLUCION' ? '− ' : '' }}Q
                                    {{ number_format((float) $transaccion->monto, 2) }}</td>
                                <td><span
                                        class="fin-pill {{ $estado === 'APROBADA' ? 'green' : (in_array($estado, ['RECHAZADA', 'ANULADA']) ? 'red' : 'amber') }}">{{ $transaccion->estado }}</span>
                                </td>
                                <td>{{ $transaccion->fecha_transaccion ?? 'No registrada' }}</td>
                                <td><a class="fin-action"
                                        href="{{ route('finanzas.show', $transaccion->id_transaccion) }}">Ver detalle</a>
                                </td>
                            </tr>
                        @empty<tr>
                                <td colspan="8" class="fin-empty">No hay transacciones financieras registradas.</td>
                            </tr>
                        @endforelse
                        @if ($transacciones->isNotEmpty())
                            <tr id="sinResultadosFinanzas" style="display:none">
                                <td colspan="8" class="fin-empty">No se encontraron transacciones con esos filtros.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="fin-pagination" id="paginacionFinanzas" hidden>
                <span class="fin-count" id="rangoFinanzas"></span>
                <nav class="fin-pages" id="botonesFinanzas" aria-label="Páginas de transacciones"></nav>
            </div>
        </div>
    </div>
    <script>
        const finPorPagina = 15;
        let finPagina = 1;
        let finFiltradas = [];

        function pintarPaginaFinanzas() {
            const total = finFiltradas.length;
            const paginas = Math.max(1, Math.ceil(total / finPorPagina));
            finPagina = Math.min(Math.max(1, finPagina), paginas);
            const inicio = (finPagina - 1) * finPorPagina;
            const visibles = new Set(finFiltradas.slice(inicio, inicio + finPorPagina));
            document.querySelectorAll('#tablaFinanzas .fila-finanza').forEach(fila => {
                fila.style.display = visibles.has(fila) ? '' : 'none';
            });
            document.getElementById('contadorFinanzas').textContent = 'Resultados encontrados: ' + total;
            const vacio = document.getElementById('sinResultadosFinanzas');
            if (vacio) vacio.style.display = total ? 'none' : '';
            const panel = document.getElementById('paginacionFinanzas');
            panel.hidden = total <= finPorPagina;
            document.getElementById('rangoFinanzas').textContent = total
                ? `Mostrando ${inicio + 1}–${Math.min(inicio + finPorPagina, total)} de ${total}`
                : 'Sin resultados';
            const nav = document.getElementById('botonesFinanzas');
            nav.replaceChildren();
            const agregar = (texto, pagina, deshabilitado, activo = false) => {
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.className = 'fin-page-btn' + (activo ? ' active' : '');
                boton.textContent = texto;
                boton.disabled = deshabilitado;
                if (activo) boton.setAttribute('aria-current', 'page');
                boton.addEventListener('click', () => {
                    finPagina = pagina;
                    pintarPaginaFinanzas();
                });
                nav.appendChild(boton);
            };
            agregar('Anterior', finPagina - 1, finPagina === 1);
            const desde = Math.max(1, Math.min(finPagina - 2, paginas - 4));
            const hasta = Math.min(paginas, desde + 4);
            for (let i = desde; i <= hasta; i++) agregar(String(i), i, false, i === finPagina);
            agregar('Siguiente', finPagina + 1, finPagina === paginas);
        }

        function filtrarFinanzas() {
            const buscar = document.getElementById('buscarFinanza').value.trim().toLowerCase();
            const tipo = document.getElementById('filtroTipo').value;
            const estado = document.getElementById('filtroEstado').value;
            finFiltradas = [...document.querySelectorAll('#tablaFinanzas .fila-finanza')].filter(fila => {
                const coincideBusqueda = !buscar || [fila.dataset.transaccion, fila.dataset.venta,
                    fila.dataset.reserva].some(valor => (valor || '').toLowerCase().includes(buscar));
                return coincideBusqueda && (!tipo || fila.dataset.tipo === tipo) &&
                    (!estado || fila.dataset.estado === estado);
            });
            finPagina = 1;
            pintarPaginaFinanzas();
        }

        function limpiarFiltrosFinanzas() {
            document.getElementById('buscarFinanza').value = '';
            document.getElementById('filtroTipo').value = '';
            document.getElementById('filtroEstado').value = '';
            filtrarFinanzas();
        }
        document.addEventListener('DOMContentLoaded', filtrarFinanzas);
    </script>
@endsection
