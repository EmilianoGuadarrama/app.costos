{{-- resources/views/mano_de_obras/index.blade.php --}}
@extends('layout')

@section('title','Mano de Obra')

@section('content')
    <style>
        .panel-box{ background:#fff; border:1px solid rgba(0,0,0,.25); padding:26px; max-width:760px; margin:0 auto; }
        .form-grid{ max-width:520px; margin:0 auto; }
        .btn-mid{ display:flex; justify-content:center; margin:18px 0 22px; }

        .tbl-grid{
            width:100%;
            border-collapse:collapse;
            table-layout:fixed;
            font-size:.92rem;
            background:#fff;
        }
        .tbl-grid th,.tbl-grid td{ border:1px solid rgba(0,0,0,.12); padding:10px 8px; background:#fff; }
        .tbl-grid thead th{ font-size:.78rem; font-weight:700; text-align:left; }
        .actions{ display:flex; justify-content:flex-end; gap:10px; }
        .icon-btn{ width:28px;height:28px;display:grid;place-items:center;border:0;background:transparent;border-radius:4px;padding:0;color:#111; }
        .icon-btn:hover{ background:rgba(0,0,0,.06); }
        .tbl-grid tbody td{ height:46px; }
    </style>

    <div class="panel-box">
        <div class="form-grid">
            <div class="fw-bold mb-3">M.Obra</div>

            @php $fields = ['Clave','Categoria','Unidad','Salario']; @endphp
            @foreach($fields as $f)
                <div class="row align-items-center mb-2">
                    <div class="col-5 small fw-semibold">{{ $f }}</div>
                    <div class="col-7"><input class="form-control form-control-sm" placeholder="Placeholder"></div>
                </div>
            @endforeach

            <div class="btn-mid">
                <button class="btn btn-secondary btn-sm px-3" type="button">
                    <i class="bi bi-plus-circle me-2"></i> Agregar Información
                </button>
            </div>
        </div>

        <div class="table-responsive mt-2">
            <table class="tbl-grid">
                <thead>
                <tr>
                    <th>Clave</th>
                    <th>Categoria</th>
                    <th>Unidad</th>
                    <th>Salario</th>
                    <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                @for($i=0;$i<4;$i++)
                    <tr>
                        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        <td>
                            <div class="actions">
                                <button class="icon-btn" type="button" title="Editar"><i class="bi bi-pencil"></i></button>
                                <button class="icon-btn" type="button" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>
@endsection
