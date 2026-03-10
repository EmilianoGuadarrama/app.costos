{{-- resources/views/layout.blade.php --}}
    <!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','App Precios Unitarios')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root{
            --ui-dark:#5a5a5a;
            --ui-dark-2:#4f4f4f;
            --ui-light:#f5f5f5;
            --ui-border:#8a8a8a;
        }

        body{ background: #e9e9e9; }

        .app-shell{ min-height: 100vh; display: flex; flex-direction: column; }
        .app-main{ flex: 1; display: flex; min-height: 0; }

        .topbar{ background: var(--ui-dark); border-bottom: 1px solid rgba(255,255,255,.15); }
        .brand-badge{
            width: 44px; height: 44px; border-radius: 50%;
            background: #ffffff; display: grid; place-items: center;
            position: relative; overflow: hidden;
        }
        .brand-badge .bolt{
            position: absolute; right: -2px; top: -2px;
            width: 18px; height: 18px; border-radius: 4px;
            background: #2bd15a; display: grid; place-items: center;
            color: #fff; font-size: .85rem;
        }
        .topbar .welcome{ color:#fff; opacity:.9; font-size: .95rem; }

        /* SIDEBAR */
        .sidebar{
            width: 270px; background: var(--ui-dark); color: #fff;
            border-right: 1px solid rgba(255,255,255,.15);
            display:flex; flex-direction:column;
            min-height: 0;
        }
        .sidebar-inner{
            padding: 14px 14px 18px 14px;
            overflow:auto;
        }
        .sidebar .search{ background: #fff; border-radius: 999px; padding: 4px 10px; }

        .nav-vertical .list-group-item{
            background: transparent; border: 0; color: #fff;
            padding: .55rem .75rem; border-radius: .35rem;
            display: flex; align-items: center; gap: .6rem;
        }
        .nav-vertical .list-group-item:hover{ background: rgba(255,255,255,.10); }
        .nav-vertical .list-group-item.active{ background: rgba(255,255,255,.18); color: #fff; }
        .nav-vertical .icon{ width: 22px; text-align: center; opacity: .95; }
        .nav-divider{ height: 1px; background: rgba(255,255,255,.18); margin: 12px 0; }

        .content-area{ flex: 1; background: #fff; min-height: 0; overflow:auto; }
        .content-card{ max-width: 820px; margin: 0 auto; padding: 34px 18px 30px 18px; }

        .footer{ background: var(--ui-dark); color: #fff; }
        .footer .muted{ opacity: .85; font-size: .9rem; }
        .footer a{ color: #fff; text-decoration: none; opacity: .9; }
        .footer a:hover{ opacity: 1; text-decoration: underline; }
        .footer .footer-title{ font-weight: 700; margin-bottom: .6rem; }
        .footer .social .btn{ width: 40px; height: 40px; border-radius: 999px; display: grid; place-items: center; }

        @media (max-width: 991.98px){
            .sidebar{ display:none; }
        }
    </style>
</head>

<body>
@php
    // Menú único (desktop + mobile)
    $menu = [
        ['route'=>'inicio','label'=>'Inicio','icon'=>'bi-house-door-fill','match'=>'inicio'],
        ['route'=>'proyectos','label'=>'Proyectos','icon'=>'bi-folder-fill','match'=>'proyectos'],
        ['route'=>'conceptos','label'=>'C.Conceptos','icon'=>'bi-diagram-3-fill','match'=>'conceptos'],
        ['divider'=>true],
        ['route'=>'generadores','label'=>'Generadores','icon'=>'bi-calculator-fill','match'=>'generadores'],
        ['route'=>'materiales','label'=>'Materiales','icon'=>'bi-box-seam-fill','match'=>'materiales'],
        ['route'=>'mano_obra','label'=>'M.Obra','icon'=>'bi-person-badge-fill','match'=>'mano_obra'],
        ['route'=>'maquinaria_equipo','label'=>'Maquinaria y Equipo','icon'=>'bi-truck-front-fill','match'=>'maquinaria_equipo'],
        ['route'=>'indirectos','label'=>'C.Indirectos','icon'=>'bi-percent','match'=>'indirectos'],
        ['route'=>'pu','label'=>'P.U','icon'=>'bi-cash-coin','match'=>'pu'],
        ['route'=>'presupuesto','label'=>'Presupuesto','icon'=>'bi-receipt-cutoff','match'=>'presupuesto'],
        ['route'=>'reportes','label'=>'Reportes','icon'=>'bi-file-earmark-text-fill','match'=>'reportes'],
    ];

    $renderMenu = function() use ($menu) {
        foreach($menu as $item){
            if(!empty($item['divider'])){
                echo '<div class="nav-divider"></div>';
                continue;
            }
            $active = request()->routeIs($item['match']) ? ' active' : '';
            echo '<a href="'.route($item['route']).'" class="list-group-item'.$active.'">';
            echo '<span class="icon"><i class="bi '.$item['icon'].'"></i></span> '.$item['label'];
            echo '</a>';
        }
    };
@endphp

<div class="app-shell">

    {{-- TOPBAR --}}
    <nav class="navbar navbar-expand-lg topbar py-2">
        <div class="container-fluid px-3">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-badge">
                    <i class="bi bi-bar-chart-fill text-dark"></i>
                    <span class="bolt"><i class="bi bi-lightning-fill"></i></span>
                </div>
                <div class="text-white fw-semibold">Akiraka Estudio</div>
            </div>

            <div class="d-none d-md-block welcome text-center flex-grow-1">
                @yield('topbar_welcome','“Bienvenido a app.precios.unitarios.”')
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-light d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                    <i class="bi bi-list"></i>
                </button>

                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                        @yield('topbar_user','Usuario Activo')
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN --}}
    <div class="app-main">

        {{-- SIDEBAR (desktop) --}}
        <aside class="sidebar">
            <div class="sidebar-inner">
                <div class="search mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-search text-secondary"></i>
                    <input type="text" class="form-control form-control-sm border-0 shadow-none p-0" placeholder="Search">
                </div>

                <div class="list-group nav-vertical">
                    {!! $renderMenu() !!}
                </div>
            </div>
        </aside>

        {{-- CONTENT --}}
        <main class="content-area">
            <div class="content-card">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- FOOTER --}}
    <footer class="footer py-4">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-12 col-md-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="brand-badge" style="transform: scale(.92);">
                            <i class="bi bi-bar-chart-fill text-dark"></i>
                            <span class="bolt"><i class="bi bi-lightning-fill"></i></span>
                        </div>
                        <div class="fw-semibold">Akiraka</div>
                    </div>
                    <div class="muted small">Dirección<br>Derechos reservados</div>
                </div>

                <div class="col-12 col-md-5">
                    <div class="footer-title">Contenido</div>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-grid gap-1 small">
                                <a href="{{ route('inicio') }}">Inicio</a>
                                <a href="{{ route('proyectos') }}">Proyectos</a>
                                <a href="{{ route('conceptos') }}">Conceptos</a>
                                <a href="{{ route('generadores') }}">Generadores</a>
                                <a href="{{ route('materiales') }}">Materiales</a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid gap-1 small">
                                <a href="{{ route('mano_obra') }}">Mano de obra</a>
                                <a href="{{ route('maquinaria_equipo') }}">Maquinaria</a>
                                <a href="{{ route('indirectos') }}">Indirectos</a>
                                <a href="{{ route('presupuesto') }}">Presupuesto</a>
                                <a href="{{ route('reportes') }}">Reportes</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="footer-title">Contactos</div>
                    <div class="small muted">
                        <div class="d-flex justify-content-between border-bottom border-light border-opacity-25 py-1">
                            <span>Teléfono</span><span>— — — —</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom border-light border-opacity-25 py-1">
                            <span>Correo</span><span>— — — —</span>
                        </div>
                    </div>

                    <div class="social d-flex gap-2 mt-3">
                        <a class="btn btn-outline-light btn-sm" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a class="btn btn-outline-light btn-sm" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a class="btn btn-outline-light btn-sm" href="#" aria-label="Web"><i class="bi bi-lightning-fill"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- OFFCANVAS SIDEBAR (mobile) --}}
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menú</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="search mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-search text-secondary"></i>
            <input type="text" class="form-control form-control-sm border-0 shadow-none p-0" placeholder="Search">
        </div>

        <div class="list-group nav-vertical">
            {!! $renderMenu() !!}
        </div>
    </div>
</div>
</body>
</html>
