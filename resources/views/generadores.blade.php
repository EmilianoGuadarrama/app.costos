{{-- resources/views/generadores.blade.php --}}
    <!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generadores | App Precios Unitarios</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root{ --ui-dark:#5a5a5a; }
        body{ background:#e9e9e9; }
        .app-shell{ min-height:100vh; display:flex; flex-direction:column; }
        .app-main{ flex:1; display:flex; }

        /* Topbar */
        .topbar{ background:var(--ui-dark); border-bottom:1px solid rgba(255,255,255,.15); }
        .brand-badge{
            width:44px;height:44px;border-radius:50%;
            background:#fff;display:grid;place-items:center;position:relative;overflow:hidden;
        }
        .brand-badge .bolt{
            position:absolute; right:-2px; top:-2px;
            width:18px;height:18px;border-radius:4px;background:#2bd15a;
            display:grid;place-items:center;color:#fff;font-size:.85rem;
        }
        .topbar .welcome{ color:#fff; opacity:.9; font-size:.95rem; }

        /* Sidebar */
        .sidebar{
            width:270px;background:var(--ui-dark);color:#fff;
            border-right:1px solid rgba(255,255,255,.15);
        }
        .sidebar-inner{ padding:14px 14px 18px; }
        .sidebar .search{ background:#fff;border-radius:999px;padding:4px 10px; }
        .nav-vertical .list-group-item{
            background:transparent;border:0;color:#fff;
            padding:.55rem .75rem;border-radius:.35rem;
            display:flex;align-items:center;gap:.6rem;
        }
        .nav-vertical .list-group-item:hover{ background:rgba(255,255,255,.10); }
        .nav-vertical .list-group-item.active{ background:rgba(255,255,255,.18); color:#fff; }
        .nav-vertical .icon{ width:22px; text-align:center; opacity:.95; }
        .nav-divider{ height:1px;background:rgba(255,255,255,.18);margin:12px 0; }

        /* Content */
        .content-area{ flex:1;background:#fff; }
        .content-wrap{ max-width: 860px;margin:0 auto;padding:24px 18px 28px; }
        .sheet{
            border:1px solid rgba(0,0,0,.15);
            border-radius:4px;background:#fff;
            padding: 26px 30px;
        }

        /* Compact inputs like your UI */
        .form-compact .form-label{ margin-bottom:0;font-size:.9rem;color:#222; }
        .form-compact .form-control{
            font-size:.82rem;padding:.2rem .45rem;height:22px;border-radius:2px;max-width:230px;
        }
        .form-compact .row{ --bs-gutter-x: 14px; }
        .form-compact .mb-2{ margin-bottom:.55rem !important; }

        /* Table */
        .table thead th{
            font-size:.74rem;
            text-transform:none;
            vertical-align:middle;
        }
        .table td{ height:44px; vertical-align:middle; }
        .btn-action{ width:30px;height:30px;display:inline-grid;place-items:center;padding:0; }

        /* Footer */
        .footer{ background:var(--ui-dark);color:#fff; }
        .footer .muted{ opacity:.85;font-size:.9rem; }
        .footer .footer-title{ font-weight:700;margin-bottom:.6rem; }
        .footer a{ color:#fff;text-decoration:none;opacity:.9; }
        .footer a:hover{ opacity:1;text-decoration:underline; }

        @media (max-width: 991.98px){ .sidebar{ display:none; } }
        @media (max-width: 576px){
            .sheet{ padding:18px 16px; }
            .form-compact .form-control{ max-width:100%;height:auto;padding:.45rem .6rem; }
        }
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

            <div class="d-flex align-items-center gap-2 text-white">
                <i class="bi bi-person-circle fs-4"></i>
                <span class="small">Usuario Activo</span>
            </div>
        </div>
    </nav>

    {{-- MAIN --}}
    <div class="app-main">

        {{-- SIDEBAR --}}
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

                    {{-- pendientes --}}
                    <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-box-seam-fill"></i></span> Materiales</a>
                    <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-person-badge-fill"></i></span> M.Obra</a>
                    <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-truck-front-fill"></i></span> Maquinaria y Equipo</a>
                    <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-percent"></i></span> C.Indirectos</a>
                    <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-cash-coin"></i></span> P.U</a>
                    <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-receipt-cutoff"></i></span> Presupuesto</a>
                    <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-file-earmark-text-fill"></i></span> Reportes</a>
                </div>
            </div>
        </aside>

        {{-- CONTENT --}}
        <main class="content-area">
            <div class="content-wrap">
                <div class="sheet">

                    <div class="mb-3 fw-semibold">Generadores</div>

                    <form method="POST" action="#" class="form-compact">
                        @csrf

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-4 col-lg-3"><label class="form-label">Concepto</label></div>
                            <div class="col-12 col-sm-8 col-lg-9"><input class="form-control" name="concepto" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-4 col-lg-3"><label class="form-label">Unidad</label></div>
                            <div class="col-12 col-sm-8 col-lg-9"><input class="form-control" name="unidad" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-4 col-lg-3"><label class="form-label">Localización</label></div>
                            <div class="col-12 col-sm-8 col-lg-9"><input class="form-control" name="localizacion" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-4 col-lg-3"><label class="form-label">Ejes</label></div>
                            <div class="col-12 col-sm-8 col-lg-9"><input class="form-control" name="ejes" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-4 col-lg-3"><label class="form-label">No de piezas</label></div>
                            <div class="col-12 col-sm-8 col-lg-9"><input class="form-control" name="no_piezas" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-4 col-lg-3"><label class="form-label">Ancho</label></div>
                            <div class="col-12 col-sm-8 col-lg-9"><input class="form-control" name="ancho" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-4 col-lg-3"><label class="form-label">Largo</label></div>
                            <div class="col-12 col-sm-8 col-lg-9"><input class="form-control" name="largo" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-12 col-sm-4 col-lg-3"><label class="form-label">Alto</label></div>
                            <div class="col-12 col-sm-8 col-lg-9"><input class="form-control" name="alto" placeholder="Placeholder"></div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-12 col-sm-4 col-lg-3"><label class="form-label">Resultado</label></div>
                            <div class="col-12 col-sm-8 col-lg-9"><input class="form-control" name="resultado" placeholder="Placeholder"></div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-sm btn-secondary px-3">
                                <i class="bi bi-plus-circle me-2"></i> Agregar Información
                            </button>
                        </div>
                    </form>

                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead>
                                <tr class="text-center">
                                    <th>Concepto</th>
                                    <th>Unidad</th>
                                    <th>Localización</th>
                                    <th>Ejes</th>
                                    <th>No de piezas</th>
                                    <th>Ancho</th>
                                    <th>Largo</th>
                                    <th>Alto</th>
                                    <th>Resultado</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @for($i=0; $i<4; $i++)
                                    <tr>
                                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-dark btn-sm btn-action me-1" title="Editar">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-dark btn-sm btn-action" title="Eliminar">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>

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
                                <a href="#">Materiales</a>
                                <a href="#">Mano de obra</a>
                                <a href="#">Maquinaria</a>
                                <a href="#">Indirectos</a>
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
                        <a class="btn btn-outline-light btn-sm rounded-circle" style="width:40px;height:40px;display:grid;place-items:center;" href="#">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a class="btn btn-outline-light btn-sm rounded-circle" style="width:40px;height:40px;display:grid;place-items:center;" href="#">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a class="btn btn-outline-light btn-sm rounded-circle" style="width:40px;height:40px;display:grid;place-items:center;" href="#">
                            <i class="bi bi-lightning-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
