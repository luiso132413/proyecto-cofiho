<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Catálogo de vehículos | COFIHO V2</title>
    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, sans-serif;
            background: #f4f7fb;
            color: #152440
        }

        a {
            text-decoration: none;
            color: inherit
        }

        .nav {
            background: #0e1d36;
            color: white;
            padding: 20px max(24px, calc((100vw - 1180px)/2));
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px
        }

        .logo {
            font-weight: 900;
            letter-spacing: .08em
        }

        .nav small {
            color: #9db5d7
        }

        .nav a:last-child {
            color: #b9d5ff
        }

        .hero {
            background: linear-gradient(115deg, #10213e, #1c488e);
            color: white;
            padding: 75px 24px;
            text-align: center
        }

        .hero small {
            letter-spacing: .18em;
            color: #93c5fd;
            font-weight: 800
        }

        .hero h1 {
            font-size: clamp(32px, 5vw, 56px);
            margin: 14px auto;
            max-width: 780px
        }

        .hero p {
            color: #d4e2f7
        }

        .wrap {
            max-width: 1180px;
            margin: auto;
            padding: 36px 20px
        }

        .filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            background: white;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            margin-bottom: 26px
        }

        .filters input,
        .filters select {
            flex: 1;
            min-width: 180px;
            border: 1px solid #d5dfec;
            border-radius: 9px;
            padding: 12px;
            font: inherit
        }

        .btn {
            background: #2563eb;
            border: 0;
            color: white;
            padding: 12px 20px;
            border-radius: 9px;
            font-weight: 750;
            cursor: pointer
        }

        .muted {
            color: #64748b
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 22px
        }

        .car {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 24px #1224450b
        }

        .visual {
            height: 190px;
            background: linear-gradient(140deg, #e7effb, #cbdcf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5475a7;
            position: relative
        }

        .visual svg {
            width: 100px;
            height: 100px
        }


        .visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .tag { z-index: 1; }
        .tag {
            position: absolute;
            top: 16px;
            left: 16px;
            background: white;
            border-radius: 30px;
            padding: 7px 12px;
            font-size: 12px;
            font-weight: 800
        }

        .info {
            padding: 22px
        }

        .info h2 {
            margin: 0 0 8px;
            font-size: 22px
        }

        .meta {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 18px
        }

        .price {
            font-size: 25px;
            font-weight: 850;
            color: #163b77;
            margin-bottom: 18px
        }

        .info .btn {
            display: block;
            text-align: center
        }

        .empty {
            text-align: center;
            padding: 70px 15px;
            background: white;
            border-radius: 18px
        }

        .footer {
            margin-top: 60px;
            background: #0e1d36;
            color: #b5c6df;
            padding: 30px;
            text-align: center;
            font-size: 13px
        }

        @media(max-width:600px) {
            .hero {
                padding: 54px 18px
            }

            .nav {
                padding: 18px
            }

            .filters {
                flex-direction: column
            }

            .acceso-admin {
    color: #94a3b8 !important;
    font-size: 12px;
    font-weight: 500;
    padding: 8px 10px;
    border: 1px solid #334155;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.acceso-admin:hover {
    color: #ffffff !important;
    border-color: #64748b;
    background: rgba(255, 255, 255, 0.06);
}

        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ secure_asset('css/halloween.css') }}">
</head>

<body>
    <header class="nav"><a href="{{ route('catalogo.index') }}" class="logo">COFIHO <span
                style="color:#60a5fa">V2</span><br><small>Catálogo automotriz</small></a><a href="#vehiculos">Explorar
            vehículos</a>
            <a href="{{ route('login') }}" class="acceso-admin">
    &#128274; Acceso administrativo
</a>
    </header>
    <section class="hero"><small>CONOCE NUESTRO INVENTARIO</small>
        <h1>Encuentra el vehículo ideal para ti</h1>
        <p>Explora vehículos nuevos y usados disponibles en nuestras instalaciones.</p>
        
    </section>
    <main class="wrap" id="vehiculos">
        <h2>Vehículos disponibles</h2>
        <p class="muted">Información consultada directamente del inventario COFIHO.</p>
        <form class="filters" method="GET" action="{{ route('catalogo.index') }}"><input name="buscar"
                value="{{ request('buscar') }}" placeholder="Buscar marca, modelo o año"
                aria-label="Buscar vehículos"><select name="condicion" aria-label="Condición">
                <option value="">Nuevos y usados</option>
                <option value="NUEVO" @selected(request('condicion') === 'NUEVO')>Nuevos</option>
                <option value="USADO" @selected(request('condicion') === 'USADO')>Usados</option>
            </select><button class="btn">Buscar</button><a href="{{ route('catalogo.index') }}"
                style="align-self:center">Limpiar</a></form>
        <p class="muted">{{ $vehiculos->count() }} vehículo(s) encontrado(s)</p>
        <div class="grid">
            @forelse($vehiculos as $v)
                <article class="car">
                    <div class="visual">
                        <span class="tag">{{ $v->condicion }}</span>
                        @php
                            $imagen = asset('imagenes/catalogo/prueba1.jpg');
                            foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
                                $rutaStorage = 'vehiculos/' . $v->id_vehiculo . '.' . $extension;
                                $rutaAnterior = 'imagenes/catalogo/' . $v->id_vehiculo . '.' . $extension;
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
                        <img
                            src="{{ $imagen }}"
                            alt="{{ $v->modelo?->marca?->nombre ?? 'Marca' }} {{ $v->modelo?->nombre ?? 'Modelo' }}"
                            loading="lazy"
                        >
                    </div>
                    <div class="info">
                        <h2>{{ $v->modelo?->marca?->nombre ?? 'Marca' }} {{ $v->modelo?->nombre ?? 'Modelo' }}</h2>
                        <div class="meta">{{ $v->anio }} · {{ $v->tipoVehiculo?->nombre ?? 'Vehículo' }} ·
                            {{ $v->color }}</div>
                        <div class="price">Q {{ number_format((float) $v->precio_lista, 2) }}</div><a class="btn"
                            href="{{ route('catalogo.show', $v->id_vehiculo) }}">Ver detalles</a>
                    </div>
                </article>
            @empty<div class="empty">
                    <h3>No encontramos vehículos</h3>
                    <p class="muted">Prueba con otros filtros de búsqueda.</p>
                </div>
            @endforelse
        </div>
    </main>
    <footer class="footer">COFIHO V2 · Catálogo de demostración conectado al inventario · Precios sujetos a confirmación
    </footer>
    <script src="{{ secure_asset('js/halloween.js') }}" defer></script>
</body>

</html>
