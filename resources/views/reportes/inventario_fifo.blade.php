@extends('layouts.app')

@section('title', 'Inventario FIFO - COFIHO V2')

@section('page-title', 'Reporte de Inventario FIFO')

@section('content')
    <style>
        .report-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap
        }

        .report-kicker {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.4px;
            color: #2563eb;
            text-transform: uppercase
        }

        .report-sub {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
            margin: 7px 0 0
        }

        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 20px
        }

        .report-tile {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 22px;
            background: #fff;
            transition: transform .2s, box-shadow .2s
        }

        .report-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px #152d5210
        }

        .report-icon {
            display: inline-grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 21px;
            margin-bottom: 16px
        }

        .report-tile h3 {
            font-size: 16px;
            margin: 0 0 9px;
            color: #0f172a
        }

        .report-tile p {
            font-size: 13px;
            line-height: 1.6;
            color: #64748b;
            min-height: 44px;
            margin: 0 0 18px
        }

        .report-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
            gap: 12px;
            margin-bottom: 20px
        }

        .report-stat {
            padding: 18px 20px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 13px
        }

        .report-stat small {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px
        }

        .report-stat strong {
            font-size: 25px;
            color: #0f172a;
            font-weight: 800
        }

        .report-stat em {
            display: block;
            font-style: normal;
            color: #64748b;
            font-size: 11px;
            margin-top: 5px
        }

        .report-card {
            margin-bottom: 20px
        }

        .report-card h2 {
            margin-bottom: 8px
        }

        .report-chart {
            height: 255px;
            position: relative
        }

        .report-filter {
            display: flex;
            gap: 12px;
            align-items: end;
            flex-wrap: wrap;
            margin: 16px 0
        }

        .report-filter label {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            display: block;
            margin-bottom: 6px
        }

        .report-filter input,
        .report-filter select {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background: white;
            color: #0f172a;
            min-width: 180px;
            max-width: 100%
        }

        .report-filter button {
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background: #fff;
            cursor: pointer
        }

        .report-table {
            width: 100%
        }

        .report-table th {
            white-space: nowrap
        }

        .report-table td {
            vertical-align: middle
        }

        .report-pill {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800
        }

        .report-pill.ok {
            background: #dcfce7;
            color: #166534
        }

        .report-pill.warn {
            background: #fef3c7;
            color: #92400e
        }

        .report-pill.bad {
            background: #fee2e2;
            color: #991b1b
        }

        .report-pill.neutral {
            background: #e2e8f0;
            color: #334155
        }

        .report-empty {
            padding: 30px;
            text-align: center;
            color: #64748b
        }

        .report-footer {
            margin-top: 16px
        }

        .report-muted {
            font-size: 12px;
            color: #64748b
        }

        @media(max-width:700px) {
            .report-chart {
                height: 220px
            }

            .report-stat strong {
                font-size: 21px
            }
        }
    </style>
    @php
        $porPredio = $vehiculos->groupBy('id_predio')->map(fn($items) => $items->count())->sortKeys();
        $etiquetas = $porPredio->keys()->map(fn($id) => 'Predio ' . $id)->values();
        $cantidades = $porPredio->values();
        $ordenados = $vehiculos->sort(function ($a, $b) {
            $fechaA = strtotime((string) $a->fecha_ingreso);
            $fechaB = strtotime((string) $b->fecha_ingreso);
            return ($fechaA <=> $fechaB) ?: ((int) $a->id_vehiculo <=> (int) $b->id_vehiculo);
        })->values();
    @endphp
    <div class="report-head">
        <div>
            <div class="report-kicker">Operaciones / FIFO</div>
            <h2 style="margin:6px 0">Inventario FIFO</h2>
            <p class="report-sub">Orden de ingreso y distribución de unidades por predio.</p>
        </div><a href="{{ route('reportes.index') }}" class="btn">← Centro de reportes</a>
    </div>
    <div class="report-stat-grid">
        <div class="report-stat"><small>Vehículos en reporte</small><strong>{{ $vehiculos->count() }}</strong><em>Registros
                disponibles en la vista FIFO</em></div>
        <div class="report-stat"><small>Predios</small><strong>{{ $porPredio->count() }}</strong><em>Con vehículos en el
                reporte</em></div>
        <div class="report-stat"><small>Antigüedad máxima</small><strong>{{ (int) $vehiculos->max('dias_inventario') }}
                días</strong><em>Mayor permanencia registrada</em></div>
        <div class="report-stat"><small>Valor del inventario</small><strong style="font-size:20px">Q
                {{ number_format((float) $vehiculos->sum('precio_lista'), 2) }}</strong><em>Suma de precios de lista</em>
        </div>
    </div>
    <div class="card report-card">
        <h2>Distribución por predio</h2>
        <p class="report-sub">Cantidad de vehículos incluidos en el reporte FIFO.</p>
        @if ($vehiculos->isNotEmpty())
        <div class="report-chart"><canvas id="graficaFifoPredios"></canvas></div>@else<div class="report-empty">No hay
                datos disponibles para graficar.</div>
        @endif
    </div>
    <div class="card report-card">
        <h2>Detalle de inventario FIFO</h2>
        <p class="report-sub">Orden global por fecha de ingreso, sin separar los vehículos por predio.</p>
        <div class="report-filter">
            <div><label for="fifoBuscar">Buscar vehículo</label><input id="fifoBuscar" placeholder="VIN o ID de vehículo">
            </div>
            <div><label for="fifoEstado">Estado</label><select id="fifoEstado">
                    <option value="">Todos</option>
                    <option value="DISPONIBLE">Disponible</option>
                    <option value="RESERVADO">Reservado</option>
                    <option value="VENDIDO">Vendido</option>
                </select></div><button type="button" id="fifoLimpiar">Limpiar filtros</button><span id="fifoContador"
                class="report-muted"></span>
        </div>
        <div class="table-responsive">
            <table class="report-table" id="fifoTabla">
                <thead>
                    <tr>
                        <th>Orden FIFO</th>
                        <th>ID inventario</th>
                        <th>Predio</th>
                        <th>ID vehículo</th>
                        <th>VIN</th>
                        <th>Condición</th>
                        <th>Estado</th>
                        <th>Precio lista</th>
                        <th>Fecha ingreso</th>
                        <th>Días</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordenados as $posicion => $v)
                        <tr data-search="{{ strtolower($v->vin . ' ' . $v->id_vehiculo) }}"
                            data-estado="{{ strtoupper($v->estado) }}">
                            <td><strong>{{ $posicion + 1 }}</strong></td>
                            <td>{{ $v->id_inventario }}</td>
                            <td>{{ $v->id_predio }}</td>
                            <td>{{ $v->id_vehiculo }}</td>
                            <td>{{ $v->vin }}</td>
                            <td>{{ $v->condicion }}</td>
                            <td><span
                                    class="report-pill {{ $v->estado === 'DISPONIBLE' ? 'ok' : ($v->estado === 'RESERVADO' ? 'warn' : 'neutral') }}">{{ $v->estado }}</span>
                            </td>
                            <td>Q {{ number_format((float) $v->precio_lista, 2) }}</td>
                            <td>{{ $v->fecha_ingreso }}</td>
                            <td>{{ $v->dias_inventario }}</td>
                    </tr>@empty<tr>
                            <td colspan="10" class="report-empty">No hay vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const c = document.getElementById('graficaFifoPredios');
            if (c && window.Chart) new Chart(c, {
                type: 'bar',
                data: {
                    labels: @json($etiquetas),
                    datasets: [{
                        label: 'Vehículos',
                        data: @json($cantidades),
                        backgroundColor: '#2563eb',
                        borderRadius: 7,
                        maxBarThickness: 70
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            const input = document.getElementById('fifoBuscar'),
                select = document.getElementById('fifoEstado'),
                rows = [...document.querySelectorAll('#fifoTabla tbody tr[data-search]')],
                count = document.getElementById('fifoContador');

            function filter() {
                let n = 0;
                rows.forEach(r => {
                    let show = r.dataset.search.includes(input.value.trim().toLowerCase()) && (!select
                        .value || r.dataset.estado === select.value);
                    r.hidden = !show;
                    if (show) n++
                });
                count.textContent = `Mostrando ${n} de ${rows.length}`;
            }
            input.addEventListener('input', filter);
            select.addEventListener('change', filter);
            document.getElementById('fifoLimpiar').addEventListener('click', () => {
                input.value = '';
                select.value = '';
                filter()
            });
            filter()
        });
    </script>
@endsection
