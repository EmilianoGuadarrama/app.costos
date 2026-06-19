@extends('layout')

@section('title', 'Inicio — App Costos')

@section('content')
<style>
    /* ── Home minimalista ── */
    .home-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 120px);
        padding: 40px 24px;
        text-align: center;
    }

    /* Logo */
    .home-logo-ring {
        width: 160px;
        height: 160px;
        background: #fff;
        border-radius: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 0 1px rgba(0,0,0,.06),
            0 8px 32px rgba(0,0,0,.10);
        margin-bottom: 36px;
        animation: fadeUp .6s ease both;
    }

    .home-logo-ring img {
        width: 120px;
        object-fit: contain;
    }

    /* Nombre */
    .home-brand {
        font-size: 1.8rem;
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
        animation: fadeUp .65s .08s ease both;
    }

    .home-tagline {
        font-size: .95rem;
        color: #9ca3af;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 48px;
        animation: fadeUp .65s .14s ease both;
    }

    /* Divisor */
    .home-divider {
        width: 40px;
        height: 2px;
        background: #e5e7eb;
        border-radius: 2px;
        margin: 0 auto 48px;
        animation: fadeUp .65s .18s ease both;
    }

    /* Accesos rápidos */
    .home-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 14px;
        max-width: 700px;
        width: 100%;
        animation: fadeUp .7s .22s ease both;
    }

    .home-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px 16px;
        text-decoration: none;
        color: #111827;
        transition: box-shadow .22s, transform .22s, border-color .22s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .home-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,.09);
        transform: translateY(-3px);
        border-color: #d1d5db;
        color: #111827;
    }

    .home-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .icon-obras    { background: #eff6ff; color: #2563eb; }
    .icon-proceso  { background: #fff7ed; color: #ea580c; }
    .icon-finanzas { background: #f0fdf4; color: #16a34a; }
    .icon-proveed  { background: #faf5ff; color: #7c3aed; }

    .home-card-label {
        font-size: .82rem;
        font-weight: 700;
        color: #374151;
        text-align: center;
        line-height: 1.3;
    }

    /* Footer minimalista */
    .home-footer {
        margin-top: 56px;
        font-size: .72rem;
        color: #d1d5db;
        letter-spacing: 1px;
        text-transform: uppercase;
        animation: fadeUp .7s .3s ease both;
    }

    /* Animación */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="home-wrapper">

    {{-- Logo centrado --}}
    <div class="home-logo-ring">
        <img src="{{ asset('img/logo_akiraka.jpeg') }}" alt="Logo Akiraka Estudio">
    </div>

    <h1 class="home-brand">Akiraka Estudio</h1>
    <p class="home-tagline">Sistema de gestión de costos</p>

    <div class="home-divider"></div>

    {{-- Accesos rápidos --}}
    <div class="home-cards">
        <a href="{{ route('obras.index') }}" class="home-card">
            <div class="home-card-icon icon-obras">
                <i class="bi bi-building"></i>
            </div>
            <span class="home-card-label">Obras &amp;<br>Presupuestos</span>
        </a>

        <a href="{{ route('obras_proceso.index') }}" class="home-card">
            <div class="home-card-icon icon-proceso">
                <i class="bi bi-cone-striped"></i>
            </div>
            <span class="home-card-label">Obras en<br>Proceso</span>
        </a>

        <a href="{{ route('ingresos.index') }}" class="home-card">
            <div class="home-card-icon icon-finanzas">
                <i class="bi bi-wallet2"></i>
            </div>
            <span class="home-card-label">Finanzas</span>
        </a>

        <a href="{{ route('pre_proveedores.index') }}" class="home-card">
            <div class="home-card-icon icon-proveed">
                <i class="bi bi-truck"></i>
            </div>
            <span class="home-card-label">Presup.<br>Proveedores</span>
        </a>
    </div>

    <p class="home-footer">© {{ date('Y') }} · Akiraka Estudio · Todos los derechos reservados</p>
</div>
@endsection
