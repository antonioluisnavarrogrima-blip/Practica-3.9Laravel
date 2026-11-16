<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Proyectos</title>
    <style>
        :root {
            --bg: #f5f7fa;
            --surface: #ffffff;
            --text: #0f172a;
            --muted: #475569;
            --border: #e2e8f0;
            --primary: #1d4ed8;
            --primary-hover: #1e40af;
            --primary-soft: #eff6ff;
            --success-bg: #ecfdf5;
            --success-text: #065f46;
            --warn-bg: #fffbeb;
            --warn-text: #92400e;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid var(--primary);
            outline-offset: 2px;
        }

        .container {
            width: 100%;
            max-width: 1080px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Cabecera */
        .site-header {
            background-color: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-height: 72px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text);
            font-weight: 700;
            font-size: 1.125rem;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background-color: var(--primary);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Botones */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-outline {
            background-color: var(--surface);
            color: var(--text);
            border-color: var(--border);
        }

        .btn-outline:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
        }

        .btn-link {
            color: var(--muted);
            padding: 10px 14px;
        }

        .btn-link:hover {
            color: var(--text);
        }

        /* Contenido principal */
        main {
            flex: 1;
        }

        /* Hero */
        .hero {
            padding: 56px 0 40px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 40px;
            align-items: center;
        }

        .eyebrow {
            display: inline-block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            background-color: var(--primary-soft);
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 16px;
        }

        .hero h1 {
            font-size: clamp(1.9rem, 3.5vw, 2.65rem);
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
        }

        .hero-lead {
            font-size: 1.075rem;
            color: var(--muted);
            margin-bottom: 28px;
            max-width: 52ch;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Tarjeta de vista previa del programa */
        .preview-card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        }

        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            margin-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .preview-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }

        .preview-meta {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .project-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background-color 0.15s ease;
        }

        .project-row:last-child {
            margin-bottom: 0;
        }

        .project-row:hover {
            background-color: #f8fafc;
        }

        .project-name {
            font-weight: 600;
            font-size: 0.95rem;
            display: block;
        }

        .project-detail {
            font-size: 0.82rem;
            color: var(--muted);
        }

        .badge {
            font-size: 0.78rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .badge-active {
            background-color: var(--warn-bg);
            color: var(--warn-text);
        }

        .badge-done {
            background-color: var(--success-bg);
            color: var(--success-text);
        }

        /* Sección de características */
        .features {
            padding: 24px 0 64px;
        }

        .features h2 {
            font-size: 1.35rem;
            margin-bottom: 20px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .feature-item {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            transition: border-color 0.15s ease;
        }

        .feature-item:hover {
            border-color: #cbd5e1;
        }

        .feature-item h3 {
            font-size: 1.02rem;
            margin-bottom: 6px;
        }

        .feature-item p {
            font-size: 0.93rem;
            color: var(--muted);
        }

        /* Pie de página */
        .site-footer {
            background-color: var(--surface);
            border-top: 1px solid var(--border);
            padding: 20px 0;
            font-size: 0.875rem;
            color: var(--muted);
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Responsive */
        @media (max-width: 820px) {
            .hero {
                padding: 36px 0 28px;
            }

            .hero-grid,
            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('welcome') }}" class="brand">
                <span>Gestor de Proyectos</span>
            </a>

            <nav class="header-nav" aria-label="Acceso de usuario">
                @auth
                    <a href="{{ route('projects.index') }}" class="btn btn-primary">Ir a mis proyectos</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-link">Iniciar sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-grid">
                <div>
                    <span class="eyebrow">Control de proyectos y equipos</span>
                    <h1>Organiza tus proyectos, plazos y participantes en un solo lugar.</h1>
                    <p class="hero-lead">
                        Crea proyectos con fecha límite y estado, asigna roles a cada participante y registra las horas aportadas sin complicaciones.
                    </p>

                    <div class="hero-actions">
                        @auth
                            <a href="{{ route('projects.index') }}" class="btn btn-primary">Ver proyectos</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Entrar a mi cuenta</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline">Crear cuenta nueva</a>
                            @endif
                        @endauth
                    </div>
                </div>

               
        </section>

        <section class="features" aria-labelledby="features-heading">
            <div class="container">
                <h2 id="features-heading">Qué puedes hacer en la aplicación</h2>

                <div class="features-grid">
                    <article class="feature-item">
                        <h3>Seguimiento de plazos y estados</h3>
                        <p>Registra título, descripción, fecha límite y estado de cada proyecto para saber qué requiere atención primero.</p>
                    </article>

                    <article class="feature-item">
                        <h3>Colaboración por roles</h3>
                        <p>Asocia participantes a cada proyecto indicando su rol y las horas de contribución acumuladas.</p>
                    </article>

                    <article class="feature-item">
                        <h3>Gestión completa (CRUD)</h3>
                        <p>Crea, consulta, edita o elimina proyectos desde una tabla clara y directa.</p>
                    </article>

                    <article class="feature-item">
                        <h3>Acceso privado por cuenta</h3>
                        <p>Tus proyectos y tu perfil están protegidos tras tu sesión de usuario.</p>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <span>Gestor de Proyectos</span>
            <span>Inicia sesión para gestionar tus proyectos activos.</span>
        </div>
    </footer>
</body>
</html>
