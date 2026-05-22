@extends('layout')
@section('title', 'Mis Obras — App Costos')
@section('content')
<style>
.dash-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:32px; }
.dash-title { font-size:1.85rem; font-weight:800; color:#111827; margin:0; }
.dash-subtitle { color:#6b7280; font-size:.95rem; margin:4px 0 0; }
.btn-nueva-obra {
    background:#111827; color:#fff; border:none; border-radius:12px;
    padding:.75rem 1.4rem; font-weight:700; font-size:.9rem;
    text-decoration:none; display:inline-flex; align-items:center; gap:8px;
    transition:background .2s;
}
.btn-nueva-obra:hover { background:#374151; color:#fff; }

/* Grid de tarjetas */
.obras-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(340px,1fr)); gap:24px; }
.obra-card {
    background:#fff; border:1px solid #e5e7eb; border-radius:20px;
    padding:24px; box-shadow:0 4px 16px rgba(0,0,0,.05);
    transition:box-shadow .25s, transform .25s; display:flex; flex-direction:column; gap:14px;
}
.obra-card:hover { box-shadow:0 12px 32px rgba(0,0,0,.1); transform:translateY(-2px); }

.obra-nombre { font-size:1.2rem; font-weight:800; color:#111827; margin:0; }
.obra-direccion { font-size:.85rem; color:#6b7280; display:flex; align-items:center; gap:5px; }
.obra-encargado { font-size:.85rem; color:#374151; display:flex; align-items:center; gap:5px; }

.obra-stats { display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px; }
.stat-box { background:#f9fafb; border-radius:12px; padding:10px 12px; text-align:center; }
.stat-value { font-size:1.05rem; font-weight:800; color:#111827; display:block; }
.stat-label { font-size:.68rem; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px; }
.stat-box.azul .stat-value { color:#2563eb; }
.stat-box.rojo .stat-value { color:#dc2626; }

.obra-barra-wrap { }
.obra-barra-labels { display:flex; justify-content:space-between; font-size:.72rem; color:#9ca3af; margin-bottom:4px; }
.obra-barra { background:#f3f4f6; border-radius:100px; height:6px; overflow:hidden; }
.obra-barra-fill { height:100%; border-radius:100px; background:linear-gradient(90deg,#2563eb,#7c3aed); }

.obra-acciones { display:flex; gap:8px; flex-wrap:wrap; }
.btn-obra {
    flex:1; min-width:80px; text-align:center; border-radius:10px;
    padding:.5rem .8rem; font-size:.78rem; font-weight:700;
    text-decoration:none; transition:all .2s; border:1.5px solid;
}
.btn-ver    { border-color:#111827; color:#111827; }
.btn-ver:hover    { background:#111827; color:#fff; }
.btn-presup { border-color:#2563eb; color:#2563eb; }
.btn-presup:hover { background:#2563eb; color:#fff; }
.btn-caja   { border-color:#059669; color:#059669; }
.btn-caja:hover   { background:#059669; color:#fff; }

.empty-state { text-align:center; padding:80px 40px; color:#9ca3af; }
.empty-state i { font-size:4rem; color:#d1d5db; display:block; margin-bottom:20px; }
.empty-state h3 { font-size:1.4rem; font-weight:700; color:#374151; margin-bottom:10px; }
</style>

<div class="dash-header">
    <div>
        <h1 class="dash-title">Mis Obras</h1>
        <p class="dash-subtitle">Gestión de obras activas y sus presupuestos</p>
    </div>
    <a href="{{ route('obras.create') }}" class="btn-nueva-obra" id="btn-nueva-obra">
        <i class="bi bi-plus-lg"></i> Nueva Obra
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($obras->isEmpty())
    <div class="empty-state">
        <i class="bi bi-building"></i>
        <h3>Sin obras registradas</h3>
        <p>Crea tu primera obra para comenzar a gestionar presupuestos.</p>
        <a href="{{ route('obras.create') }}" class="btn-nueva-obra" style="display:inline-flex;margin-top:16px;">
            <i class="bi bi-plus-lg"></i> Crear primera obra
        </a>
    </div>
@else
<div class="obras-grid">
    @foreach($obras as $obra)
    @php
        $datos       = $obra->datosDeObra;
        $encargado   = $obra->encargado?->persona;
        $duracion    = (int) $obra->duracion;
        $diasTrans   = $obra->dias_transcurridos;
        $diasFaltan  = $obra->dias_faltan;
        $progreso    = ($duracion > 0 && $diasTrans > 0) ? min(100, round(($diasTrans/$duracion)*100)) : 0;
        $totalPres   = $obra->totalObra?->total_final ?? $obra->total_presupuestado ?? 0;
        $caja        = $obra->cajaGeneral;
        $saldoCaja   = $caja ? ((float)$caja->ingresos_totales - (float)$caja->egresos_totales) : null;
    @endphp
    <div class="obra-card" id="obra-card-{{ $obra->id }}">
        <div>
            <h3 class="obra-nombre">{{ $datos?->nombre ?? "Obra #$obra->id" }}</h3>
            @if($datos?->descripcion)
                <p style="font-size:.82rem;color:#6b7280;margin:4px 0 0;">{{ Str::limit($datos->descripcion,80) }}</p>
            @endif
        </div>

        @if($encargado)
        <div class="obra-encargado">
            <i class="bi bi-person-fill" style="color:#2563eb;"></i>
            <span>{{ $encargado->nombre }} {{ $encargado->apellido_paterno }}</span>
            @if($obra->encargado->rol)
                <span style="color:#9ca3af;">· {{ $obra->encargado->rol }}</span>
            @endif
        </div>
        @endif

        @if($obra->fecha_inicio)
        <div class="obra-direccion">
            <i class="bi bi-calendar3"></i>
            <span>Inicio: {{ $obra->fecha_inicio->format('d/m/Y') }}</span>
            @if($obra->duracion)
                <span style="color:#9ca3af;">· {{ $obra->duracion }} días</span>
            @endif
        </div>
        @endif

        <div class="obra-stats">
            <div class="stat-box azul">
                <span class="stat-value">${{ number_format($totalPres,0) }}</span>
                <span class="stat-label">Presupuestado</span>
            </div>
            <div class="stat-box {{ $diasFaltan !== null && $diasFaltan < 0 ? 'rojo' : '' }}">
                <span class="stat-value">{{ $diasFaltan ?? '—' }}</span>
                <span class="stat-label">{{ $diasFaltan < 0 ? 'Vencido' : 'Días rest.' }}</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $datos?->num_niveles ?? '—' }}</span>
                <span class="stat-label">Niveles</span>
            </div>
        </div>

        @if($duracion > 0)
        <div class="obra-barra-wrap">
            <div class="obra-barra-labels">
                <span>Progreso temporal</span><span>{{ $progreso }}%</span>
            </div>
            <div class="obra-barra">
                <div class="obra-barra-fill" style="width:{{ $progreso }}%"></div>
            </div>
        </div>
        @endif

        <div class="obra-acciones">
            <a href="{{ route('obras.show', $obra->id) }}" class="btn-obra btn-ver" id="btn-ver-{{ $obra->id }}">
                <i class="bi bi-eye me-1"></i>Ver
            </a>
            @php
                $tieneRenglones = $obra->asignaConceptos()->exists()
                               || $obra->asignaMateriales()->exists()
                               || $obra->asignaMaquinaria()->exists();
            @endphp
            @if($tieneRenglones)
            <a href="{{ route('obras.presupuesto', $obra->id) }}" class="btn-obra btn-presup" id="btn-presup-{{ $obra->id }}">
                <i class="bi bi-file-earmark-text me-1"></i>Presupuesto
            </a>
            @else
            <button type="button" class="btn-obra btn-presup" id="btn-presup-{{ $obra->id }}"
                    onclick="alertaSinPresup({{ $obra->id }}, '{{ addslashes($datos?->nombre ?? 'Obra #'.$obra->id) }}')"
                    style="cursor:pointer;border:none;text-align:center;">
                <i class="bi bi-file-earmark-text me-1"></i>Presupuesto
            </button>
            @endif
            @if($caja)
            <a href="{{ route('caja_general.show', $caja->id) }}" class="btn-obra btn-caja" id="btn-caja-{{ $obra->id }}">
                <i class="bi bi-wallet2 me-1"></i>Caja
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Modal Presupuesto Vacío -->
<div id="modalSinPresup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(17,24,39,0.7); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; text-align:center; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
        <div style="width:64px; height:64px; background:#fef3c7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
            <i class="bi bi-file-earmark-x" style="font-size:2rem; color:#d97706;"></i>
        </div>
        <h3 style="font-size:1.2rem; font-weight:800; color:#111; margin-bottom:8px;">Presupuesto Vacío</h3>
        <p style="font-size:.9rem; color:#4b5563; margin-bottom:24px;">La obra <strong id="modalObraNombre"></strong> aún no tiene renglones en su presupuesto.</p>
        
        <div style="display:flex; flex-direction:column; gap:10px;">
            <a href="#" id="modalBtnAgregar" class="btn btn-primary" style="background:#2563eb; border:none; border-radius:10px; padding:10px; font-weight:700;">
                <i class="bi bi-plus-lg me-1"></i> Agregar Renglones
            </a>
            <button type="button" onclick="cerrarAlertaPresup()" style="background:transparent; border:1.5px solid #e5e7eb; border-radius:10px; padding:10px; color:#4b5563; font-weight:600; cursor:pointer;">
                Cancelar
            </button>
        </div>
    </div>
</div>

<script>
function alertaSinPresup(obraId, nombreObra) {
    document.getElementById('modalObraNombre').textContent = nombreObra;
    document.getElementById('modalBtnAgregar').href = `/obras/${obraId}/presupuesto/agregar`;
    document.getElementById('modalSinPresup').style.display = 'flex';
}
function cerrarAlertaPresup() {
    document.getElementById('modalSinPresup').style.display = 'none';
}
</script>
@endsection
