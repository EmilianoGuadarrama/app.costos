@extends('layout')
@section('title', 'Obras en Proceso')

@section('content')
<style>
.pu-hdr {
    display: flex; justify-content: space-between; align-items: center;
    background: #fff; padding: 20px 25px; border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 25px;
}
.pu-hdr h1 { margin: 0; font-size: 1.6rem; font-weight: 800; color: #111; }
.card-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;
}
.obra-card {
    background: #fff; border-radius: 12px; padding: 20px;
    border: 1px solid #e5e7eb; box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    transition: 0.2s;
    display: flex; flex-direction: column;
}
.obra-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); border-color: #cbd5e1; }
.o-title { font-size: 1.15rem; font-weight: 800; color: #111; margin-bottom: 5px; }
.o-estado { 
    display: inline-block; padding: 4px 10px; border-radius: 20px; 
    font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
    margin-bottom: 15px;
}
.st-en_curso { background: #dbeafe; color: #1d4ed8; }
.st-pausada { background: #fef3c7; color: #b45309; }
.st-finalizada { background: #dcfce7; color: #15803d; }
.o-stat { display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 8px; color: #4b5563; }
.o-stat strong { color: #111; }
.progress-container { width: 100%; background: #f3f4f6; border-radius: 6px; height: 8px; margin-top: 5px; overflow: hidden; }
.progress-bar { height: 100%; background: #3b82f6; }
.btn-view {
    margin-top: auto; padding-top: 15px; text-align: center;
    border-top: 1px solid #f3f4f6; margin-top: 15px;
}
.btn-view a {
    display: inline-block; width: 100%; background: #111; color: #fff;
    padding: 8px; border-radius: 8px; font-size: 0.9rem; font-weight: 700;
    text-decoration: none; transition: 0.2s;
}
.btn-view a:hover { background: #374151; }
</style>

<div class="pu-hdr">
    <div>
        <h1><i class="bi bi-cone-striped me-2 text-primary"></i> Obras en Proceso</h1>
        <p class="text-muted mb-0 mt-1" style="font-size: 0.9rem;">Gestión y avance de proyectos aprobados.</p>
    </div>
</div>

<div class="card-grid">
    @forelse($obrasProceso as $p)
        @php
            $obra = $p->obraIniciada;
            $nombre = $obra->datosDeObra->nombre ?? 'Obra #' . $obra->id;
            $diasText = $p->estado == 'pausada' ? 'Pausado' : round($p->dias_transcurridos) . ' / ' . $obra->duracion . ' días';
        @endphp
        <div class="obra-card">
            <div class="o-title">{{ $nombre }}</div>
            <div>
                <span class="o-estado st-{{ $p->estado }}">
                    <i class="bi bi-record-circle-fill me-1"></i> 
                    {{ str_replace('_', ' ', $p->estado) }}
                </span>
            </div>
            
            <div class="o-stat">
                <span>Progreso Físico:</span>
                <strong>{{ $p->porcentaje_avanzado }}%</strong>
            </div>
            <div class="progress-container mb-3">
                <div class="progress-bar" style="width: {{ $p->porcentaje_avanzado }}%;"></div>
            </div>

            <div class="o-stat">
                <span>Días Transcurridos:</span>
                <strong>{{ $diasText }}</strong>
            </div>
            <div class="o-stat">
                <span>Presupuesto IVA:</span>
                <strong>{{ $p->con_iva ? 'Sí' : 'No' }}</strong>
            </div>

            <div class="btn-view">
                <a href="{{ route('obras_proceso.show', $p->id) }}">
                    <i class="bi bi-graph-up-arrow me-1"></i> Ver Dashboard
                </a>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-inboxes text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">No hay obras en proceso actualmente.</p>
            <a href="{{ route('obras.index') }}" class="btn btn-outline-primary btn-sm mt-2">Ir a Presupuestos</a>
        </div>
    @endforelse
</div>
@endsection
