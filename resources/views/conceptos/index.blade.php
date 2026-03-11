@extends('layout')

@section('title','Conceptos')

@section('content')
    <style>
        .tbl-grid{
            width:100%;
            border-collapse:collapse;
            table-layout:fixed;
            font-size:.92rem;
            background:#fff;
        }
        .tbl-grid th,.tbl-grid td{
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
        .icon-btn:hover{ background:rgba(0,0,0,.06); color:#111; }
        .tbl-grid tbody td{ height:44px; }
    </style>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h4 class="fw-bold mb-1">Conceptos</h4>
            <div class="text-secondary small">Listado general de conceptos.</div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>
            <a href="{{ route('conceptos.create') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Concepto
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="tbl-grid">
            <thead>
            <tr>
                <th>CLAVE</th>
                <th>PARTIDA</th>
                <th>SUBPARTIDA</th>
                <th>DESCRIPCIÓN</th>
                <th>UNIDAD</th>
                <th>CANTIDAD</th>
                <th>PU</th>
                <th>IMPORTE</th>
                <th>ACCIONES</th>
            </tr>
            </thead>
           <tbody>
@foreach($conceptos as $concepto)
<tr>
    <td>{{ $concepto->codigo }}</td>
    <td></td>
    <td></td>
    <td>{{ $concepto->descripcion }}</td>
    <td>{{ $concepto->id_unidad }}</td>
    <td></td>
    <td>{{ $concepto->precio_unitario }}</td>
    <td></td>

    <td>
        <div class="actions">
            <a class="icon-btn" href="{{ route('conceptos.edit', $concepto->id_concepto) }}" title="Editar">
                <i class="bi bi-pencil"></i>
            </a>

            <form action="{{ route('conceptos.destroy',$concepto->id_concepto) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="icon-btn" type="submit" title="Eliminar">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@endforeach
</tbody>
        </table>
    </div>
@endsection
