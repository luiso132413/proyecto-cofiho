<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Detalle del vehículo | COFIHO V2</title>
    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: #f4f7fb;
            color: #14233e;
            font-family: system-ui, -apple-system, Segoe UI, sans-serif
        }

        header {
            background: #0e1d36;
            color: white;
            padding: 22px max(24px, calc((100vw - 1050px)/2));
            font-weight: 800
        }

        header a {
            color: white;
            text-decoration: none
        }

        main {
            max-width: 1050px;
            margin: 40px auto;
            padding: 0 20px
        }

        .back {
            color: #2563eb;
            text-decoration: none
        }

        .panel {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            align-items: start;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            margin-top: 22px
        }

        .visual {
            background: linear-gradient(145deg, #dce8f9, #adc9ef);
            height: 460px;
            min-height: 0;
            display: grid;
            place-items: center;
            color: #5379af
        }

        .visual img { width: 100%; height: 100%; min-height: 0; object-fit: contain; display: block; }
        .public-form[hidden] { display: none !important; }
        .details { min-width: 0; }
        .visual svg {
            width: 170px;
            height: 170px
        }

        .details {
            padding: 38px
        }

        .eyebrow {
            color: #2563eb;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .15em
        }

        .details h1 {
            font-size: 32px;
            margin: 12px 0
        }

        .price {
            font-size: 32px;
            font-weight: 850;
            color: #173f80;
            margin: 22px 0
        }

        dl {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px
        }

        dt {
            color: #64748b;
            font-size: 12px
        }

        dd {
            margin: 5px 0;
            font-weight: 750
        }

        .notice {
            margin-top: 22px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.6
        }

        .reserve-panel { margin-top: 24px; padding: 24px; background: #f8fafc; border: 1px solid #dbe5f2; border-radius: 14px; }
        .reserve-panel h2 { margin: 0 0 8px; font-size: 20px; }
        .reserve-panel p { color: #64748b; line-height: 1.6; font-size: 14px; }
        .reserve-toggle { background: #2563eb; border: 0; border-radius: 9px; color: white; padding: 12px 18px; font-weight: 700; cursor: pointer; }
        .reserve-options { margin-top: 16px; padding-top: 16px; border-top: 1px solid #dbe5f2; }
        .reserve-options[hidden] { display: none; }
        .reserve-choice { display: block; width: 100%; text-align: left; background: white; border: 1px solid #dbe5f2; border-radius: 9px; padding: 14px; margin: 10px 0; font: inherit; color: #14233e; cursor: pointer; }
        .reserve-choice:hover { border-color: #2563eb; }
        .reserve-info { font-size: 13px; color: #64748b; }
        .public-form { margin-top: 14px; display: grid; gap: 12px; }
        .public-form label { display: grid; gap: 5px; font-size: 13px; font-weight: 650; }
        .public-form input { width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit; }
        .public-form button { background: #2563eb; color: white; padding: 12px; border: 0; border-radius: 9px; font-weight: 700; cursor: pointer; }
        .public-alert { padding: 12px; border-radius: 8px; background: #eff6ff; color: #1e40af; margin: 12px 0; font-size: 13px; }
        .public-errors { background: #fef2f2; color: #991b1b; }
        @media(max-width:750px) {
            .panel {
                grid-template-columns: 1fr
            }

            .visual {
                height: 300px;
                min-height: 0
            }

            .details {
                padding: 25px
            }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/halloween.css') }}">
</head>

<body>
    <header><a href="{{ route('catalogo.index') }}">COFIHO V2 · CATÁLOGO</a></header>
    <main><a class="back" href="{{ route('catalogo.index') }}">← Volver al catálogo</a>
        <div class="panel">
            <div class="visual">
                @php
                    $imagen = asset('imagenes/catalogo/prueba1.jpg');
                    foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
                        $rutaStorage = 'vehiculos/' . $vehiculo->id_vehiculo . '.' . $extension;
                        $rutaAnterior = 'imagenes/catalogo/' . $vehiculo->id_vehiculo . '.' . $extension;
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($rutaStorage)) {
                            $imagen = asset('storage/' . $rutaStorage);
                            break;
                        }
                        if (file_exists(public_path($rutaAnterior))) {
                            $imagen = asset($rutaAnterior);
                            break;
                        }
                    }
                @endphp
                <img src="{{ $imagen }}" alt="{{ $vehiculo->modelo?->marca?->nombre ?? 'Marca' }} {{ $vehiculo->modelo?->nombre ?? 'Modelo' }}">
            </div>
            <section class="details">
                <div class="eyebrow">{{ $vehiculo->condicion }} · DISPONIBLE</div>
                <h1>{{ $vehiculo->modelo?->marca?->nombre ?? 'Marca' }} {{ $vehiculo->modelo?->nombre ?? 'Modelo' }}
                </h1>
                <div class="price">Q {{ number_format((float) $vehiculo->precio_lista, 2) }}</div>
                <dl>
                    <div>
                        <dt>Año</dt>
                        <dd>{{ $vehiculo->anio }}</dd>
                    </div>
                    <div>
                        <dt>Color</dt>
                        <dd>{{ $vehiculo->color }}</dd>
                    </div>
                    <div>
                        <dt>Tipo</dt>
                        <dd>{{ $vehiculo->tipoVehiculo?->nombre ?? 'No especificado' }}</dd>
                    </div>
                    <div>
                        <dt>Condición</dt>
                        <dd>{{ $vehiculo->condicion }}</dd>
                    </div>
                    <div>
                        <dt>Ubicación</dt>
                        <dd>{{ $inventarioActual?->predio?->nombre ?? 'Consultar' }}</dd>
                    </div>
                    <div>
                        <dt>Estado</dt>
                        <dd>Disponible</dd>
                    </div>
                </dl>
                <p class="notice">Disponibilidad y precio sujetos a confirmación. No se reciben pagos en el catálogo.</p>
                <div class="reserve-panel">
                    <h2>¿Quieres reservar este vehículo?</h2>
                    <p>Podrás solicitar una reserva sin pago, con vigencia de 15 minutos una vez confirmada.</p>
                    @unless($clientePublico)
                    <button class="reserve-toggle" type="button" id="abrir-reserva" aria-expanded="false" aria-controls="opciones-reserva">Quiero reservar</button>
                    @endunless
                    @if(session('registro_publico_ok'))
                        <p class="public-alert" role="status">{{ session('registro_publico_ok') }}</p>
                    @endif
                    @if($errors->any())
                        <div class="public-alert public-errors" role="alert">
                            <strong>Revisa los datos:</strong>
                            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    @if($clientePublico)
                        <div class="public-alert" role="status" style="margin-top:16px">
                            <strong>Cliente identificado (demostración)</strong>
                            <p style="margin:8px 0">Cliente: {{ $clientePublico->nombre }} {{ $clientePublico->apellido }}</p>
                            <p style="margin:8px 0">Vehículo: {{ $vehiculo->modelo?->marca?->nombre }} {{ $vehiculo->modelo?->nombre }} {{ $vehiculo->anio }}</p>
                            <p style="margin:8px 0">Enganche: Q 0.00 · Vigencia prevista: 15 minutos desde la confirmación.</p>
                            <p style="margin:8px 0">Al confirmar, se solicitará la reserva a Oracle. No se requiere pago.</p>
                            <form method="POST" action="{{ route('catalogo.reserva.confirmar', $vehiculo->id_vehiculo) }}" style="margin-top:14px">@csrf
                                <button class="reserve-toggle" type="submit">Confirmar reserva sin pago</button>
                            </form>
                        </div>
                    @else
                    <div class="reserve-options" id="opciones-reserva" {{ $errors->any() ? '' : 'hidden' }}>
                        <strong>¿Ya eres cliente de COFIHO?</strong>
                        <button class="reserve-choice" type="button" data-tipo="nuevo">Soy cliente nuevo</button>
                        <button class="reserve-choice" type="button" data-tipo="existente">Ya estoy registrado</button>
                        <p class="reserve-info" id="mensaje-reserva" role="status">Selecciona una opción para continuar.</p>
                        <form id="form-registro-publico" class="public-form" method="POST" action="{{ route('catalogo.reserva.cliente', $vehiculo->id_vehiculo) }}" hidden>
                            @csrf
                            <label>Nombre *<input name="nombre" maxlength="100" required value="{{ old('nombre') }}" autocomplete="given-name"></label>
                            <label>Apellido *<input name="apellido" maxlength="100" required value="{{ old('apellido') }}" autocomplete="family-name"></label>
                            <label>DPI (13 dígitos) *<input name="dpi" inputmode="numeric" pattern="[0-9]{13}" maxlength="13" required autocomplete="off"></label>
                            <label>NIT (opcional)<input name="nit" maxlength="20" value="{{ old('nit') }}"></label>
                            <label>Correo electrónico *<input type="email" name="email" maxlength="150" required value="{{ old('email') }}" autocomplete="email"></label>
                            <label>Teléfono *<input name="telefono" maxlength="20" required value="{{ old('telefono') }}" autocomplete="tel"></label>
                            <label>Dirección (opcional)<input name="direccion" maxlength="255" value="{{ old('direccion') }}" autocomplete="street-address"></label>
                            <div style="position:absolute;left:-9999px" aria-hidden="true"><label>Sitio web<input name="sitio_web" tabindex="-1" autocomplete="off"></label></div>
                            <p class="reserve-info">Al registrarte podrás confirmar una reserva de demostración. No se realiza ningún cobro.</p>
                            <button type="submit">Registrar mis datos</button>
                        </form>
                        <form id="form-cliente-existente" class="public-form" method="POST" action="{{ route('catalogo.reserva.identificar', $vehiculo->id_vehiculo) }}" hidden>
                            @csrf
                            <label>DPI registrado *<input name="dpi" inputmode="numeric" pattern="[0-9]{13}" maxlength="13" required autocomplete="off"></label>
                            <label>Correo registrado *<input type="email" name="email" maxlength="150" required autocomplete="email"></label>
                            <p class="reserve-info">Acceso solo para demostración universitaria; estos datos no verifican realmente la identidad.</p>
                            <button type="submit">Continuar con mi registro</button>
                        </form>
                    </div>
                    @endif
                </div>
            </section>
        </div>
    </main>
    <script>
        const abrirReserva = document.getElementById('abrir-reserva');
        const opcionesReserva = document.getElementById('opciones-reserva');
        if (opcionesReserva && abrirReserva) abrirReserva.addEventListener('click', () => {
            const abrir = opcionesReserva.hidden;
            opcionesReserva.hidden = !abrir;
            abrirReserva.setAttribute('aria-expanded', String(abrir));
        });
        document.querySelectorAll('[data-tipo]').forEach(boton => {
            boton.addEventListener('click', () => {
                const form = document.getElementById('form-registro-publico');
                if (form) form.hidden = boton.dataset.tipo !== 'nuevo';
                const existente = document.getElementById('form-cliente-existente');
                if (existente) existente.hidden = boton.dataset.tipo !== 'existente';
                document.getElementById('mensaje-reserva').textContent = boton.dataset.tipo === 'nuevo'
                    ? 'Completa tus datos para registrarte.'
                    : 'Introduce el DPI y correo registrados (solo demostración).';
            });
        });
        @if($errors->any())
            const formNuevo = document.getElementById('form-registro-publico');
            const formExistente = document.getElementById('form-cliente-existente');
            if (formNuevo) formNuevo.hidden = false;
            if (formExistente) formExistente.hidden = true;
            if (abrirReserva) abrirReserva.setAttribute('aria-expanded', 'true');
        @endif
    </script>

    <script src="{{ asset('js/halloween.js') }}" defer></script>
</body>

</html>
