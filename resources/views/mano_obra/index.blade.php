@extends('layout')

@section('title','Mano de Obra')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .dash-index-view{
        min-height:100%;
        background:#f8f8f8;
        font-family:"Garamond","Baskerville",serif;
        color:#111;
        padding:20px;
    }

    .index-panel{
        background:#fff;
        padding:40px;
        border-radius:12px;
        box-shadow:0 4px 10px rgba(0,0,0,.05);
    }

    .header-section{
        border-bottom:1px solid #eaeaea;
        padding-bottom:20px;
        margin-bottom:30px;
        display:flex;
        justify-content:space-between;
        align-items:flex-end;
        gap:16px;
        flex-wrap:wrap;
    }

    .header-section h1{
        font-size:2.2rem;
        font-weight:700;
        margin:0;
    }

    .header-section p{
        margin:6px 0 0;
        color:#666;
        font-family:Arial,sans-serif;
        font-size:.92rem;
    }

    .header-actions{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }

    .btn-filter{
        background:#fff;
        color:#111;
        border:1px solid #d9d9d9;
        padding:10px 16px;
        border-radius:6px;
        font-size:.8rem;
        letter-spacing:1px;
        text-transform:uppercase;
        font-family:Arial,sans-serif;
    }

    .btn-add-new{
        background:#111;
        color:#fff;
        border:none;
        padding:10px 20px;
        border-radius:6px;
        font-size:.8rem;
        letter-spacing:1px;
        text-transform:uppercase;
        text-decoration:none;
        font-family:Arial,sans-serif;
    }

    .btn-add-new:hover{
        background:#333;
        color:#fff;
    }

    .work-table{
        width:100%;
        border-collapse:separate;
        border-spacing:0 12px;
    }

    .work-table thead th{
        text-align:left;
        color:#888;
        font-size:.75rem;
        letter-spacing:2px;
        text-transform:uppercase;
        padding:0 20px 10px;
        font-family:Arial,sans-serif;
    }

    .work-row{
        background:#fff;
        outline:1px solid #eee;
        transition:all .3s ease;
    }

    .work-row:hover{
        transform:translateY(-2px);
        box-shadow:0 5px 15px rgba(0,0,0,.05);
    }

    .work-row td{
        padding:15px 20px;
        vertical-align:middle;
    }

    .title-main{
        font-weight:700;
        font-size:1.05rem;
        display:flex;
        align-items:center;
        gap:8px;
        flex-wrap:wrap;
    }

    .badge-dark-mini{
        font-family:Arial,sans-serif;
        font-size:.7rem;
        font-weight:700;
        padding:2px 8px;
        border-radius:6px;
        background:#111;
        color:#fff;
    }

    .badge-soft{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:5px 12px;
        border-radius:12px;
        background:#eee;
        color:#333;
        font-family:Arial,sans-serif;
        font-size:.8rem;
        font-weight:700;
    }

    .desc-text{
        color:#666;
        font-size:.85rem;
        line-height:1.4;
        margin-top:5px;
        font-family:Arial,sans-serif;
    }

    .info-stack{
        font-family:Arial,sans-serif;
        font-size:.9rem;
        color:#555;
        line-height:1.55;
    }

    .amount-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:6px 12px;
        border-radius:12px;
        background:#eee;
        color:#111;
        font-family:Arial,sans-serif;
        font-size:.82rem;
        font-weight:700;
    }

    .action-cell{
        text-align:right;
        white-space:nowrap;
    }

    .btn-icon-action{
        background:none;
        border:none;
        font-size:1.15rem;
        cursor:pointer;
        transition:transform .2s ease, color .3s ease;
        padding:5px;
        margin-left:10px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        color:#888;
        text-decoration:none;
    }

    .btn-icon-action:hover{
        transform:scale(1.15);
        color:#111;
    }

    .empty-state{
        text-align:center;
        padding:28px 18px !important;
        color:#777;
        font-style:italic;
        font-family:Arial,sans-serif;
        background:#fff;
    }

    .alert-success-custom{
        background:#ecfdf5;
        border:1px solid #bbf7d0;
        color:#166534;
        border-radius:8px;
        font-family:Arial,sans-serif;
        font-size:.9rem;
        padding:12px 16px;
        margin-bottom:20px;
    }

    @media (max-width: 992px){
        .index-panel{
            padding:24px;
        }

        .work-table{
            min-width:980px;
        }
    }
</style>

<div class="dash-index-view">
    <div class="index-panel">

        @if(session('success'))
            <div class="alert-success-custom">
                {{ session('success') }}
            </div>
        @endif

        <div class="header-section">
            <div>
                <h1>Mano de Obra</h1>
                <p>Listado general de categorías, unidades y salarios de mano de obra.</p>
            </div>

            <div class="header-actions">
                <button class="btn-filter" type="button">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>

                <a href="{{ route('mano_obra.create') }}" class="btn-add-new">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Mano de Obra
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="work-table">
                <thead>
                    <tr>
                        <th style="width:40%;">Detalle de Mano de Obra</th>
                        <th>Pago</th>
                        <th>Unidad</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse(($manoObras ?? []) as $mano)
                        <tr class="work-row">
                            <td>
                                <div class="title-main">
                                    {{ $mano->categoria ?? 'Sin categoría' }}
                                    <span class="badge-dark-mini">{{ $mano->clave ?? 'MO' }}</span>
                                </div>

                                <div class="desc-text">
                                    Registro de mano de obra para análisis de precio unitario.
                                </div>
                            </td>

                            <td>
                                <span class="amount-badge">
                                    ${{ number_format((float) $mano->salario_unitario, 2) }}
                                </span>
                            </td>

                            <td>
                                <div class="info-stack">
                                    <strong>Unidad:</strong>
                                    {{ optional($mano->unidadMedida)->nombre ?? 'N/D' }}

                                    @if(optional($mano->unidadMedida)->abreviatura)
                                        ({{ optional($mano->unidadMedida)->abreviatura }})
                                    @endif
                                </div>
                            </td>

                            <td class="action-cell">
                                <a href="{{ route('mano_obra.show', $mano->id) }}" class="btn-icon-action" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('mano_obra.edit', $mano->id) }}" class="btn-icon-action" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('mano_obra.destroy', $mano->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este registro de mano de obra?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-action" title="Eliminar">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="work-row">
                            <td colspan="4" class="empty-state">
                                No hay registros de mano de obra disponibles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection