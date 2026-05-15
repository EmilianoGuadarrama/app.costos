@extends('layout')
@section('title', 'Proyectos — App Costos')
@section('content')
<style>
/* ── Dashboard Multi-Proyecto ── */
.dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 32px;
}
.dash-title {
    font-size: 1.85rem;
    font-weight: 800;
    color: #111827;
    margin: 0;
}
.dash-subtitle {
    color: #6b7280;
    font-size: .95rem;
    margin: 4px 0 0;
}
.btn-nuevo-proyecto {
    background: #111827;
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: .75rem 1.4rem;
    font-weight: 700;
    font-size: .9rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background .2s;
}
.btn-nuevo-proyecto:hover { background: #374151; color: #fff; }

/* Tarjetas de proyecto */
.proyectos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
}
.proyecto-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,.05);
    transition: box-shadow .25s, transform .25s;
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.proyecto-card:hover {
    box-shadow: 0 12px 32px rgba(0,0,0,.1);
    transform: translateY(-2px);
}
.proj-status-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.badge-estado {
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    padding: 4px 12px;
}
.badge-activo   { background: #d1fae5; color: #065f46; }
.badge-pausado  { background: #fef3c7; color: #92400e; }
.badge-terminado{ background: #e0e7ff; color: #3730a3; }
.badge-default  { background: #f3f4f6; color: #374151; }

.proj-nombre {
    font-size: 1.2rem;
    font-weight: 800;
    color: #111827;
    margin: 0;
    line-height: 1.3;
}
.proj-cliente {
    font-size: .88rem;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 6px;
}
.proj-stats {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
}
.stat-box {
    background: #f9fafb;
    border-radius: 12px;
    padding: 10px 12px;
    text-align: center;
}
.stat-value {
    font-size: 1.05rem;
    font-weight: 800;
    color: #111827;
    display: block;
}
.stat-label {
    font-size: .68rem;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.stat-box.accent .stat-value { color: #2563eb; }
.stat-box.danger  .stat-value { color: #dc2626; }

.proj-progress {
    background: #f3f4f6;
    border-radius: 100px;
    height: 6px;
    overflow: hidden;
}
.proj-progress-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, #2563eb, #7c3aed);
    transition: width .6s ease;
}

.proj-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.btn-proj {
    flex: 1;
    min-width: 90px;
    text-align: center;
    border-radius: 10px;
    padding: .5rem .8rem;
    font-size: .78rem;
    font-weight: 700;
    text-decoration: none;
    transition: all .2s;
    border: 1.5px solid;
}
.btn-proj-view    { border-color: #111827; color: #111827; background: transparent; }
.btn-proj-view:hover { background: #111827; color: #fff; }
.btn-proj-budget  { border-color: #2563eb; color: #2563eb; background: transparent; }
.btn-proj-budget:hover { background: #2563eb; color: #fff; }
.btn-proj-finance { border-color: #059669; color: #059669; background: transparent; }
.btn-proj-finance:hover { background: #059669; color: #fff; }

/* Estado vacío */
.empty-state {
    text-align: center;
    padding: 80px 40px;
    color: #9ca3af;
}
.empty-state i { font-size: 4rem; margin-bottom: 20px; color: #d1d5db; }
.empty-state h3 { font-size: 1.4rem; font-weight: 700; color: #374151; margin-bottom: 10px; }
</style>

<div class="dash-header">
    <div>
        <h1 class="dash-title">Mis Proyectos</h1>
        <p class="dash-subtitle">Gestiona todos tus proyectos y presupuestos desde aquí</p>
    </div>
    <a href="{{ route('proyectos.create') }}" class="btn-nuevo-proyecto" id="btn-nuevo-proyecto">
        <i class="bi bi-plus-lg"></i> Nuevo Proyecto
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($proyectos->isEmpty())
    <div class="empty-state">
        <i class="bi bi-folder2-open"></i>
        <h3>Sin proyectos todavía</h3>
        <p>Crea tu primer proyecto para comenzar a gestionar presupuestos y costos.</p>
        <a href="{{ route('proyectos.create') }}" class="btn-nuevo-proyecto" style="display:inline-flex;margin-top:16px;">
            <i class="bi bi-plus-lg"></i> Crear primer proyecto
        </a>
    </div>
@else
<div class="proyectos-grid">
    @foreach($proyectos as $proyecto)
    @php
        $estado = $proyecto->estado->nombre ?? 'Sin estado';
        $estadoClass = match(mb_strtolower($estado)) {
            'activo', 'en curso', 'en proceso' => 'badge-activo',
            'pausado', 'suspendido'             => 'badge-pausado',
            'terminado', 'finalizado'           => 'badge-terminado',
            default                              => 'badge-default',
        };

        $fechaInicio = $proyecto->fecha_inicio ? \Carbon\Carbon::parse($proyecto->fecha_inicio) : null;
        $hoy         = now();
        $duracion    = (int) ($proyecto->duracion_estimada ?? 0);

        $diasTranscurridos = $fechaInicio ? (int) $fechaInicio->diffInDays($hoy, false) : 0;
        $diasRestantes     = ($duracion > 0 && $fechaInicio)
                             ? $duracion - $diasTranscurridos
                             : null;
        $progreso = ($duracion > 0 && $diasTranscurridos > 0)
                    ? min(100, round(($diasTranscurridos / $duracion) * 100))
                    : 0;

        $totalPresupuestado = $proyecto->presupuestos->sum('total');
        $numPresupuestos    = $proyecto->presupuestos->count();
    @endphp

    <div class="proyecto-card" id="proyecto-card-{{ $proyecto->id }}">
        <!-- Estado + fecha -->
        <div class="proj-status-bar">
            <span class="badge-estado {{ $estadoClass }}">{{ $estado }}</span>
            @if($fechaInicio)
                <small style="color:#9ca3af;font-size:.75rem;">
                    <i class="bi bi-calendar3 me-1"></i>{{ $fechaInicio->format('d/m/Y') }}
                </small>
            @endif
        </div>

        <!-- Nombre y cliente -->
        <div>
            <h3 class="proj-nombre">{{ $proyecto->nombre }}</h3>
            <p class="proj-cliente">
                <i class="bi bi-person-fill"></i>
                {{ $proyecto->cliente->nombre ?? '— Sin cliente —' }}
            </p>
            @if($proyecto->ubicacion)
                <p class="proj-cliente" style="margin-top:2px;">
                    <i class="bi bi-geo-alt-fill"></i>
                    {{ $proyecto->ubicacion }}
                </p>
            @endif
        </div>

        <!-- Estadísticas rápidas -->
        <div class="proj-stats">
            <div class="stat-box accent">
                <span class="stat-value">${{ number_format($totalPresupuestado, 0) }}</span>
                <span class="stat-label">Presupuestado</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $numPresupuestos }}</span>
                <span class="stat-label">Presupuesto{{ $numPresupuestos !== 1 ? 's' : '' }}</span>
            </div>
            <div class="stat-box {{ ($diasRestantes !== null && $diasRestantes < 0) ? 'danger' : '' }}">
                <span class="stat-value">
                    @if($diasRestantes !== null)
                        {{ $diasRestantes > 0 ? $diasRestantes : abs($diasRestantes) }}
                        {{ $diasRestantes < 0 ? '⚠' : '' }}
                    @else
                        —
                    @endif
                </span>
                <span class="stat-label">{{ $diasRestantes !== null ? ($diasRestantes < 0 ? 'días vencido' : 'días rest.') : 'sin fecha' }}</span>
            </div>
        </div>

        <!-- Barra de progreso -->
        @if($duracion > 0)
        <div>
            <div style="display:flex;justify-content:space-between;font-size:.72rem;color:#9ca3af;margin-bottom:5px;">
                <span>Progreso temporal</span>
                <span>{{ $progreso }}%</span>
            </div>
            <div class="proj-progress">
                <div class="proj-progress-fill" style="width:{{ $progreso }}%"></div>
            </div>
        </div>
        @endif

        <!-- Acciones -->
        <div class="proj-actions">
            <a href="{{ route('proyectos.show', $proyecto->id) }}" class="btn-proj btn-proj-view" id="btn-ver-{{ $proyecto->id }}">
                <i class="bi bi-eye me-1"></i>Ver
            </a>
            <a href="{{ route('presupuestos.create', ['proyecto_id' => $proyecto->id]) }}" class="btn-proj btn-proj-budget" id="btn-presup-{{ $proyecto->id }}">
                <i class="bi bi-file-earmark-text me-1"></i>Presupuesto
            </a>
            <a href="{{ route('cajas_chicas.index', ['proyecto_id' => $proyecto->id]) }}" class="btn-proj btn-proj-finance" id="btn-finanzas-{{ $proyecto->id }}">
                <i class="bi bi-wallet2 me-1"></i>Finanzas
            </a>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection