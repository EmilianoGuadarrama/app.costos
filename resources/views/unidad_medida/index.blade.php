@extends('layout')

@section('title','Unidad de Medida')

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
        .title-main{ font-weight:700; font-size:1.05rem; display:flex; align-items:center; gap:8px; }
        .badge-dark-mini{ font-family:Arial,sans-serif; font-size:.7rem; font-weight:700; padding:2px 8px; border-radius:6px; background:#111; color:#fff; }
        .badge-soft{ display:inline-flex; align-items:center; justify-content:center; padding:4px 10px; border-radius:12px; background:#eee; color:#333; font-family:Arial,sans-serif; font-size:.75rem; font-weight:700; }
        .desc-text{ color:#666; font-size:.85rem; line-height:1.4; margin-top:5px; font-family:Arial,sans-serif; }
        .info-stack{ font-family:Arial,sans-serif; font-size:.9rem; color:#555; line-height:1.55; }
        .action-cell{ text-align:right; white-space:nowrap; }
        .btn-icon-action{ background:none; border:none; font-size:1.15rem; cursor:pointer; transition:transform .2s ease, color .3s ease; padding:5px; margin-left:10px; display:inline-flex; align-items:center; justify-content:center; color:#888; text-decoration:none; }
        .btn-icon-action:hover{ transform:scale(1.15); color:#111; }
        .empty-state{ text-align:center; padding:28px 18px !important; color:#777; font-style:italic; font-family:Arial,sans-serif; background:#fff; }
        @media (max-width: 992px){ .index-panel{ padding:24px; } .project-table{ min-width:900px; } }
    </style>

    <div class="dash-index-view">
        <div class="index-panel">
            <div class="header-section">
                <div>
                    <h1>Unidad de Medida</h1>
                    <p>Catálogo general de unidades registradas en el sistema.</p>
                </div>

                <div class="header-actions">
                    <button class="btn-filter" type="button">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>

                    <a href="{{ Route::has('unidad_medida.create') ? route('unidad_medida.create') : '#' }}" class="btn-add-new">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Unidad
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="project-table">
                    <thead>
                    <tr>
                        <th style="width:34%;">Unidad</th>
                        <th>Descripción</th>
                        <th>ID</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse(($unidades ?? []) as $unidad)
                        @php $unidadId = $unidad->id_unidad ?? $unidad->id ?? 1; @endphp
                        <tr class="project-row">
                            <td>
                                <div class="title-main">
                                    {{ $unidad->nombre ?? 'Unidad sin nombre' }}
                                    <span class="badge-dark-mini">#{{ $unidadId }}</span>
                                </div>
                                <div class="desc-text">Registro de catálogo para uso en conceptos, materiales y análisis.</div>
                            </td>
                            <td>
                                <div class="info-stack">
                                    {{ $unidad->descripcion ?? 'Sin descripción registrada.' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge-soft">ID {{ $unidadId }}</span>
                            </td>
                            <td class="action-cell">
                                <a href="{{ Route::has('unidad_medida.show') ? route('unidad_medida.show', $unidadId) : '#' }}" class="btn-icon-action" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ Route::has('unidad_medida.edit') ? route('unidad_medida.edit', $unidadId) : '#' }}" class="btn-icon-action" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @if(Route::has('unidad_medida.destroy'))
                                    <form action="{{ route('unidad_medida.destroy', $unidadId) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta unidad de medida?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-action" title="Eliminar">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn-icon-action" title="Eliminar">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="project-row">
                            <td colspan="4" class="empty-state">No hay unidades de medida registradas.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
