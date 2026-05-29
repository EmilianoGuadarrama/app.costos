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
        body {
            margin: 0;
            background: #f3f4f6;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .app-layout {
            min-height: 100vh;
            display: flex;
            gap: 20px;
            padding: 20px;
            align-items: flex-start;
        }

        @media print {
            .sidebar { display: none !important; }
            .app-layout { padding: 0 !important; display: block !important; }
            .main-content { width: 100% !important; border: none !important; box-shadow: none !important; background: transparent !important; }
            body { background: #fff !important; }
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 248px;
            min-width: 248px;
            background-color: #111111;
            color: #fff;
            padding: 26px 18px;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 2px 0 12px rgba(0,0,0,.18);
            min-height: calc(100vh - 40px);
            position: sticky;
            top: 20px;
        }

        .sidebar .brand-box {
            margin-bottom: 1.8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-bottom: 1.4rem;
            border-bottom: 1px solid #222;
        }

        .sidebar img.logo-sidebar {
            width: 140px;
            height: 80px;
            object-fit: contain;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 10px;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,.3);
        }

        .sidebar .brand-text {
            color: #555;
            font-size: .72rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin: 0;
        }

        /* Sección de categorías del menú */
        .sidebar-section-label {
            font-size: .58rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #444;
            font-weight: 700;
            padding: 14px 10px 5px;
            margin-bottom: 2px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu .nav-item {
            margin-bottom: 3px;
        }

        .sidebar .nav-link {
            color: #aaa;
            display: flex;
            align-items: center;
            gap: 11px;
            background: transparent;
            border-radius: 9px;
            padding: 10px 13px;
            transition: all .2s ease;
            font-weight: 500;
            text-decoration: none;
            font-size: .875rem;
            cursor: pointer;
            letter-spacing: .1px;
        }

        .sidebar .nav-link i {
            color: #555;
            width: 18px;
            text-align: center;
            transition: color .2s ease;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .sidebar .nav-link:hover {
            background-color: #1c1c1c;
            color: #e0e0e0;
        }
        .sidebar .nav-link:hover i { color: #999; }

        .sidebar .nav-link.active {
            background-color: #1e1e1e;
            color: #fff;
        }
        .sidebar .nav-link.active i { color: #ccc; }

        /* Dropdown */
        .sidebar .dropdown-toggle::after {
            display: inline-block;
            margin-left: auto;
            content: "";
            border-top: .28em solid;
            border-right: .28em solid transparent;
            border-bottom: 0;
            border-left: .28em solid transparent;
            transition: transform .2s ease;
            opacity: .45;
        }
        .sidebar .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
            opacity: .7;
        }

        /* Submenú */
        .submenu {
            list-style: none;
            padding: 2px 0 4px 12px;
            margin: 0 0 4px 0;
            border-left: 1px solid #232323;
        }
        .submenu .nav-item { margin-bottom: 1px; }
        .submenu .nav-link {
            padding: 7px 13px;
            font-size: .835rem;
            color: #666;
            border-radius: 7px;
            font-weight: 500;
        }
        .submenu .nav-link:hover {
            color: #ccc;
            background: transparent;
        }
        .submenu .nav-link.active {
            color: #fff;
            background: #1c1c1c;
        }
        /* Sin iconos en el submenú */
        .submenu .nav-link i { display: none !important; }

        /* Footer */
        .sidebar .footer-text {
            font-size: .66rem;
            color: #3a3a3a;
            margin-top: 18px;
            border-top: 1px solid #1e1e1e;
            padding-top: 14px;
            text-align: center;
            letter-spacing: .5px;
        }
        .sidebar .footer-text p { margin-bottom: 4px; }

        /* Contenido principal */
        .main-content {
            flex: 1;
            min-width: 0;
        }

        .content-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,.07);
            padding: 30px;
            min-height: calc(100vh - 40px);
        }

        @media (max-width: 991.98px) {
            .app-layout { flex-direction: column; }
            .sidebar {
                width: 100%;
                min-width: 100%;
                min-height: auto;
                position: relative;
                top: 0;
            }
            .content-card { min-height: auto; }
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
                    <a href="{{ route('obras.index') }}" class="nav-link {{ request()->routeIs('obras.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> <span class="nav-text">Obras (Presupuesto)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('obras_proceso.index') }}" class="nav-link {{ request()->routeIs('obras_proceso.*') ? 'active' : '' }}">
                        <i class="bi bi-cone-striped"></i> <span class="nav-text">Obras en Proceso</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pre_proveedores.index') }}" class="nav-link {{ request()->routeIs('pre_proveedores*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i> <span class="nav-text">Presup. Proveedores</span>
                    </a>
                </li>

                {{-- CATÁLOGOS --}}
                <li class="nav-item">
                    @php $isCat = request()->routeIs(['conceptos*','materiales*','maquinaria*','mano_obra*','areas*','unidad_medida*']); @endphp
                    <a class="nav-link dropdown-toggle {{ $isCat ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#menuCatalogos" aria-expanded="{{ $isCat ? 'true' : 'false' }}">
                        <i class="fas fa-list"></i>
                        <span>Catálogos</span>
                    </a>
                    <div class="collapse {{ $isCat ? 'show' : '' }}" id="menuCatalogos">
                        <ul class="submenu">
                            <li><a href="{{ route('conceptos.index') }}" class="nav-link {{ request()->routeIs('conceptos*') ? 'active' : '' }}">Conceptos</a></li>
                            <li><a href="{{ route('materiales.index') }}" class="nav-link {{ request()->routeIs('materiales*') ? 'active' : '' }}">Materiales</a></li>
                            <li><a href="{{ route('maquinaria.index') }}" class="nav-link {{ request()->routeIs('maquinaria*') ? 'active' : '' }}">Maquinaria</a></li>
                            <li><a href="{{ route('mano_obra.index') }}" class="nav-link {{ request()->routeIs('mano_obra*') ? 'active' : '' }}">Mano de Obra</a></li>
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
