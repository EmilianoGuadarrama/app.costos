{{-- resources/views/proyecto.blade.php --}}
    <!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyectos | App Precios Unitarios</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root{
            --ui-dark:#5a5a5a;
            --ui-dark-2:#4f4f4f;
            --ui-border:#8a8a8a;
        }
        body{ background:#e9e9e9; }

        .app-shell{ min-height:100vh; display:flex; flex-direction:column; }
        .app-main{ flex:1; display:flex; }

        /* Topbar */
        .topbar{ background:var(--ui-dark); border-bottom:1px solid rgba(255,255,255,.15); }
        .brand-badge{
            width:44px;height:44px;border-radius:50%;
            background:#fff;display:grid;place-items:center;
            position:relative; overflow:hidden;
        }
        .brand-badge .bolt{
            position:absolute; right:-2px; top:-2px;
            width:18px; height:18px; border-radius:4px;
            background:#2bd15a; display:grid; place-items:center;
            color:#fff; font-size:.85rem;
        }
        .topbar .welcome{ color:#fff; opacity:.9; font-size:.95rem; }

        /* Sidebar */
        .sidebar{
            width:270px;
            background:var(--ui-dark);
            color:#fff;
            border-right:1px solid rgba(255,255,255,.15);
        }
        .sidebar-inner{ padding:14px 14px 18px 14px; }
        .sidebar .search{
            background:#fff;
            border-radius:999px;
            padding:4px 10px;
        }
        .nav-vertical .list-group-item{
            background:transparent;
            border:0;
            color:#fff;
            padding:.55rem .75rem;
            border-radius:.35rem;
            display:flex;
            align-items:center;
            gap:.6rem;
        }
        .nav-vertical .list-group-item:hover{ background:rgba(255,255,255,.10); }
        .nav-vertical .list-group-item.active{ background:rgba(255,255,255,.18); color:#fff; }
        .nav-vertical .icon{ width:22px; text-align:center; opacity:.95; }
        .nav-divider{ height:1px; background:rgba(255,255,255,.18); margin:12px 0; }

        /* Content area */
        .content-area{ flex:1; background:#fff; }
        .content-wrap{
            max-width: 760px;
            margin: 0 auto;
            padding: 24px 18px 28px 18px;
        }

        /* Center sheet */
        .sheet{
            border:1px solid rgba(0,0,0,.15);
            border-radius:4px;
            padding: 26px 30px;
            background:#fff;
        }
        .section-title{
            font-size:.95rem;
            font-weight:700;
            margin: 6px 0 12px;
        }

        /* Compact input look */
        .form-compact .form-label{
            margin-bottom:0;
            font-size:.9rem;
            color:#222;
        }
        .form-compact .form-control,
        .form-compact .form-select{
            font-size:.82rem;
            padding:.2rem .45rem;
            height: 22px;
            border-radius:2px;
            max-width: 170px;
        }
        .form-compact .row{ --bs-gutter-x: 14px; }
        .form-compact .mb-2{ margin-bottom:.55rem !important; }
        .form-compact .radio-inline{ font-size:.85rem; }

        @media (max-width: 576px){
            .sheet{ padding: 18px 16px; }
            .form-compact .form-control,
            .form-compact .form-select{ max-width: 100%; height:auto; padding:.45rem .6rem; }
        }

        /* Footer */
        .footer{ background:var(--ui-dark); color:#fff; }
        .footer .muted{ opacity:.85; font-size:.9rem; }
        .footer .footer-title{ font-weight:700; margin-bottom:.6rem; }
        .footer a{ color:#fff; text-decoration:none; opacity:.9; }
        .footer a:hover{ opacity:1; text-decoration:underline; }

        /* Responsive (sidebar hidden on mobile, offcanvas used) */
        @media (max-width: 991.98px){ .sidebar{ display:none; } }
    </style>
</head>

<body>
<div class="app-shell">

    {{-- TOPBAR --}}
    <nav class="navbar navbar-expand-lg topbar py-2">
        <div class="container-fluid px-3">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-badge">
                    <i class="bi bi-bar-chart-fill text-dark"></i>
                    <span class="bolt"><i class="bi bi-lightning-fill"></i></span>
                </div>
                <div class="text-white fw-semibold">Akraka Estudio</div>
            </div>

            <div class="d-none d-md-block welcome text-center flex-grow-1">
                “Bienvenido a app.precios.unitarios.”
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-light d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                    <i class="bi bi-list"></i>
                </button>

                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                        Usuario Activo
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
                    <a href="{{ route('inicio') }}" class="list-group-item {{ request()->routeIs('inicio') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-house-door-fill"></i></span> Inicio
                    </a>

                    <a href="{{ route('proyectos') }}" class="list-group-item {{ request()->routeIs('proyectos') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-folder-fill"></i></span> Proyectos
                    </a>

                    <a href="{{ route('conceptos') }}" class="list-group-item {{ request()->routeIs('conceptos') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-diagram-3-fill"></i></span> C.Conceptos
                    </a>

                    <div class="nav-divider"></div>

                    <a href="{{ route('generadores') }}" class="list-group-item {{ request()->routeIs('generadores') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-calculator-fill"></i></span> Generadores
                    </a>

                    <a href="{{ route('materiales') }}" class="list-group-item {{ request()->routeIs('materiales') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-box-seam-fill"></i></span> Materiales
                    </a>

                    <a href="{{ route('mano_obra') }}" class="list-group-item {{ request()->routeIs('mano_obra') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-person-badge-fill"></i></span> M.Obra
                    </a>

                    <a href="{{ route('maquinaria_equipo') }}" class="list-group-item {{ request()->routeIs('maquinaria_equipo') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-truck-front-fill"></i></span> Maquinaria y Equipo
                    </a>

                    <a href="{{ route('indirectos') }}" class="list-group-item {{ request()->routeIs('indirectos') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-percent"></i></span> C.Indirectos
                    </a>

                    <a href="{{ route('pu') }}" class="list-group-item {{ request()->routeIs('pu') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-cash-coin"></i></span> P.U
                    </a>

                    <a href="{{ route('presupuesto') }}" class="list-group-item {{ request()->routeIs('presupuesto') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-receipt-cutoff"></i></span> Presupuesto
                    </a>

                    <a href="{{ route('reportes') }}" class="list-group-item {{ request()->routeIs('reportes') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-file-earmark-text-fill"></i></span> Reportes
                    </a>
                </div>
            </div>
        </aside>

        {{-- CONTENT --}}
        <main class="content-area">
            <div class="content-wrap">
                <div class="sheet">
                    <form method="POST" action="#" enctype="multipart/form-data" class="form-compact">
                        @csrf

                        <div class="section-title">Datos del Cliente</div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Nombre</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="cliente_nombre" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Razón Social</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="cliente_razon_social" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Dirección</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="cliente_direccion" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Teléfono</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="cliente_telefono" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Correo</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="email" name="cliente_correo" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">RFC</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="cliente_rfc" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Persona</label></div>
                            <div class="col-12 col-sm-6 col-lg-7">
                                <div class="d-flex gap-4 radio-inline">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cliente_persona" id="personaFisica" value="fisica" checked>
                                        <label class="form-check-label" for="personaFisica">Física</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cliente_persona" id="personaMoral" value="moral">
                                        <label class="form-check-label" for="personaMoral">Moral</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-title mt-2">Datos de la obra</div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Nombre del proyecto</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="obra_nombre_proyecto" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Ubicación del proyecto</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="obra_ubicacion" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Tipo de obra</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="obra_tipo" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Superficie de terreno</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="obra_superficie" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Tipo de uso</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="obra_uso" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Fecha de inicio estimada</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="date" name="obra_fecha_inicio" class="form-control"></div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Duración Estimada</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="obra_duracion" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="section-title mt-2">Datos de la empresa</div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Nombre</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="empresa_nombre" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Logo</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="file" name="empresa_logo" class="form-control"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Dirección</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="empresa_direccion" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Responsable técnico</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="empresa_responsable" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Cargo</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="text" name="empresa_cargo" class="form-control" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-4">
                            <div class="col-12 col-sm-6 col-lg-5"><label class="form-label">Firma digital</label></div>
                            <div class="col-12 col-sm-6 col-lg-7"><input type="file" name="firma_digital" class="form-control" accept="image/*,.pdf"></div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-secondary px-3">
                                <i class="bi bi-plus-circle me-2"></i> Agregar Información
                            </button>
                        </div>
                    </form>
                </div>
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
                        <div class="fw-semibold">Akraka</div>
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

                    <div class="d-flex gap-2 mt-3">
                        <a class="btn btn-outline-light btn-sm rounded-circle" style="width:40px;height:40px;display:grid;place-items:center;" href="#" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a class="btn btn-outline-light btn-sm rounded-circle" style="width:40px;height:40px;display:grid;place-items:center;" href="#" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a class="btn btn-outline-light btn-sm rounded-circle" style="width:40px;height:40px;display:grid;place-items:center;" href="#" aria-label="Web">
                            <i class="bi bi-lightning-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>

{{-- Bootstrap JS --}}
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
            <a href="{{ route('inicio') }}" class="list-group-item {{ request()->routeIs('inicio') ? 'active' : '' }}"><span class="icon"><i class="bi bi-house-door-fill"></i></span> Inicio</a>
            <a href="{{ route('proyectos') }}" class="list-group-item {{ request()->routeIs('proyectos') ? 'active' : '' }}"><span class="icon"><i class="bi bi-folder-fill"></i></span> Proyectos</a>
            <a href="{{ route('conceptos') }}" class="list-group-item {{ request()->routeIs('conceptos') ? 'active' : '' }}"><span class="icon"><i class="bi bi-diagram-3-fill"></i></span> C.Conceptos</a>
            <div class="nav-divider"></div>
            <a href="{{ route('generadores') }}" class="list-group-item {{ request()->routeIs('generadores') ? 'active' : '' }}"><span class="icon"><i class="bi bi-calculator-fill"></i></span> Generadores</a>
            <a href="{{ route('materiales') }}" class="list-group-item {{ request()->routeIs('materiales') ? 'active' : '' }}"><span class="icon"><i class="bi bi-box-seam-fill"></i></span> Materiales</a>
            <a href="{{ route('mano_obra') }}" class="list-group-item {{ request()->routeIs('mano_obra') ? 'active' : '' }}"><span class="icon"><i class="bi bi-person-badge-fill"></i></span> M.Obra</a>
            <a href="{{ route('maquinaria_equipo') }}" class="list-group-item {{ request()->routeIs('maquinaria_equipo') ? 'active' : '' }}"><span class="icon"><i class="bi bi-truck-front-fill"></i></span> Maquinaria y Equipo</a>
            <a href="{{ route('indirectos') }}" class="list-group-item {{ request()->routeIs('indirectos') ? 'active' : '' }}"><span class="icon"><i class="bi bi-percent"></i></span> C.Indirectos</a>
            <a href="{{ route('pu') }}" class="list-group-item {{ request()->routeIs('pu') ? 'active' : '' }}"><span class="icon"><i class="bi bi-cash-coin"></i></span> P.U</a>
            <a href="{{ route('presupuesto') }}" class="list-group-item {{ request()->routeIs('presupuesto') ? 'active' : '' }}"><span class="icon"><i class="bi bi-receipt-cutoff"></i></span> Presupuesto</a>
            <a href="{{ route('reportes') }}" class="list-group-item {{ request()->routeIs('reportes') ? 'active' : '' }}"><span class="icon"><i class="bi bi-file-earmark-text-fill"></i></span> Reportes</a>
        </div>
    </div>
</div>
</body>
</html>
