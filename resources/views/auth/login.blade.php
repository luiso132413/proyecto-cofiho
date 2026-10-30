<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b1730">
    <title>Iniciar sesión | COFIHO V2</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--navy:#0b1730;--blue:#2563eb;--muted:#64748b}
        *{box-sizing:border-box}body{margin:0;font-family:'DM Sans',Arial,sans-serif;background:#f4f7fc;color:#16243a}
        .shell{min-height:100vh;display:grid;grid-template-columns:minmax(0,1.04fr) minmax(430px,.96fr)}
        .hero{position:relative;isolation:isolate;overflow:hidden;background:radial-gradient(circle at 20% 15%,#274d8a 0,transparent 42%),linear-gradient(145deg,#111e39,#071327 78%);color:white;padding:clamp(32px,6vw,86px);display:flex;flex-direction:column;justify-content:space-between}
        .hero:before{content:'';position:absolute;z-index:-1;width:640px;height:640px;border:1px solid #ffffff13;border-radius:50%;left:18%;top:18%;box-shadow:0 0 0 85px #ffffff06,0 0 0 170px #ffffff04}
        .brand{display:flex;align-items:center;gap:13px;font-family:Manrope,sans-serif;font-weight:800;font-size:21px;letter-spacing:.035em}
        .brand-mark{width:43px;height:43px;display:grid;place-items:center;border-radius:13px;background:linear-gradient(135deg,#60a5fa,#2563eb);box-shadow:0 10px 24px #0003;color:white}
        .hero-copy{max-width:570px;margin:90px 0}.eyebrow{display:inline-flex;align-items:center;gap:9px;border:1px solid #ffffff32;border-radius:999px;padding:9px 14px;color:#d7e7ff;font-size:12px;font-weight:700;letter-spacing:.09em;text-transform:uppercase}
        .dot{width:7px;height:7px;border-radius:50%;background:#60a5fa;box-shadow:0 0 12px #60a5fa}
        h1,h2,p{margin-top:0}.hero h1{font-family:Manrope,sans-serif;font-size:clamp(38px,4.4vw,65px);line-height:1.12;letter-spacing:-.055em;margin:25px 0}.hero h1 span{color:#80b4ff}.hero p{font-size:16px;line-height:1.85;color:#b8c9e2;max-width:450px}
        .hero-footer{font-size:12px;color:#8ca4c5}.hero-line{height:1px;background:#ffffff1e;margin-bottom:20px}
        .right{display:flex;align-items:center;justify-content:center;padding:50px 28px}.login-card{width:min(100%,440px)}.top-label{color:#2563eb;font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;margin-bottom:15px}
        h2{font-family:Manrope,sans-serif;font-size:clamp(29px,3vw,37px);letter-spacing:-.045em;line-height:1.2;margin-bottom:13px}.intro{color:var(--muted);font-size:15px;line-height:1.65;margin-bottom:35px}
        label{display:block;font-weight:700;font-size:13px;margin-bottom:10px}.field{margin-bottom:22px}.input-wrap{position:relative}.input-wrap input{display:block;width:100%;height:54px;border:1px solid #dce3ee;border-radius:12px;background:white;padding:0 47px 0 46px;font:500 14px 'DM Sans',sans-serif;color:#14213b;outline:none;transition:border .2s,box-shadow .2s}.input-wrap input:focus{border-color:#3b82f6;box-shadow:0 0 0 4px #3b82f61c}.input-wrap input::placeholder{color:#9ba9bc}.field-icon{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#8292a9;pointer-events:none}.field-icon svg,.eye svg{width:19px;height:19px;display:block}
        .eye{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:transparent;border:0;color:#8292a9;padding:10px;cursor:pointer;border-radius:8px}.eye:hover{background:#f1f5f9;color:#2563eb}
        .submit{width:100%;height:55px;border:0;border-radius:12px;background:linear-gradient(105deg,#2563eb,#1d4ed8);color:white;font:700 15px 'DM Sans',sans-serif;cursor:pointer;box-shadow:0 12px 25px #2563eb32;transition:transform .2s,box-shadow .2s}.submit:hover{transform:translateY(-2px);box-shadow:0 16px 28px #2563eb48}.submit:focus-visible,.eye:focus-visible{outline:3px solid #93c5fd;outline-offset:3px}.submit:disabled{opacity:.75;cursor:wait}
        .error{border:1px solid #fecaca;background:#fef2f2;color:#b91c1c;border-radius:12px;padding:13px 15px;font-size:13px;line-height:1.5;margin-bottom:22px}.security{display:flex;align-items:center;justify-content:center;gap:8px;color:#8190a5;font-size:12px;margin-top:25px}.security svg{width:15px;height:15px}.mobile-brand{display:none}
        @media(max-width:900px){.shell{grid-template-columns:1fr}.hero{display:none}.right{min-height:100vh;padding:36px 22px}.mobile-brand{display:flex;align-items:center;gap:11px;font-family:Manrope,sans-serif;font-weight:800;color:#122447;margin-bottom:65px}.mobile-brand .brand-mark{width:38px;height:38px}.login-card{max-width:430px}}
        @media(prefers-reduced-motion:reduce){*{transition:none!important}}
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/halloween.css">
</head>
<body>
<main class="shell">
    <section class="hero" aria-label="Presentación de COFIHO">
        <div class="brand"><span class="brand-mark" aria-hidden="true">C</span><span>COFIHO <span style="color:#93c5fd">V2</span></span></div>
        <div class="hero-copy">
            <span class="eyebrow"><span class="dot"></span> Plataforma de gestión automotriz</span>
            <h1>Tu operación.<br><span>En un solo lugar.</span></h1>
            <p>Gestiona vehículos, inventario, clientes, reservas y ventas desde una plataforma diseñada para trabajar con claridad y eficiencia.</p>
        </div>
        <div class="hero-footer"><div class="hero-line"></div>COFIHO V2 · Sistema de gestión de concesionaria</div>
    </section>
    <section class="right" aria-label="Inicio de sesión">
        <div class="login-card">
            <div class="mobile-brand"><span class="brand-mark">C</span> COFIHO V2</div>
            <div class="top-label">Bienvenido de nuevo</div>
            <h2>Inicia sesión en<br>tu cuenta</h2>
            <p class="intro">Ingresa tus credenciales para acceder al sistema de gestión COFIHO V2.</p>
            @if ($errors->any())
                <div class="error" role="alert">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
                @csrf
                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <div class="input-wrap">
                        <span class="field-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nombre@empresa.com" autocomplete="username" required autofocus>
                    </div>
                </div>
                <div class="field">
                    <label for="password">Contraseña</label>
                    <div class="input-wrap">
                        <span class="field-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg></span>
                        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>
                        <button class="eye" type="button" id="togglePassword" aria-label="Mostrar contraseña" aria-pressed="false"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg></button>
                    </div>
                </div>
                <button class="submit" type="submit" id="submitButton">Ingresar al sistema →</button>
            </form>
            <div class="security"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg> Acceso exclusivo para personal autorizado</div>
        </div>
    </section>
</main>
<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    toggle.addEventListener('click', () => {
        const visible = password.type === 'password';
        password.type = visible ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', String(visible));
        toggle.setAttribute('aria-label', visible ? 'Ocultar contraseña' : 'Mostrar contraseña');
    });
    document.getElementById('loginForm').addEventListener('submit', () => {
        const button = document.getElementById('submitButton');
        button.disabled = true;
        button.textContent = 'Ingresando...';
    });
</script>

    <script src="/js/halloween.js" defer></script>
</body>
</html>
