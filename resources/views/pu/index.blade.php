@extends('layout')

@section('title','Análisis P.U.')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .dash-index-view{ min-height:100%; background:#f8f8f8; font-family:"Garamond","Baskerville",serif; color:#111; padding:20px; }
    .index-panel{ background:#fff; padding:40px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); }
    .header-section{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:30px; display:flex; justify-content:space-between; align-items:flex-end; gap:16px; flex-wrap:wrap; }
    .header-section h1{ font-size:2.2rem; font-weight:700; margin:0; }
    .header-section p{ margin:6px 0 0; color:#666; font-family:Arial,sans-serif; font-size:.92rem; }
    .header-actions{ display:flex; gap:10px; flex-wrap:wrap; }
    .btn-filter{ background:#fff; color:#111; border:1px solid #d9d9d9; padding:10px 16px; border-radius:6px; font-size:.8rem; letter-spacing:1px; text-transform:uppercase; font-family:Arial,sans-serif; }
    .btn-add-new{ background:#111; color:#fff; border:none; padding:10px 20px; border-radius:6px; font-size:.8rem; letter-spacing:1px; text-transform:uppercase; text-decoration:none; font-family:Arial,sans-serif; }
    .btn-add-new:hover{ background:#333; color:#fff; }
    .project-table{ width:100%; border-collapse:separate; border-spacing:0 12px; }
    .project-table thead th{ text-align:left; color:#888; font-size:.75rem; letter-spacing:2px; text-transform:uppercase; padding:0 20px 10px; font-family:Arial,sans-serif; }
    .project-row{ background:#fff; outline:1px solid #eee; transition:all .3s ease; }
    .project-row:hover{ transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,.05); }
    .project-row td{ padding:15px 20px; vertical-align:middle; }
    .title-main{ font-weight:700; font-size:1.05rem; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .badge-dark-mini{ font-family:Arial,sans-serif; font-size:.7rem; font-weight:700; padding:2px 8px; border-radius:6px; background:#111; color:#fff; }
    .desc-text{ color:#666; font-size:.85rem; line-height:1.4; margin-top:5px; font-family:Arial,sans-serif; }
    .info-stack{ font-family:Arial,sans-serif; font-size:.9rem; color:#555; line-height:1.55; }
    .action-cell{ text-align:right; white-space:nowrap; }
    .btn-icon-action{ background:none; border:none; font-size:1.15rem; cursor:pointer; transition:transform .2s ease, color .3s ease; padding:5px; margin-left:10px; display:inline-flex; align-items:center; justify-content:center; color:#888; text-decoration:none; }
    .btn-icon-action:hover{ transform:scale(1.15); color:#111; }
    .empty-state{ text-align:center; padding:28px 18px !important; color:#777; font-style:italic; font-family:Arial,sans-serif; background:#fff; }
</style>

<div class="dash-index-view">
    <div class="index-panel">
        <div class="header-section">
            <div>
                <h1>Análisis P.U.</h1>
                <p>Listado general de análisis de precios unitarios registrados.</p>
            </div>

            <div class="header-actions">
                <button class="btn-filter" type="button">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>

                <a href="{{ route('analisis_pu.create') }}" class="btn-add-new">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Análisis
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="font-family:Arial,sans-serif;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="project-table">
                <thead>
                    <tr>
                        <th>Detalle del análisis</th>
                        <th>Información</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($analisis ?? []) as $item)
                        <tr class="project-row">
                            <td>
                                <div class="title-main">
                                    {{ $item->concepto->clave ?? 'Sin clave' }}
                                    <span class="badge-dark-mini">A.P.U.</span>
                                </div>

                                <div class="desc-text">
                                    {{ $item->concepto->descripcion ?? 'Sin descripción' }}
                                </div>
                            </td>

                            <td>
                                <div class="info-stack">
                                    <div><strong>Proyecto:</strong> {{ $item->proyecto->nombre ?? 'Sin proyecto' }}</div>
                                    <div><strong>Concepto:</strong> {{ $item->concepto->clave ?? 'N/D' }}</div>
                                    <div><strong>Observaciones:</strong> {{ $item->observaciones ?? 'Sin observaciones' }}</div>
                                    <div><strong>Fecha:</strong> {{ optional($item->created_at)->format('Y-m-d H:i') ?? 'N/D' }}</div>
                                </div>
                            </td>

                            <td class="action-cell">
                                <a href="{{ route('analisis_pu.show', $item->id) }}" class="btn-icon-action" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('analisis_pu.edit', $item->id) }}" class="btn-icon-action" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('analisis_pu.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este análisis?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-action" title="Eliminar">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="project-row">
                            <td colspan="3" class="empty-state">No hay análisis P.U. registrados por el momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection