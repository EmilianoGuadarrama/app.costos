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

        @media print {
            .sidebar { display: none !important; }
            .app-layout { padding: 0 !important; display: block !important; }
            .main-content { width: 100% !important; border: none !important; box-shadow: none !important; background: transparent !important; }
            body { background: #fff !important; }
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
            width:160px;
            height:90px;
            object-fit:contain;
            background-color:#ffffff;
            border-radius:14px;
            padding:12px;
            margin-bottom:15px;
            box-shadow:0 4px 15px rgba(0,0,0,0.25);
        }

        .sidebar .brand-text{
            color:#ccc;
            font-size:.78rem;
            letter-spacing:3px;
            text-transform:uppercase;
            margin:0;
            text-align:center;
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
            padding:12px 16px;
            transition:all .25s ease;
            font-weight:500;
            text-decoration:none;
            font-size:0.92rem;
            cursor: pointer;
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

        /* Dropdown custom styles */
        .sidebar .dropdown-toggle::after {
            display: inline-block;
            margin-left: auto;
            vertical-align: middle;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
            transition: transform .25s ease;
        }
        .sidebar .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .submenu {
            list-style: none;
            padding: 0 0 0 15px;
            margin: 5px 0 10px 0;
            border-left: 1px solid #333;
        }

        .submenu .nav-link {
            padding: 8px 16px;
            font-size: 0.88rem;
            color: #aaa;
        }

        .submenu .nav-link:hover,
        .submenu .nav-link.active {
            color: #fff;
            background-color: transparent;
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
                <img src="{{ asset('img/logo_akiraka.jpeg') }}" alt="Logo Akiraka" class="logo-sidebar">
                <p class="brand-text">Akiraka Estudio</p>
            </div>

            <ul class="sidebar-menu">
                <li class="nav-item">
                    <a href="{{ route('inicio') }}" class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>

                {{-- OBRAS (principal) --}}
                <li class="nav-item">
                    <a href="{{ route('obras.index') }}" class="nav-link {{ request()->routeIs('obras*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Mis Obras</span>
                    </a>
                </li>

                {{-- CATÁLOGOS --}}
                <li class="nav-item">
                    @php $isCat = request()->routeIs(['conceptos*','materiales*','maquinaria*','areas*','unidad_medida*']); @endphp
                    <a class="nav-link dropdown-toggle {{ $isCat ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#menuCatalogos" aria-expanded="{{ $isCat ? 'true' : 'false' }}">
                        <i class="fas fa-list"></i>
                        <span>Catálogos</span>
                    </a>
                    <div class="collapse {{ $isCat ? 'show' : '' }}" id="menuCatalogos">
                        <ul class="submenu">
                            <li><a href="{{ route('conceptos.index') }}" class="nav-link {{ request()->routeIs('conceptos*') ? 'active' : '' }}">Conceptos</a></li>
                            <li><a href="{{ route('materiales.index') }}" class="nav-link {{ request()->routeIs('materiales*') ? 'active' : '' }}">Materiales</a></li>
                            <li><a href="{{ route('maquinaria.index') }}" class="nav-link {{ request()->routeIs('maquinaria*') ? 'active' : '' }}"><i class="bi bi-truck me-1"></i>Maquinaria</a></li>
                            <li><a href="{{ route('areas.index') }}" class="nav-link {{ request()->routeIs('areas*') ? 'active' : '' }}">Areas</a></li>
                            <li><a href="{{ route('unidad_medida.index') }}" class="nav-link {{ request()->routeIs('unidad_medida*') ? 'active' : '' }}">Unidades</a></li>
                        </ul>
                    </div>
                </li>

                {{-- ADMINISTRACIÓN --}}
                <li class="nav-item">
                    @php $isAdmin = request()->routeIs(['clientes*','proveedores*','empleados*']); @endphp
                    <a class="nav-link dropdown-toggle {{ $isAdmin ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#menuAdmin" aria-expanded="{{ $isAdmin ? 'true' : 'false' }}">
                        <i class="fas fa-id-card"></i>
                        <span>Administración</span>
                    </a>
                    <div class="collapse {{ $isAdmin ? 'show' : '' }}" id="menuAdmin">
                        <ul class="submenu">
                            <li><a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes*') ? 'active' : '' }}">Clientes</a></li>
                            <li><a href="{{ route('proveedores.index') }}" class="nav-link {{ request()->routeIs('proveedores*') ? 'active' : '' }}">Proveedores</a></li>
                            <li><a href="{{ route('empleados.index') }}" class="nav-link {{ request()->routeIs('empleados*') ? 'active' : '' }}">Empleados</a></li>
                        </ul>
                    </div>
                </li>

                {{-- FINANZAS --}}
                <li class="nav-item">
                    @php $isFin = request()->routeIs(['ingresos*','egresos*','caja_general*']); @endphp
                    <a class="nav-link dropdown-toggle {{ $isFin ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#menuFinanzas" aria-expanded="{{ $isFin ? 'true' : 'false' }}">
                        <i class="fas fa-coins"></i>
                        <span>Finanzas</span>
                    </a>
                    <div class="collapse {{ $isFin ? 'show' : '' }}" id="menuFinanzas">
                        <ul class="submenu">
                            <li><a href="{{ route('ingresos.index') }}" class="nav-link {{ request()->routeIs('ingresos*') ? 'active' : '' }}">Ingresos</a></li>
                            <li><a href="{{ route('egresos.index') }}" class="nav-link {{ request()->routeIs('egresos*') ? 'active' : '' }}">Egresos</a></li>
                            <li><a href="{{ route('caja_general.index') }}" class="nav-link {{ request()->routeIs('caja_general*') ? 'active' : '' }}">Caja General</a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item" style="margin-top:20px;">
                    <a href="{{ route('inicio') }}" class="nav-link">
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
