<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'App Precios Unitarios')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body{
            margin:0;
            background:#f3f4f6;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .app-layout{
            min-height:100vh;
            display:flex;
            gap:20px;
            padding:20px;
            align-items:flex-start;
        }

        .sidebar {
            width: 260px;
            min-width: 260px;
            background-color: #1c1c1c;
            color: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 10;
            box-shadow: 4px 0 15px rgba(0,0,0,0.2);
            min-height: calc(100vh - 40px);
            position: sticky;
            top: 20px;
        }

        .sidebar .text-center {
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar img.logo-sidebar {
            width: 100px;
            height: 100px;
            object-fit: contain;
            background-color: #ffffff;
            border-radius: 50%;
            padding: 22px 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        }

        .sidebar p.small {
            color: #ccc;
        }

        .sidebar .nav-link {
            color: #e0e0e0;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: transparent;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .sidebar .nav-link i {
            color: #888;
            width: 20px;
            text-align: center;
            transition: color 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #2c2c2c;
            color: #fff;
        }

        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i {
            color: #fff;
        }

        .sidebar .footer-text {
            font-size: 0.7rem;
            color: #666;
            margin-top: 20px;
            border-top: 1px solid #333;
            padding-top: 15px;
        }

        .main-content{
            flex:1;
            min-width:0;
        }

        .content-card{
            background:#fff;
            border-radius:12px;
            box-shadow:0 8px 22px rgba(0,0,0,.08);
            padding:30px;
            min-height:calc(100vh - 40px);
        }

        @media (max-width: 991.98px){
            .app-layout{
                flex-direction:column;
            }

            .sidebar{
                width:100%;
                min-width:100%;
                min-height:auto;
                position:relative;
                top:0;
            }

            .content-card{
                min-height:auto;
            }
        }
    </style>
</head>
<body>

<div class="app-layout">

    <aside class="sidebar">
        <div>
            <div class="text-center">
                <img src="{{ asset('images/logo_akiraka.png') }}" alt="Logo Akiraka" class="logo-sidebar">
                <p class="small mb-4 text-uppercase" style="letter-spacing: 2px;">Akiraka Estudio</p>
            </div>

            <ul class="nav flex-column" style="list-style: none; padding: 0;">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}" href="{{ route('inicio') }}">
                        <div><i class="fas fa-home me-3"></i> Inicio</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('proyectos*') ? 'active' : '' }}" href="{{ route('proyectos') }}">
                        <div><i class="fas fa-folder me-3"></i> Proyectos</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('conceptos*') ? 'active' : '' }}" href="{{ route('conceptos') }}">
                        <div><i class="fas fa-diagram-project me-3"></i> Conceptos</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('generadores*') ? 'active' : '' }}" href="{{ route('generadores') }}">
                        <div><i class="fas fa-calculator me-3"></i> Generadores</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('materiales*') ? 'active' : '' }}" href="{{ route('materiales') }}">
                        <div><i class="fas fa-boxes-stacked me-3"></i> Materiales</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('mano_obra*') ? 'active' : '' }}" href="{{ route('mano_obra') }}">
                        <div><i class="fas fa-users-gear me-3"></i> Mano de Obra</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('maquinaria_equipo*') ? 'active' : '' }}" href="{{ route('maquinaria_equipo') }}">
                        <div><i class="fas fa-truck-monster me-3"></i> Maquinaria y Equipo</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('indirectos*') ? 'active' : '' }}" href="{{ route('indirectos') }}">
                        <div><i class="fas fa-percent me-3"></i> Indirectos</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pu*') ? 'active' : '' }}" href="{{ route('pu') }}">
                        <div><i class="fas fa-file-invoice-dollar me-3"></i> P.U</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('presupuesto*') ? 'active' : '' }}" href="{{ route('presupuesto') }}">
                        <div><i class="fas fa-wallet me-3"></i> Presupuesto</div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reportes*') ? 'active' : '' }}" href="{{ route('reportes') }}">
                        <div><i class="fas fa-chart-bar me-3"></i> Reportes</div>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <a href="{{ route('inicio') }}" class="nav-link">
                <div><i class="fas fa-sign-out-alt me-3"></i> Salir</div>
            </a>

            <div class="text-center footer-text">
                <p class="mb-1 text-uppercase" style="letter-spacing: 1px;">Akiraka Estudio</p>
                <p class="mb-0">© {{ date('Y') }} Derechos reservados</p>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="content-card">
            @yield('content')
        </div>
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
