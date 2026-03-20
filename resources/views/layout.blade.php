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
            box-shadow: 4px 0 15px rgba(0,0,0,0.2);
            min-height: calc(100vh - 40px);
            position: sticky;
            top: 20px;
        }

        .sidebar img.logo-sidebar {
            width: 100px;
            height: 100px;
            object-fit: contain;
            background-color: #fff;
            border-radius: 50%;
            padding: 20px;
            margin-bottom: 15px;
        }

        .sidebar .nav-link {
            color: #e0e0e0;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            padding: 12px 15px;
            transition: .3s;
            text-decoration: none;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background:#2c2c2c;
            color:#fff;
        }

        .main-content{
            flex:1;
        }

        .content-card{
            background:#fff;
            border-radius:12px;
            box-shadow:0 8px 22px rgba(0,0,0,.08);
            padding:30px;
            min-height:calc(100vh - 40px);
        }

        @media (max-width: 991px){
            .app-layout{
                flex-direction:column;
            }
            .sidebar{
                width:100%;
                position:relative;
            }
        }
    </style>
</head>

<body>

<div class="app-layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div>

            <div class="text-center mb-4">
                <img src="{{ asset('images/logo_akiraka.png') }}" class="logo-sidebar">
                <p class="small text-uppercase">Akiraka Estudio</p>
            </div>

            <ul class="nav flex-column">

                <li>
                    <a class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}" href="{{ route('inicio') }}">
                        <i class="fas fa-home me-2"></i> Inicio
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('proyectos*') ? 'active' : '' }}" href="{{ route('proyectos') }}">
                        <i class="fas fa-folder me-2"></i> Proyectos
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('conceptos*') ? 'active' : '' }}" href="{{ route('conceptos') }}">
                        <i class="fas fa-diagram-project me-2"></i> Conceptos
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('generadores*') ? 'active' : '' }}" href="{{ route('generadores') }}">
                        <i class="fas fa-calculator me-2"></i> Generadores
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('materiales*') ? 'active' : '' }}" href="{{ route('materiales') }}">
                        <i class="fas fa-boxes-stacked me-2"></i> Materiales
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('mano_obra*') ? 'active' : '' }}" href="{{ route('mano_obra') }}">
                        <i class="fas fa-users me-2"></i> Mano de Obra
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('maquinaria_equipo*') ? 'active' : '' }}" href="{{ route('maquinaria_equipo') }}">
                        <i class="fas fa-truck me-2"></i> Maquinaria
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('indirectos*') ? 'active' : '' }}" href="{{ route('indirectos') }}">
                        <i class="fas fa-percent me-2"></i> Indirectos
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('pu*') ? 'active' : '' }}" href="{{ route('pu') }}">
                        <i class="fas fa-file-invoice-dollar me-2"></i> P.U
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('presupuesto*') ? 'active' : '' }}" href="{{ route('presupuesto') }}">
                        <i class="fas fa-wallet me-2"></i> Presupuesto
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('reportes*') ? 'active' : '' }}" href="{{ route('reportes') }}">
                        <i class="fas fa-chart-bar me-2"></i> Reportes
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('unidad_medida*') ? 'active' : '' }}" href="{{ route('unidad_medida.index') }}">
                        <i class="fas fa-ruler me-2"></i> Unidades
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('partidas*') ? 'active' : '' }}" href="{{ route('partidas') }}">
                        <i class="fas fa-list me-2"></i> Partidas
                    </a>
                </li>

            </ul>
        </div>

        <div class="text-center small mt-4">
            © {{ date('Y') }} Akiraka
        </div>
    </aside>


    {{-- CONTENIDO --}}
    <main class="main-content">
        <div class="content-card">
            @yield('content')
        </div>
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>