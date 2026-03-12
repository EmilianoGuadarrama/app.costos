@extends('layout')

@section('title','Generadores')

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
            word-wrap:break-word;
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
            <h4 class="fw-bold mb-1">Generadores</h4>
            <div class="text-secondary small">Listado general de generadores.</div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" type="button">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>
            <a href="{{ route('generadores.create') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Generador
            </a>
        </div>
    </div>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    <div class="table-responsive">
        <table class="tbl-grid">
            <thead>
            <tr>
                <th>CONCEPTO</th>
                <th>UNIDAD</th>
                <th>LOCALIZACIÓN</th>
                <th>EJES</th>
                <th>NO DE PIEZAS</th>
                <th>ANCHO</th>
                <th>LARGO</th>
                <th>ALTO</th>
                <th>RESULTADO</th>
                <th>ACCIONES</th>
            </tr>
            </thead>

            <tbody>
            @forelse($generadores as $g)
                <tr>
                    <td>{{ $g->concepto }}</td>
                    <td>{{ $g->unidad }}</td>
                    <td>{{ $g->localizacion }}</td>
                    <td>{{ $g->ejes }}</td>
                    <td class="text-center">{{ $g->no_piezas }}</td>
                    <td class="text-center">{{ $g->ancho }}</td>
                    <td class="text-center">{{ $g->largo }}</td>
                    <td class="text-center">{{ $g->alto }}</td>
                    <td class="text-center">{{ $g->resultado }}</td>

                    <td>
                        <div class="actions">
                            <a class="icon-btn" href="{{ route('generadores.edit', $g->id_generador) }}" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('generadores.destroy', $g->id_generador) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="icon-btn" type="submit" title="Eliminar"
                                        onclick="return confirm('¿Eliminar este generador?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-secondary py-4">
                        No hay generadores registrados.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
