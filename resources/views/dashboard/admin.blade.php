@extends('layouts.app')
@section('title', 'Centro de operaciones | COFIHO V2')
@section('page-title', 'Centro de operaciones')
@push('styles')
    <style>
        .cofi-dashboard {
            --ink: #142746;
            --muted: #71809a;
            --line: #e3eaf4;
            color: var(--ink);
            max-width: 1600px;
            margin: 0 auto
        }

        .cofi-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(115deg, #0e203e 0%, #173a78 100%);
            border-radius: 19px;
            padding: 30px 34px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px
        }

        .cofi-hero:after {
            content: "";
            position: absolute;
            width: 280px;
            height: 280px;
            border: 1px solid #ffffff26;
            border-radius: 50%;
            right: -90px;
            top: -145px;
            box-shadow: 0 0 0 52px #ffffff08, 0 0 0 104px #ffffff05;
            pointer-events: none
        }

        .cofi-hero-content {
            position: relative;
            z-index: 1
        }

        .cofi-eyebrow {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .15em;
            color: #9dc5ff;
            margin-bottom: 10px
        }

        .cofi-hero h2 {
            font-size: clamp(24px, 2.7vw, 33px);
            margin: 0 0 8px;
            color: #fff;
            font-weight: 800
        }

        .cofi-hero p {
            color: #d6e3f8;
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
            max-width: 600px
        }

        .cofi-date {
            position: relative;
            z-index: 1;
            border: 1px solid #ffffff30;
            background: #ffffff13;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 12px;
            color: #e7efff;
            white-space: nowrap
        }

        .cofi-section {
            margin-top: 26px
        }

        .cofi-section-head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 12px;
            margin-bottom: 14px
        }

        .cofi-section-head h3 {
            font-size: 16px;
            font-weight: 800;
            margin: 0;
            color: #142746
        }

        .cofi-section-head p {
            font-size: 12px;
            color: var(--muted);
            margin: 5px 0 0
        }

        .cofi-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px
        }

        .cofi-kpi {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 15px;
            padding: 19px;
            min-width: 0;
            box-shadow: 0 5px 18px #142b5106
        }

        .cofi-kpi-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px
        }

        .cofi-kpi-label {
            font-size: 12px;
            font-weight: 650;
            color: #63738e
        }

        .cofi-kpi-icon {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: #eaf1ff;
            color: #2258bb;
            font-size: 17px;
            font-weight: 800
        }

        .cofi-kpi-value {
            display: block;
            margin-top: 10px;
            font-size: 31px;
            font-weight: 850;
            letter-spacing: -.04em;
            color: #122c57;
            line-height: 1.1
        }

        .cofi-kpi-foot {
            margin-top: 9px;
            font-size: 11px;
            color: #8a98ad
        }

        .cofi-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 13px
        }

        .cofi-action {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 17px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            color: #17345f;
            text-decoration: none;
            transition: transform .2s, border-color .2s, box-shadow .2s
        }

        .cofi-action:hover {
            transform: translateY(-2px);
            border-color: #a7c3f7;
            box-shadow: 0 10px 25px #204c9212
        }

        .cofi-action-icon {
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            background: #edf3ff;
            color: #235bc1;
            border-radius: 11px;
            font-size: 19px;
            font-weight: 800
        }

        .cofi-action-label {
            flex: 1;
            font-size: 13px;
            font-weight: 800
        }

        .cofi-arrow {
            color: #3168d5;
            font-size: 19px
        }

        .cofi-note {
            margin-top: 20px;
            padding: 15px 18px;
            border: 1px solid #dce6f5;
            background: #f7faff;
            border-radius: 12px;
            color: #62748f;
            font-size: 12px;
            line-height: 1.5
        }

        /* Responsive basado en espacio disponible, incluido el menú lateral */
        .cofi-dashboard { min-width:0; }
        .cofi-hero-content { min-width:0; }
        .cofi-actions, .cofi-kpis { min-width:0; }
        .cofi-action { min-width:0; }
        .cofi-action-label { min-width:0; overflow-wrap:anywhere; }
        @media(max-width:1250px) {
            .cofi-kpis { grid-template-columns:repeat(2,minmax(0,1fr)); }
            .cofi-actions { grid-template-columns:repeat(2,minmax(0,1fr)); }
        }
        @media(max-width:1050px) {
            .cofi-kpis {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:750px) {
            .cofi-kpis {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .cofi-actions {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .cofi-date {
                display: none
            }

            .cofi-hero {
                padding: 25px
            }
        }

        @media(max-width:480px) {

            .cofi-kpis,
            .cofi-actions {
                grid-template-columns: 1fr
            }

            .cofi-hero {
                padding: 22px
            }

            .cofi-kpi {
                padding: 16px
            }
        }
    </style>
@endpush
@section('content')
    <div class="cofi-dashboard">
        <section class="cofi-hero">
            <div class="cofi-hero-content">
                <div class="cofi-eyebrow">COFIHO V2 · ADMINISTRADOR</div>
                <h2>Centro de operaciones</h2>
                <p>Supervisa el inventario, las operaciones y el rendimiento general de COFIHO.</p>
            </div>
            <div class="cofi-date">{{ now()->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</div>
        </section>
        <section class="cofi-section" aria-labelledby="dash-kpi-heading">
            <div class="cofi-section-head">
                <div>
                    <h3 id="dash-kpi-heading">Resumen de operaciones</h3>
                    <p>Indicadores generales registrados en el sistema</p>
                </div>
            </div>
            <div class="cofi-kpis">
                <article class="cofi-kpi">
                    <div class="cofi-kpi-top"><span class="cofi-kpi-label">Vehículos registrados</span><span
                            class="cofi-kpi-icon" aria-hidden="true">🚘</span></div><strong
                        class="cofi-kpi-value">{{ number_format($totalVehiculos) }}</strong>
                    <div class="cofi-kpi-foot">Total registrado</div>
                </article>
                <article class="cofi-kpi">
                    <div class="cofi-kpi-top"><span class="cofi-kpi-label">Disponibles</span><span class="cofi-kpi-icon"
                            aria-hidden="true">✓</span></div><strong
                        class="cofi-kpi-value">{{ number_format($disponibles) }}</strong>
                    <div class="cofi-kpi-foot">Total registrado</div>
                </article>
                <article class="cofi-kpi">
                    <div class="cofi-kpi-top"><span class="cofi-kpi-label">Ventas registradas</span><span
                            class="cofi-kpi-icon" aria-hidden="true">▣</span></div><strong
                        class="cofi-kpi-value">{{ number_format($ventas) }}</strong>
                    <div class="cofi-kpi-foot">Total registrado</div>
                </article>
                <article class="cofi-kpi">
                    <div class="cofi-kpi-top"><span class="cofi-kpi-label">Reservas pendientes</span><span
                            class="cofi-kpi-icon" aria-hidden="true">◷</span></div><strong
                        class="cofi-kpi-value">{{ number_format($reservasPendientes) }}</strong>
                    <div class="cofi-kpi-foot">Total registrado</div>
                </article>
                <article class="cofi-kpi">
                    <div class="cofi-kpi-top"><span class="cofi-kpi-label">Clientes</span><span class="cofi-kpi-icon"
                            aria-hidden="true">♙</span></div><strong
                        class="cofi-kpi-value">{{ number_format($clientes) }}</strong>
                    <div class="cofi-kpi-foot">Total registrado</div>
                </article>
                <article class="cofi-kpi">
                    <div class="cofi-kpi-top"><span class="cofi-kpi-label">Inspecciones</span><span class="cofi-kpi-icon"
                            aria-hidden="true">☷</span></div><strong
                        class="cofi-kpi-value">{{ number_format($inspecciones) }}</strong>
                    <div class="cofi-kpi-foot">Total registrado</div>
                </article>
                <article class="cofi-kpi">
                    <div class="cofi-kpi-top"><span class="cofi-kpi-label">Avalúos</span><span class="cofi-kpi-icon"
                            aria-hidden="true">▤</span></div><strong
                        class="cofi-kpi-value">{{ number_format($avaluos) }}</strong>
                    <div class="cofi-kpi-foot">Total registrado</div>
                </article>
                <article class="cofi-kpi">
                    <div class="cofi-kpi-top"><span class="cofi-kpi-label">Usuarios</span><span class="cofi-kpi-icon"
                            aria-hidden="true">♢</span></div><strong
                        class="cofi-kpi-value">{{ number_format($usuarios) }}</strong>
                    <div class="cofi-kpi-foot">Total registrado</div>
                </article>
            </div>
        </section>
        <section class="cofi-section" aria-labelledby="dash-actions-heading">
            <div class="cofi-section-head">
                <div>
                    <h3 id="dash-actions-heading">Accesos rápidos</h3>
                    <p>Ingresa directamente a los módulos de trabajo</p>
                </div>
            </div>
            <nav class="cofi-actions" aria-label="Accesos rápidos">
                <a class="cofi-action" href="{{ route('vehiculos.index') }}"><span class="cofi-action-icon"
                        aria-hidden="true">🚘</span><span class="cofi-action-label">Vehículos</span><span class="cofi-arrow"
                        aria-hidden="true">↗</span></a>
                <a class="cofi-action" href="{{ route('reservas.index') }}"><span class="cofi-action-icon"
                        aria-hidden="true">▦</span><span class="cofi-action-label">Reservas</span><span class="cofi-arrow"
                        aria-hidden="true">↗</span></a>
                <a class="cofi-action" href="{{ route('ventas.index') }}"><span class="cofi-action-icon"
                        aria-hidden="true">▧</span><span class="cofi-action-label">Ventas</span><span class="cofi-arrow"
                        aria-hidden="true">↗</span></a>
                <a class="cofi-action" href="{{ route('finanzas.index') }}"><span class="cofi-action-icon"
                        aria-hidden="true">▣</span><span class="cofi-action-label">Finanzas</span><span class="cofi-arrow"
                        aria-hidden="true">↗</span></a>
                <a class="cofi-action" href="{{ route('reportes.index') }}"><span class="cofi-action-icon"
                        aria-hidden="true">▥</span><span class="cofi-action-label">Reportes</span><span
                        class="cofi-arrow" aria-hidden="true">↗</span></a>
                <a class="cofi-action" href="{{ route('usuarios.index') }}"><span class="cofi-action-icon"
                        aria-hidden="true">♙</span><span class="cofi-action-label">Usuarios</span><span
                        class="cofi-arrow" aria-hidden="true">↗</span></a>
            </nav>
        </section>
        <div class="cofi-note">Los indicadores muestran los registros generales del sistema, no únicamente los movimientos
            del día.</div>
    </div>
@endsection
