@extends('layout')

@section('title','P.U')

@section('content')
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
        .actions{
            display:flex;
            justify-content:flex-end;
            gap:10px;
        }
        .icon-btn{
            width:28px;
            height:28px;
            display:grid;
            place-items:center;
            border:0;
            background:transparent;
            border-radius:4px;
            padding:0;
            color:#111;
            text-decoration:none;
        }
        .icon-btn:hover{
            background:rgba(0,0,0,.06);
            color:#111;
        }
        .tbl-grid tbody td{ height:44px; }
    </style>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h4 class="fw-bold mb-1">P.U</h4>
            <div class="text-secondary small">Listado de análisis de precios unitarios por concepto.</div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>

            <a href="{{ route('pu.create') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo P.U
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="tbl-grid">
            <thead>
            <tr>
                <th>CÓDIGO</th>
                <th>CONCEPTO</th>
                <th>UNIDAD</th>
                <th>PRECIO UNITARIO</th>
                <th>MATERIALES</th>
                <th>MANO DE OBRA</th>
                <th>ACCIONES</th>
            </tr>
            </thead>

            <tbody>
            @forelse(($puItems ?? []) as $item)
                <tr>
                    <td>{{ $item->codigo ?? '' }}</td>
                    <td>{{ $item->descripcion ?? '' }}</td>
                    <td>{{ $item->unidad ?? '' }}</td>
                    <td>{{ $item->precio_unitario ?? '' }}</td>
                    <td>{{ $item->materiales_total ?? '' }}</td>
                    <td>{{ $item->mano_obra_total ?? '' }}</td>
                    <td>
                        <div class="actions">
                            <a class="icon-btn" href="{{ route('pu.show', $item->id_concepto ?? 1) }}" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a class="icon-btn" href="{{ route('pu.edit', $item->id_concepto ?? 1) }}" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="icon-btn" type="button" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                @for($i=0; $i<6; $i++)
                    @php($id = $i + 1)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <div class="actions">
                                <a class="icon-btn" href="{{ route('pu.show', $id) }}" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="icon-btn" href="{{ route('pu.edit', $id) }}" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="icon-btn" type="button" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endfor
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
