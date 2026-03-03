{{-- resources/views/proyectos/index.blade.php --}}
@extends('layout')

@section('title','Proyectos')

@section('content')

    {{-- CABECERA --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h4 class="fw-bold mb-1">Proyectos</h4>
            <div class="text-secondary small">Listado general de proyectos y su estado actual.</div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>
            <button class="btn btn-sm btn-secondary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Proyecto
            </button>
        </div>
    </div>

    <style>
        .tbl-grid{
            width:100%;
            border-collapse:collapse;
            table-layout:fixed;
            font-size:.92rem;
            background:#fff;
        }
        .tbl-grid th,
        .tbl-grid td{
            border:1px solid rgba(0,0,0,.35);
            padding:10px 8px;
            vertical-align:middle;
            background:#fff;
        }
        .tbl-grid thead th{
            text-transform:uppercase;
            letter-spacing:.04em;
            font-size:.72rem;
            font-weight:800;
            text-align:center;
            padding:8px 6px;
        }

        .w-clave{width:10%}
        .w-partida{width:12%}
        .w-sub{width:12%}
        .w-desc{width:26%}
        .w-uni{width:9%}
        .w-cant{width:9%}
        .w-pu{width:10%}
        .w-imp{width:10%}
        .w-acc{width:12%}

        .cell-center{ text-align:center; }
        .cell-right{ text-align:right; }

        .actions{
            display:flex;
            justify-content:flex-end;
            gap:10px;
        }
        .icon-btn{
            width:28px; height:28px;
            display:grid; place-items:center;
            border-radius:4px;
            border:0;
            background:transparent;
            color:#111;
            padding:0;
        }
        .icon-btn:hover{ background:rgba(0,0,0,.06); }

        .tbl-grid tbody td{ height:44px; }
    </style>

    {{-- TABLA VACÍA (sin marco externo) --}}
    <div class="table-responsive">
        <table class="tbl-grid">
            <thead>
            <tr>
                <th class="w-clave">CLAVE</th>
                <th class="w-partida">PARTIDA</th>
                <th class="w-sub">SUBPARTIDA</th>
                <th class="w-desc">DESCRIPCIÓN</th>
                <th class="w-uni">UNIDAD</th>
                <th class="w-cant">CANTIDAD</th>
                <th class="w-pu">PU</th>
                <th class="w-imp">IMPORTE</th>
                <th class="w-acc">ACCIONES</th>
            </tr>
            </thead>

            <tbody>
            @for($i=0; $i<8; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="cell-center">&nbsp;</td>
                    <td class="cell-center">&nbsp;</td>
                    <td class="cell-right">&nbsp;</td>
                    <td class="cell-right">&nbsp;</td>
                    <td>
                        <div class="actions">
                            <button class="icon-btn" type="button" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="icon-btn" type="button" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>

@endsection
