@extends('layout')
@section('title','Cajas Chicas')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .dash-index-view{ min-height:100%; background:#f8f8f8; font-family:"Garamond","Baskerville",serif; color:#111; padding:20px; }
    .index-panel{ background:#fff; padding:40px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); }
    .header-section{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:30px; display:flex; justify-content:space-between; align-items:flex-end; gap:16px; flex-wrap:wrap; }
    .header-section h1{ font-size:2.2rem; font-weight:700; margin:0; }
    .header-section p{ margin:6px 0 0; color:#666; font-family:Arial,sans-serif; font-size:.92rem; }
    .header-actions{ display:flex; gap:10px; flex-wrap:wrap; }
    .btn-add-new{ background:#111; color:#fff; border:none; padding:10px 20px; border-radius:6px; font-size:.8rem; letter-spacing:1px; text-transform:uppercase; text-decoration:none; font-family:Arial,sans-serif; cursor:pointer; transition:background .3s ease; }
    .btn-add-new:hover{ background:#333; color:#fff; }
    .project-table{ width:100%; border-collapse:separate; border-spacing:0 12px; }
    .project-table thead th{ text-align:left; color:#888; font-size:.75rem; letter-spacing:2px; text-transform:uppercase; padding:0 20px 10px; font-family:Arial,sans-serif; }
    .project-row{ background:#fff; outline:1px solid #eee; transition:all .3s ease; }
    .project-row:hover{ transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,.05); }
    .project-row td{ padding:15px 20px; vertical-align:middle; font-family:Arial,sans-serif; }
    .action-cell{ text-align:right; white-space:nowrap; }
    .btn-icon-action{ background:none; border:none; font-size:1.15rem; cursor:pointer; transition:transform .2s ease, color .3s ease; padding:5px; margin-left:10px; display:inline-flex; align-items:center; justify-content:center; color:#888; text-decoration:none; }
    .btn-icon-action:hover{ transform:scale(1.15); color:#111; }
    .empty-state{ text-align:center; padding:28px 18px !important; color:#777; font-style:italic; font-family:Arial,sans-serif; background:#fff; }
    .text-success{ color:#198754; } .text-danger{ color:#dc3545; }
    .modal-content{ border:none; border-radius:16px; box-shadow:0 20px 50px rgba(0,0,0,.15); }
    .modal-header{ border-bottom:1px solid #ececec; padding:18px 22px; }
    .modal-title{ font-family:"Garamond","Baskerville",serif; font-size:1.45rem; font-weight:700; color:#111; }
    .modal-body{ padding:22px; font-family:Arial,sans-serif; color:#444; }
    .modal-footer{ border-top:1px solid #ececec; padding:16px 22px; }
    .detail-grid{ display:grid; grid-template-columns:1fr 1fr; gap:18px; }
    .detail-box{ background:#fafafa; border:1px solid #ececec; border-radius:12px; padding:16px; }
    .detail-box h6{ font-size:.82rem; text-transform:uppercase; letter-spacing:1.5px; color:#777; margin-bottom:10px; font-weight:700; }
    .detail-box p{ margin:0 0 8px; line-height:1.5; font-size:.95rem; }
    .detail-box strong{ color:#111; }
    .btn-modal-dark{ background:#111; color:#fff; border:none; border-radius:8px; padding:9px 18px; font-family:Arial,sans-serif; font-size:.82rem; letter-spacing:1px; text-transform:uppercase; text-decoration:none; }
    .btn-modal-dark:hover{ background:#333; color:#fff; }
    .btn-modal-light{ background:#fff; color:#111; border:1px solid #d9d9d9; border-radius:8px; padding:9px 18px; font-family:Arial,sans-serif; font-size:.82rem; letter-spacing:1px; text-transform:uppercase; }
    .btn-modal-danger{ background:#b91c1c; color:#fff; border:none; border-radius:8px; padding:9px 18px; font-family:Arial,sans-serif; font-size:.82rem; letter-spacing:1px; text-transform:uppercase; }
    .btn-modal-danger:hover{ background:#991b1b; color:#fff; }
    @media(max-width:992px){ .index-panel{ padding:24px; } .detail-grid{ grid-template-columns:1fr; } }
</style>
<div class="dash-index-view">
    <div class="index-panel">
        @if(session('success'))
            <div class="alert alert-dark mb-4" style="font-family:Arial;font-size:.85rem;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-4" style="font-family:Arial;font-size:.85rem;"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <div class="header-section">
            <div><h1>Cajas Chicas</h1><p>Gestión de fondos de caja chica asignados a proyectos.</p></div>
            <div class="header-actions"><a href="{{ route('cajas_chicas.create') }}" class="btn-add-new"><i class="bi bi-plus-circle me-1"></i> Nueva Caja</a></div>
        </div>
        <div class="table-responsive">
            <table class="project-table">
                <thead><tr><th>Nombre</th><th>Proyecto / Responsable</th><th>Inicial</th><th>Saldo Actual</th><th style="text-align:right;">Acciones</th></tr></thead>
                <tbody>
                @forelse($cajas as $caja)
                    @php $cajaId = $caja->id; @endphp
                    <tr class="project-row">
                        <td><strong>{{ $caja->nombre }}</strong></td>
                        <td><i class="bi bi-briefcase text-muted"></i> {{ $caja->proyecto->nombre ?? 'N/A' }}<br><i class="bi bi-person text-muted"></i> {{ $caja->responsable->nombre ?? 'N/A' }}</td>
                        <td>${{ number_format($caja->monto_inicial, 2) }}</td>
                        <td><strong class="{{ $caja->saldo_actual > 0 ? 'text-success' : 'text-danger' }}">${{ number_format($caja->saldo_actual, 2) }}</strong></td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon-action" title="Ver" data-bs-toggle="modal" data-bs-target="#verCajaModal{{ $cajaId }}"><i class="bi bi-eye"></i></button>
                            <button type="button" class="btn-icon-action" title="Eliminar" data-bs-toggle="modal" data-bs-target="#eliminarCajaModal{{ $cajaId }}"><i class="bi bi-trash3"></i></button>
                        </td>
                    </tr>
                    <div class="modal fade" id="verCajaModal{{ $cajaId }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">{{ $caja->nombre }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
                            <div class="modal-body"><div class="detail-grid">
                                <div class="detail-box"><h6>Información General</h6><p><strong>Nombre:</strong> {{ $caja->nombre }}</p><p><strong>Proyecto:</strong> {{ $caja->proyecto->nombre ?? 'N/A' }}</p><p><strong>Responsable:</strong> {{ $caja->responsable->nombre ?? 'N/A' }}</p></div>
                                <div class="detail-box"><h6>Montos</h6><p><strong>Monto inicial:</strong> ${{ number_format($caja->monto_inicial, 2) }}</p><p><strong>Saldo actual:</strong> ${{ number_format($caja->saldo_actual, 2) }}</p></div>
                            </div></div>
                            <div class="modal-footer"><button type="button" class="btn-modal-light" data-bs-dismiss="modal">Cerrar</button><a href="{{ route('cajas_chicas.edit', $cajaId) }}" class="btn-modal-dark">Editar</a></div>
                        </div></div>
                    </div>
                    <div class="modal fade" id="eliminarCajaModal{{ $cajaId }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">Eliminar caja chica</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
                            <div class="modal-body">¿Deseas eliminar la caja chica <strong>{{ $caja->nombre }}</strong>?<br><br>Esta acción no se puede deshacer.</div>
                            <div class="modal-footer"><button type="button" class="btn-modal-light" data-bs-dismiss="modal">Cancelar</button><form action="{{ route('cajas_chicas.destroy', $cajaId) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn-modal-danger">Eliminar</button></form></div>
                        </div></div>
                    </div>
                @empty
                    <tr class="project-row"><td colspan="5" class="empty-state">No hay cajas chicas registradas por el momento.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
