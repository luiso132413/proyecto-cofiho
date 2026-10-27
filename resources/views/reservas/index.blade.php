@extends('layouts.app')
@section('title', 'Reservas - COFIHO V2')
@section('page-title', 'Gestión de Reservas')
@section('content')
    <style>
        .rv-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px
        }

        .rv-muted {
            color: #64748b;
            font-size: 13px
        }

        .rv-primary {
            background: #2563eb;
            color: white;
            border: 0;
            border-radius: 10px;
            padding: 12px 18px;
            font-weight: 700;
            cursor: pointer
        }

        .rv-secondary {
            background: white;
            border: 1px solid #cbd5e1;
            color: #334155;
            border-radius: 10px;
            padding: 11px 16px;
            cursor: pointer
        }

        .rv-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px
        }

        .rv-stat {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 13px;
            padding: 19px
        }

        .rv-stat strong {
            display: block;
            font-size: 26px;
            color: #0f172a
        }

        .rv-stat span {
            font-size: 12px;
            color: #64748b
        }

        .rv-panel {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            padding: 22px;
            margin-bottom: 20px
        }

        .rv-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px
        }

        .rv-field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px
        }

        .rv-field input,
        .rv-field select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background: #fff;
            color: #0f172a;
            min-height: 44px
        }

        .rv-field input:focus,
        .rv-field select:focus {
            outline: 3px solid #dbeafe;
            border-color: #2563eb
        }

        .rv-step {
            border-top: 1px solid #e2e8f0;
            padding-top: 18px;
            margin-top: 20px
        }

        .rv-step h3 {
            font-size: 15px;
            margin: 0 0 13px
        }

        .rv-choice {
            display: flex;
            gap: 12px;
            flex-wrap: wrap
        }

        .rv-choice label {
            border: 1px solid #cbd5e1;
            padding: 13px 15px;
            border-radius: 10px;
            cursor: pointer;
            flex: 1;
            min-width: 220px
        }

        .rv-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 22px
        }


        .rv-filter-panel { display:grid; grid-template-columns:minmax(220px,320px) auto; justify-content:start; gap:12px; align-items:end; padding:18px; background:#f7f9fd; border:1px solid #e2e8f0; border-radius:12px; margin:16px 0 20px; }
        .rv-filter-panel .rv-field { min-width:0; }
        .rv-filter-panel .rv-field input,.rv-filter-panel .rv-field select { min-height:42px; }
        .rv-filter-clear { min-height:42px; white-space:nowrap; }
        .rv-filter-count { font-size:12px; color:#64748b; margin:0 0 12px; }
        @media(max-width:1100px) { .rv-filter-panel { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media(max-width:600px) { .rv-filter-panel { grid-template-columns:1fr; } }
        .rv-pagination { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-top:18px; }
        .rv-page-buttons { display:flex; gap:6px; flex-wrap:wrap; }
        .rv-page-buttons button { padding:8px 12px; border:1px solid #cbd5e1; border-radius:8px; background:#fff; color:#334155; cursor:pointer; font-weight:650; }
        .rv-page-buttons button[aria-current="page"] { background:#2563eb; border-color:#2563eb; color:white; }
        .rv-page-buttons button:disabled { opacity:.45; cursor:not-allowed; }
        .rv-scroll {
            overflow-x: auto
        }

        .rv-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px
        }

        .rv-table th {
            text-align: left;
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            letter-spacing: .04em;
            padding: 13px
        }

        .rv-table td {
            padding: 13px;
            border-bottom: 1px solid #e2e8f0
        }

        .rv-pill {
            border-radius: 999px;

            padding:5px 9px;background:#e2e8f0;font-size:11px;font-weight:700}.rv-pill[data-state="PENDIENTE"] {
                background: #fef3c7;
                color: #92400e
            }

            .rv-pill[data-state="CONFIRMADA"] {
                background: #dbeafe;
                color: #1d4ed8
            }

            .rv-pill[data-state="COMPLETADA"] {
                background: #dcfce7;
                color: #166534
            }

            .rv-pill[data-state="EXPIRADA"],
            .rv-pill[data-state="CANCELADA"] {
                background: #fee2e2;
                color: #991b1b
            }

            .rv-link {
                color: #2563eb;
                font-weight: 700;
                text-decoration: none
            }

            .rv-alert {
                padding: 13px 15px;
                border-radius: 10px;
                margin-bottom: 16px
            }

            .rv-alert-success {
                background: #dcfce7;
                color: #166534
            }

            .rv-alert-error {
                background: #fee2e2;
                color: #991b1b
            }

            [hidden] {
                display: none !important
            }

            @media(max-width:800px) {
                .rv-stats {
                    grid-template-columns: repeat(2, 1fr)
                }

                .rv-grid {
                    grid-template-columns: 1fr
                }
            }

            @media(max-width:480px) {
                .rv-stats {
                    grid-template-columns: 1fr
                }
            }
    </style>
    @php
        $totalReservas = $reservas->count();
        $pendientes = $reservas->filter(fn($r) => strtoupper(trim($r->estado ?? '')) === 'PENDIENTE')->count();
        $confirmadas = $reservas->filter(fn($r) => strtoupper(trim($r->estado ?? '')) === 'CONFIRMADA')->count();
        $completadas = $reservas->filter(fn($r) => strtoupper(trim($r->estado ?? '')) === 'COMPLETADA')->count();
    @endphp
    <div class="rv-head">
        <div>
            <h2 style="margin:0 0 5px">Control de reservas</h2>
            <p class="rv-muted" style="margin:0">Consulta y administra las reservas de vehículos desde un solo lugar.</p>
        </div><button type="button" class="rv-primary" id="rvOpen" aria-expanded="false" aria-controls="rvFormPanel">+ Nueva
            reserva</button>
    </div>
    <div class="rv-stats">
        <div class="rv-stat"><strong>{{ $totalReservas }}</strong><span>Total de reservas</span></div>
        <div class="rv-stat"><strong>{{ $pendientes }}</strong><span>Pendientes</span></div>
        <div class="rv-stat"><strong>{{ $confirmadas }}</strong><span>Confirmadas</span></div>
        <div class="rv-stat"><strong>{{ $completadas }}</strong><span>Completadas</span></div>
    </div>
    @if (session('success'))
        <div class="rv-alert rv-alert-success alert-success" role="status">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="rv-alert rv-alert-error alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif
    <section id="rvFormPanel" class="rv-panel" hidden>
        <div class="rv-head">
            <div>
                <h2 style="margin:0 0 5px">Nueva reserva</h2>
                <p class="rv-muted" style="margin:0">Selecciona al cliente y las características del vehículo.</p>
            </div><button class="rv-secondary" type="button" id="rvClose">Cerrar</button>
        </div>
        <form id="rvForm" method="POST" action="{{ route('reservas.store') }}"
            data-direct="{{ route('reservas.store') }}" data-fifo="{{ route('reservas.fifo') }}"
            data-checkout="{{ route('reservas.checkout') }}">
            @csrf
            <div class="rv-step">
                <h3>1. Cliente</h3>
                <div class="rv-grid">
                    <div class="rv-field"><label for="rvCliente">Cliente activo</label><select name="id_cliente"
                            id="rvCliente" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id_cliente }}" @selected(old('id_cliente') == $cliente->id_cliente)>
                                    {{ $cliente->nombre }} {{ $cliente->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rv-field"><label for="rvCanal">Canal de atención</label><select name="canal"
                            id="rvCanal" required>
                            <option value="AGENCIA" @selected(old('canal', 'AGENCIA') === 'AGENCIA')>Agencia</option>
                            <option value="WEB" @selected(old('canal') === 'WEB')>Web</option>
                            <option value="CORPORATIVO" @selected(old('canal') === 'CORPORATIVO')>Corporativo</option>
                        </select></div>
                </div>
            </div>
            <div class="rv-step">
                <h3>2. Vehículo</h3>
                <div class="rv-choice"><label><input type="radio" name="rv_seleccion" value="directa" checked>
                        <strong>Vehículo específico</strong>
                        <div class="rv-muted">Ya se conoce el vehículo que desea reservar el cliente.</div>
                    </label><label><input type="radio" name="rv_seleccion" value="fifo"> <strong>Buscar por
                            características</strong>
                        <div class="rv-muted">El sistema asignará el vehículo elegible según FIFO.</div>
                    </label></div>
                <div id="rvDirecto" class="rv-grid" style="margin-top:16px">
                    <div class="rv-field"><label for="rvVehiculo">Vehículo disponible</label>
                        <select name="id_vehiculo" id="rvVehiculo" required>
                            <option value="">Seleccione un vehículo</option>
                            @foreach ($vehiculosDisponibles as $vehiculo)
                                <option value="{{ $vehiculo->id_vehiculo }}" @selected(old('id_vehiculo') == $vehiculo->id_vehiculo)>
                                    {{ $vehiculo->marca }} {{ $vehiculo->modelo }} — {{ $vehiculo->anio }} — {{ $vehiculo->color }} — {{ $vehiculo->condicion }} — Q{{ number_format((float) $vehiculo->precio_lista, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="rv-muted">Solo aparecen vehículos con estado DISPONIBLE. La disponibilidad se valida nuevamente al confirmar.</small>
                    </div>
                </div>
                <div id="rvFifo" class="rv-grid" style="margin-top:16px" hidden>
                    <div class="rv-field"><label for="rvPredio">Predio</label><select id="rvPredio" name="id_predio"
                            disabled>
                            <option value="">Seleccione un predio</option>
                            <option value="1" @selected(old('id_predio') == 1)>Cofiño Stahl Central Zona 5</option>
                            <option value="2" @selected(old('id_predio') == 2)>Agencia Roosevelt</option>
                            <option value="3" @selected(old('id_predio') == 3)>Predio Seminuevos Cayalá</option>
                        </select></div>
                    <div class="rv-field"><label for="rvModelo">Modelo</label><select id="rvModelo" name="id_modelo"
                            disabled>
                            <option value="">Seleccione un modelo</option>
                            @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->id_modelo }}" @selected(old('id_modelo') == $modelo->id_modelo)>{{ $modelo->marca }} {{ $modelo->modelo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rv-field" style="grid-column:1/-1">
                        <label>Vehículos disponibles según los filtros</label>
                        <div id="rvFifoResultados" class="rv-muted" aria-live="polite">Selecciona un predio y un modelo.</div>
                        <small class="rv-muted">El sistema reservará automáticamente el vehículo con mayor prioridad FIFO. La lista es informativa.</small>
                    </div>
                </div>
            </div>
            <div class="rv-step">
                <h3>3. Condiciones de la reserva</h3><label style="display:flex;align-items:center;gap:9px"><input
                        type="checkbox" id="rvConPago"> Registrar enganche confirmado al crear la reserva</label>
                <p class="rv-muted">Si no hay pago confirmado, la reserva se crea sin enganche. Para asignación FIFO, el
                    enganche se registra posteriormente desde el detalle de la reserva.</p>
                <div id="rvPago" class="rv-grid" style="margin-top:15px" hidden>
                    <div class="rv-field"><label for="rvMetodo">Método de pago</label><select id="rvMetodo"
                            name="id_metodo_pago" disabled>
                            <option value="">Seleccione un método</option>
                            <option value="1">VisaNet</option>
                            <option value="2">Transferencia BAC</option>
                            <option value="3">POS Agencia</option>
                        </select></div>
                    <div class="rv-field"><label for="rvMonto">Enganche (Q)</label><input id="rvMonto" type="number"
                            name="monto_enganche" min="0.01" step="0.01" disabled></div>
                    <div class="rv-field"><label for="rvReferencia">Referencia del pago confirmado</label><input
                            id="rvReferencia" type="text" name="referencia" maxlength="120" disabled></div>
                </div>
            </div>
            <div class="rv-actions"><button type="button" class="rv-secondary" id="rvCancel">Cancelar</button><button
                    type="submit" class="rv-primary" id="rvSubmit">Confirmar reserva</button></div>
        </form>
    </section>
    <section class="rv-panel">
        <div class="rv-head">
            <div>
                <h2 style="margin:0 0 5px">Reservas registradas</h2>
                <p class="rv-muted" style="margin:0">Historial y estado actual de las operaciones.</p>
            </div>
        </div>
        <div class="rv-filter-panel" role="search" aria-label="Filtro de reservas">
            <div class="rv-field">
                <label for="rvFiltroEstado">Filtrar por estado</label>
                <select id="rvFiltroEstado">
                    <option value="">Todos los estados</option>
                    <option value="COMPLETADA">Completada</option>
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="CANCELADA">Cancelada</option>
                    <option value="EXPIRADA">Expirada</option>
                </select>
            </div>
            <button type="button" class="rv-secondary rv-filter-clear" id="rvLimpiarFiltros">Limpiar filtro</button>
        </div>
        <p class="rv-filter-count" id="rvFiltroResumen" aria-live="polite"></p>
        <div class="rv-scroll">
            <table class="rv-table" id="rvTabla">
                <thead>
                    <tr>
                        <th>Reserva</th>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $reserva)
                        <tr data-estado="{{ strtoupper(trim($reserva->estado ?? '')) }}" data-fecha="{{ substr((string) ($reserva->fecha_reserva ?? ''), 0, 10) }}">
                            <td><strong>#{{ $reserva->id_reserva }}</strong></td>
                            <td>{{ trim(($reserva->cliente_nombre ?? '') . ' ' . ($reserva->cliente_apellido ?? '')) }}</td>
                            <td>
                                <strong>{{ trim(($reserva->vehiculo_marca ?? '') . ' ' . ($reserva->vehiculo_modelo ?? '')) }}</strong>
                                <div class="rv-muted">Placa: {{ filled($reserva->vehiculo_placa ?? null) ? $reserva->vehiculo_placa : 'Sin placa' }}</div>
                            </td>
                            <td>{{ $reserva->fecha_reserva ?? 'No registrada' }}</td>
                            <td><span class="rv-pill"
                                    data-state="{{ strtoupper(trim($reserva->estado ?? '')) }}">{{ $reserva->estado ?? 'No definido' }}</span>
                            </td>
                            <td><a class="rv-link" href="{{ route('reservas.show', $reserva->id_reserva) }}">Ver
                                    detalles</a></td>
                    </tr>@empty<tr>
                            <td colspan="6">No hay reservas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <nav class="rv-pagination" id="rvPaginacion" aria-label="Paginación de reservas">
            <span class="rv-muted" id="rvResumenPaginas"></span>
            <div class="rv-page-buttons" id="rvBotonesPaginas"></div>
        </nav>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const panel = document.getElementById('rvFormPanel'),
                open = document.getElementById('rvOpen'),
                form = document.getElementById('rvForm');
            const modeInputs = [...document.querySelectorAll('input[name="rv_seleccion"]')],
                check = document.getElementById('rvConPago');
            const direct = document.getElementById('rvDirecto'),
                fifo = document.getElementById('rvFifo'),
                pago = document.getElementById('rvPago');
            const togglePanel = (show) => {
                panel.hidden = !show;
                open.setAttribute('aria-expanded', String(show));
                if (show) panel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            };
            open.addEventListener('click', () => togglePanel(panel.hidden));
            document.getElementById('rvClose').addEventListener('click', () => togglePanel(false));
            document.getElementById('rvCancel').addEventListener('click', () => togglePanel(false));

            const fifoDatos = @json($vehiculosFIFO);
            const fifoPredio = document.getElementById('rvPredio');
            const fifoModelo = document.getElementById('rvModelo');
            const fifoResultados = document.getElementById('rvFifoResultados');
            const botonReserva = document.getElementById('rvSubmit');

            function renderFIFO() {
                const predio = String(fifoPredio.value);
                const modelo = String(fifoModelo.value);
                fifoResultados.replaceChildren();
                if (!predio || !modelo) {
                    fifoResultados.textContent = 'Selecciona un predio y un modelo.';
                    return false;
                }
                const encontrados = fifoDatos.filter(v => String(v.id_predio) === predio && String(v.id_modelo) === modelo);
                if (!encontrados.length) {
                    fifoResultados.textContent = 'No se cuenta con vehículos disponibles de este modelo en el predio seleccionado.';
                    return false;
                }
                const lista = document.createElement('ol');
                lista.style.paddingLeft = '22px';
                encontrados.forEach((v, i) => {
                    const li = document.createElement('li');
                    const precio = Number(v.precio_lista).toLocaleString('es-GT', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    li.textContent = `${v.marca} ${v.modelo} · ${v.anio} · ${v.color} · Q${precio}${i === 0 ? ' — Prioridad FIFO' : ''}`;
                    li.style.padding = '5px 0';
                    lista.appendChild(li);
                });
                fifoResultados.appendChild(lista);
                return true;
            }

            function update() {
                const isFifo = modeInputs.find(el => el.checked).value === 'fifo';
                direct.hidden = isFifo;
                fifo.hidden = !isFifo;
                direct.querySelectorAll('input,select').forEach(el => {
                    el.disabled = isFifo;
                    el.required = !isFifo;
                });
                fifo.querySelectorAll('input,select').forEach(el => {
                    el.disabled = !isFifo;
                    el.required = isFifo;
                });
                check.disabled = isFifo;
                if (isFifo) check.checked = false;
                const withPay = !isFifo && check.checked;
                pago.hidden = !withPay;
                pago.querySelectorAll('input,select').forEach(el => {
                    el.disabled = !withPay;
                    el.required = withPay;
                });
                botonReserva.disabled = isFifo && !renderFIFO();
                form.action = isFifo ? form.dataset.fifo : (withPay ? form.dataset.checkout : form.dataset.direct);
                document.getElementById('rvSubmit').textContent = withPay ? 'Confirmar reserva y enganche' :
                    'Confirmar reserva';
            }
            modeInputs.forEach(el => el.addEventListener('change', update));
            check.addEventListener('change', update);
            fifoPredio.addEventListener('change', update);
            fifoModelo.addEventListener('change', update);
            update();
            // Paginación y filtro por estado sobre todas las reservas cargadas.
            const filas = [...document.querySelectorAll('#rvTabla tbody tr')].filter(row => row.querySelector('a.rv-link'));
            const resumen = document.getElementById('rvResumenPaginas');
            const botones = document.getElementById('rvBotonesPaginas');
            const paginacion = document.getElementById('rvPaginacion');
            const porPagina = 15;
            let paginaActual = 1;

            function pintarPagina() {
                const estado = document.getElementById('rvFiltroEstado').value;
                const coincidentes = filas.filter(row => !estado || row.dataset.estado === estado);
                document.getElementById('rvFiltroResumen').textContent = `${coincidentes.length} de ${filas.length} reservas coinciden con los filtros`;
                const total = coincidentes.length;
                const paginas = Math.max(1, Math.ceil(total / porPagina));
                paginaActual = Math.min(Math.max(1, paginaActual), paginas);
                filas.forEach(row => row.hidden = true);
                coincidentes.slice((paginaActual - 1) * porPagina, paginaActual * porPagina)
                    .forEach(row => row.hidden = false);
                const inicio = total ? (paginaActual - 1) * porPagina + 1 : 0;
                const fin = Math.min(paginaActual * porPagina, total);
                resumen.textContent = total ? `Mostrando ${inicio}–${fin} de ${total} reservas` : 'No se encontraron reservas';
                botones.replaceChildren();
                paginacion.hidden = filas.length === 0;

                function agregarBoton(etiqueta, pagina, deshabilitado = false, actual = false) {
                    const boton = document.createElement('button');
                    boton.type = 'button';
                    boton.textContent = etiqueta;
                    boton.disabled = deshabilitado;
                    if (actual) boton.setAttribute('aria-current', 'page');
                    boton.addEventListener('click', () => {
                        paginaActual = pagina;
                        pintarPagina();
                        document.getElementById('rvTabla').scrollIntoView({behavior:'smooth', block:'start'});
                    });
                    botones.appendChild(boton);
                }
                agregarBoton('Anterior', paginaActual - 1, paginaActual === 1);
                for (let p = Math.max(1, paginaActual - 2); p <= Math.min(paginas, paginaActual + 2); p++) {
                    agregarBoton(String(p), p, false, p === paginaActual);
                }
                agregarBoton('Siguiente', paginaActual + 1, paginaActual === paginas);
            }
            const refrescarFiltros = () => { paginaActual = 1; pintarPagina(); };
            document.getElementById('rvFiltroEstado').addEventListener('change', refrescarFiltros);
            document.getElementById('rvLimpiarFiltros').addEventListener('click', () => {
                document.getElementById('rvFiltroEstado').value = '';
                refrescarFiltros();
            });
            pintarPagina();
            @if ($errors->any())
                togglePanel(true);
            @endif
        });
    </script>
@endsection
