<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'App Precios Unitarios')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body{
            margin:0;
            background:#f3f4f6;
            font-family:"Helvetica Neue", Arial, sans-serif;
        }

        .app-layout{
            min-height:100vh;
            display:flex;
            gap:20px;
            padding:20px;
            align-items:flex-start;
        }

        .sidebar{
            width:260px;
            min-width:260px;
            background-color:#1c1c1c;
            color:#fff;
            padding:30px 22px;
            border-radius:16px;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            box-shadow:4px 0 15px rgba(0,0,0,0.2);
            min-height:calc(100vh - 40px);
            position:sticky;
            top:20px;
        }

        .sidebar .brand-box{
            margin-bottom:2rem;
            display:flex;
            flex-direction:column;
            align-items:center;
            text-align:center;
        }

        .sidebar img.logo-sidebar{
            width:100px;
            height:100px;
            object-fit:contain;
            background-color:#ffffff;
            border-radius:50%;
            padding:22px 15px;
            margin-bottom:15px;
            box-shadow:0 4px 15px rgba(0,0,0,0.25);
        }

        .sidebar .brand-text{
            color:#ccc;
            font-size:.82rem;
            letter-spacing:2px;
            text-transform:uppercase;
            margin:0;
        }

        .sidebar-menu{
            list-style:none;
            padding:0;
            margin:0;
        }

        .sidebar-menu .nav-item{
            margin-bottom:8px;
        }

        .sidebar .nav-link{
            color:#e0e0e0;
            display:flex;
            align-items:center;
            gap:14px;
            background:transparent;
            border-radius:12px;
            padding:14px 16px;
            transition:all .25s ease;
            font-weight:500;
            text-decoration:none;
            font-size:0.95rem;
        }

        .sidebar .nav-link i{
            color:#9ca3af;
            width:20px;
            text-align:center;
            transition:color .25s ease;
            font-size:1rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active{
            background-color:#2c2c2c;
            color:#fff;
        }

        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i{
            color:#fff;
        }

        .sidebar .footer-text{
            font-size:.7rem;
            color:#666;
            margin-top:22px;
            border-top:1px solid #333;
            padding-top:18px;
            text-align:center;
        }

        .sidebar .footer-text p{
            margin-bottom:6px;
        }

        .main-content{
            flex:1;
            min-width:0;
        }

        .content-card{
            background:#fff;
            border-radius:16px;
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
            <div class="brand-box">
                <img src="{{ asset('images/logo_akiraka.png') }}" alt="Logo Akiraka" class="logo-sidebar">
                <p class="brand-text">Akiraka Estudio</p>
            </div>

            <ul class="sidebar-menu">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}"
                       href="{{ Route::has('inicio') ? route('inicio') : '#' }}">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('proyectos*') ? 'active' : '' }}"
                       href="{{ Route::has('proyectos') ? route('proyectos') : '#' }}">
                        <i class="fas fa-folder"></i>
                        <span>Proyectos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('conceptos*') ? 'active' : '' }}"
                       href="{{ Route::has('conceptos') ? route('conceptos') : '#' }}">
                        <i class="fas fa-diagram-project"></i>
                        <span>Conceptos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('generadores*') ? 'active' : '' }}"
                       href="{{ Route::has('generadores') ? route('generadores') : '#' }}">
                        <i class="fas fa-calculator"></i>
                        <span>Generadores</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('materiales*') ? 'active' : '' }}"
                       href="{{ Route::has('materiales') ? route('materiales') : '#' }}">
                        <i class="fas fa-cubes"></i>
                        <span>Materiales</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('mano_obra*') ? 'active' : '' }}"
                       href="{{ Route::has('mano_obra') ? route('mano_obra') : '#' }}">
                        <i class="fas fa-users"></i>
                        <span>Mano de Obra</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('maquinaria_equipo*') ? 'active' : '' }}"
                       href="{{ Route::has('maquinaria_equipo') ? route('maquinaria_equipo') : '#' }}">
                        <i class="fas fa-tractor"></i>
                        <span>Maquinaria y Equipo</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('indirectos*') ? 'active' : '' }}"
                       href="{{ Route::has('indirectos') ? route('indirectos') : '#' }}">
                        <i class="fas fa-percent"></i>
                        <span>Indirectos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pu*') ? 'active' : '' }}"
                       href="{{ Route::has('pu') ? route('pu') : '#' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>P.U</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('presupuesto*') ? 'active' : '' }}"
                       href="{{ Route::has('presupuesto') ? route('presupuesto') : '#' }}">
                        <i class="fas fa-wallet"></i>
                        <span>Presupuesto</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reportes*') ? 'active' : '' }}"
                       href="{{ Route::has('reportes') ? route('reportes') : '#' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reportes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ Route::has('inicio') ? route('inicio') : '#' }}">
                        <i class="fas fa-right-from-bracket"></i>
                        <span>Salir</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="footer-text">
            <p class="mb-1 text-uppercase" style="letter-spacing:1px;">Akiraka Estudio</p>
            <p class="mb-0">© {{ date('Y') }} Derechos reservados</p>
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
