@extends('layout')

@section('title', 'Papelera de Obras')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
/* Estilos similares al index pero adaptados a papelera */
.dash-header { display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:20px; border-bottom:1px solid #e5e7eb; padding-bottom:15px; }
.dash-title { font-family:"Garamond", serif; font-size:2rem; font-weight:700; margin:0; color:#111827; }
.dash-subtitle { margin:5px 0 0; color:#6b7280; font-size:.9rem; }
.btn-back { background:#f3f4f6; color:#374151; text-decoration:none; padding:8px 16px; border-radius:8px; font-weight:600; font-size:.85rem; border:1px solid #e5e7eb; transition:all .2s; }
.btn-back:hover { background:#e5e7eb; color:#111827; }

.obra-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px; }
.obra-card { background:#fff; border-radius:16px; padding:20px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); border:1px solid #fecaca; transition:all .3s ease; display:flex; flex-direction:column; justify-content:space-between; }
.obra-card:hover { transform:translateY(-2px); box-shadow:0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); border-color:#fca5a5; }

.obra-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:15px; }
.obra-title { font-size:1.1rem; font-weight:800; color:#111827; margin:0 0 5px; line-height:1.3; }
.obra-cliente { font-size:.8rem; color:#6b7280; margin:0; display:flex; align-items:center; gap:5px; }
.obra-status { font-size:.7rem; font-weight:700; padding:4px 10px; border-radius:12px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; display:inline-flex; align-items:center; gap:4px; }

.obra-info { background:#f9fafb; border-radius:10px; padding:12px; margin-bottom:15px; font-size:.8rem; color:#4b5563; }
.obra-info p { margin:0 0 6px; display:flex; align-items:center; gap:6px; }
.obra-info p:last-child { margin:0; }
.obra-info i { color:#9ca3af; }

.obra-acciones { display:flex; gap:8px; flex-wrap:wrap; }
.btn-obra { flex:1; min-width:80px; text-align:center; border-radius:10px; padding:.5rem .8rem; font-size:.78rem; font-weight:700; text-decoration:none; transition:all .2s; border:1.5px solid; background:transparent; cursor:pointer; }
.btn-restaurar { border-color:#059669; color:#059669; }
.btn-restaurar:hover { background:#059669; color:#fff; }
.btn-eliminar { border-color:#dc2626; color:#dc2626; }
.btn-eliminar:hover { background:#dc2626; color:#fff; }

.empty-state { text-align:center; padding:80px 40px; color:#9ca3af; }
.empty-state i { font-size:4rem; color:#fecaca; display:block; margin-bottom:20px; }
.empty-state h3 { font-size:1.4rem; font-weight:700; color:#374151; margin-bottom:10px; }
</style>

<div class="dash-header">
    <div>
        <h1 class="dash-title"><i class="bi bi-trash3" style="color:#dc2626; margin-right:8px;"></i>Papelera de Reciclaje</h1>
        <p class="dash-subtitle">Obras eliminadas recientemente. Se borrarán definitivamente después de 30 días.</p>
    </div>
    <a href="{{ route('obras.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Volver a mis Obras
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($obras->isEmpty())
    <div class="empty-state">
        <i class="bi bi-trash"></i>
        <h3>La papelera está vacía</h3>
        <p>No hay obras eliminadas recientemente.</p>
    </div>
@else
    <div class="obra-grid">
        @foreach($obras as $obra)
            <div class="obra-card">
                <div>
                    <div class="obra-header">
                        <div>
                            <h3 class="obra-title">{{ $obra->datosDeObra?->nombre ?? 'Obra sin nombre' }}</h3>
                            <p class="obra-cliente">
                                <i class="bi bi-person-fill"></i> 
                                {{ $obra->cliente?->persona?->nombre_completo ?? 'Sin cliente asignado' }}
                            </p>
                        </div>
                        <span class="obra-status">
                            <i class="bi bi-clock-history"></i> Eliminada
                        </span>
                    </div>

                    <div class="obra-info">
                        <p><i class="bi bi-calendar-x"></i> <strong>Fecha de eliminación:</strong> {{ $obra->deleted_at->format('d/m/Y') }}</p>
                        <p><i class="bi bi-exclamation-triangle"></i> <strong>Se borrará el:</strong> {{ $obra->deleted_at->addDays(30)->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="obra-acciones">
                    <form action="{{ route('obras.restaurar', $obra->id) }}" method="POST" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn-obra btn-restaurar" style="width:100%;">
                            <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                        </button>
                    </form>
                    
                    <button type="button" class="btn-obra btn-eliminar" data-bs-toggle="modal" data-bs-target="#modalForceDelete{{ $obra->id }}">
                        <i class="bi bi-x-circle"></i> Destruir
                    </button>
                </div>
            </div>

            <!-- Modal Confirmar Eliminación Permanente -->
            <div class="modal fade" id="modalForceDelete{{ $obra->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border:none; border-radius:16px;">
                        <div class="modal-header" style="background:#fef2f2; border-bottom:1px solid #fecaca; border-radius:16px 16px 0 0;">
                            <h5 class="modal-title" style="color:#dc2626; font-weight:700;">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Permanentemente
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="padding:24px;">
                            <p>¿Estás completamente seguro de querer eliminar la obra <strong>{{ $obra->datosDeObra?->nombre }}</strong>?</p>
                            <p style="color:#dc2626; font-size:.9rem; margin-bottom:0;">Esta acción es irreversible y se perderán todos los datos asociados al presupuesto de esta obra.</p>
                        </div>
                        <div class="modal-footer" style="border-top:1px solid #f3f4f6;">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px; font-weight:600;">Cancelar</button>
                            <form action="{{ route('obras.forceDelete', $obra->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="border-radius:10px; font-weight:600; background:#dc2626;">Sí, destruir obra</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
