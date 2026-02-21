{{-- resources/views/app/inicio.blade.php --}}
    <!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Precios Unitarios</title>

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

        /* Layout base */
        .app-shell{
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .app-main{
            flex: 1;
            display: flex;
        }

        /* Navbar */
        .topbar{
            background: var(--ui-dark);
            border-bottom: 1px solid rgba(255,255,255,.15);
        }
        .brand-badge{
            width: 44px; height: 44px;
            border-radius: 50%;
            background: #ffffff;
            display: grid; place-items: center;
            position: relative;
            overflow: hidden;
        }
        .brand-badge .bolt{
            position: absolute;
            right: -2px; top: -2px;
            width: 18px; height: 18px;
            border-radius: 4px;
            background: #2bd15a;
            display: grid; place-items: center;
            color: #fff;
            font-size: .85rem;
        }
        .topbar .welcome{
            color:#fff; opacity:.9;
            font-size: .95rem;
        }

        /* Sidebar */
        .sidebar{
            width: 270px;
            background: var(--ui-dark);
            color: #fff;
            border-right: 1px solid rgba(255,255,255,.15);
        }
        .sidebar-inner{
            padding: 14px 14px 18px 14px;
        }
        .sidebar .search{
            background: #fff;
            border-radius: 999px;
            padding: 4px 10px;
        }
        .nav-vertical .list-group-item{
            background: transparent;
            border: 0;
            color: #fff;
            padding: .55rem .75rem;
            border-radius: .35rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .nav-vertical .list-group-item:hover{
            background: rgba(255,255,255,.10);
        }
        .nav-vertical .list-group-item.active{
            background: rgba(255,255,255,.18);
            color: #fff;
        }
        .nav-vertical .icon{
            width: 22px;
            text-align: center;
            opacity: .95;
        }
        .nav-divider{
            height: 1px;
            background: rgba(255,255,255,.18);
            margin: 12px 0;
        }

        /* Content */
        .content-area{
            flex: 1;
            background: #fff;
        }
        .content-card{
            max-width: 820px;
            margin: 0 auto;
            padding: 34px 18px 30px 18px;
        }
        .content-title{
            text-align: center;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .content-text{
            color: #333;
            font-size: .98rem;
            line-height: 1.6;
        }
        .content-img{
            width: 100%;
            max-width: 520px;
            display: block;
            margin: 26px auto 0 auto;
            border-radius: 4px;
            background: #f0f0f0;
        }

        /* Footer */
        .footer{
            background: var(--ui-dark);
            color: #fff;
        }
        .footer .muted{
            opacity: .85;
            font-size: .9rem;
        }
        .footer a{ color: #fff; text-decoration: none; opacity: .9; }
        .footer a:hover{ opacity: 1; text-decoration: underline; }
        .footer .footer-title{
            font-weight: 700;
            margin-bottom: .6rem;
        }
        .footer .social .btn{
            width: 40px; height: 40px;
            border-radius: 999px;
            display: grid; place-items: center;
        }

        /* Responsive */
        @media (max-width: 991.98px){
            .sidebar{ display:none; }
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

            <div class="d-flex align-items-center gap-2">
                {{-- Botón sidebar móvil --}}
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
                        <li>
                            {{-- Ajusta a tu ruta real --}}
                            <a class="dropdown-item text-danger" href="#">Cerrar sesión</a>
                        </li>
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
                    {{-- Ajusta href/route() a tus rutas reales --}}
                    <a href="#" class="list-group-item active">
                        <span class="icon"><i class="bi bi-house-door-fill"></i></span> Inicio
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-folder-fill"></i></span> Proyectos
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-diagram-3-fill"></i></span> C.Conceptos
                    </a>
                    <div class="nav-divider"></div>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-calculator-fill"></i></span> Generadores
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-box-seam-fill"></i></span> Materiales
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-person-badge-fill"></i></span> M.Obra
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-truck-front-fill"></i></span> Maquinaria y Equipo
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-percent"></i></span> C.Indirectos
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-cash-coin"></i></span> P.U
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-receipt-cutoff"></i></span> Presupuesto
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="icon"><i class="bi bi-file-earmark-text-fill"></i></span> Reportes
                    </a>
                </div>
            </div>
        </aside>

        {{-- CONTENT --}}
        <main class="content-area">
            <div class="content-card">
                <h3 class="content-title">¿Cuál es nuestro propósito?</h3>

                <div class="content-text">
                    <p>
                        La App de Precios Unitarios es una herramienta digital diseñada para optimizar la elaboración, análisis y control de presupuestos en proyectos de construcción.
                        Su propósito principal es facilitar el cálculo estructurado de costos directos e indirectos mediante la integración de materiales, mano de obra, maquinaria y rendimientos,
                        permitiendo generar análisis de precio unitario (APU) de manera precisa y estandarizada.
                    </p>

                    <p>
                        La aplicación centraliza la información técnica y financiera del proyecto en una plataforma intuitiva, ofreciendo control sobre catálogos de conceptos,
                        desglose de insumos, generación automática de costos y visualización de reportes comparativos. Esto reduce errores manuales, mejora la trazabilidad de los cálculos
                        y agiliza la toma de decisiones.
                    </p>

                    <p class="mb-0">
                        Orientada a arquitectos, ingenieros civiles, contratistas y desarrolladores, esta solución digital transforma el proceso tradicional de presupuestación en un sistema
                        eficiente, organizado y adaptable a diferentes tipos de obra.
                    </p>


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
                        <div class="fw-semibold">Akiraka</div>
                    </div>
                    <div class="muted small">
                        Dirección<br>
                        Derechos reservados
                    </div>
                </div>

                <div class="col-12 col-md-5">
                    <div class="footer-title">Contenido</div>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-grid gap-1 small">
                                <a href="#">Inicio</a>
                                <a href="#">Proyectos</a>
                                <a href="#">Conceptos</a>
                                <a href="#">Generadores</a>
                                <a href="#">Materiales</a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid gap-1 small">
                                <a href="#">Mano de obra</a>
                                <a href="#">Maquinaria</a>
                                <a href="#">Indirectos</a>
                                <a href="#">Presupuesto</a>
                                <a href="#">Reportes</a>
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
            <a href="#" class="list-group-item active"><span class="icon"><i class="bi bi-house-door-fill"></i></span> Inicio</a>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-folder-fill"></i></span> Proyectos</a>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-diagram-3-fill"></i></span> C.Conceptos</a>
            <div class="nav-divider"></div>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-calculator-fill"></i></span> Generadores</a>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-box-seam-fill"></i></span> Materiales</a>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-person-badge-fill"></i></span> M.Obra</a>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-truck-front-fill"></i></span> Maquinaria y Equipo</a>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-percent"></i></span> C.Indirectos</a>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-cash-coin"></i></span> P.U</a>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-receipt-cutoff"></i></span> Presupuesto</a>
            <a href="#" class="list-group-item"><span class="icon"><i class="bi bi-file-earmark-text-fill"></i></span> Reportes</a>
        </div>
    </div>
</div>
</body>
</html>
