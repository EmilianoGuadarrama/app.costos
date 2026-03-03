{{-- resources/views/conceptos/index.blade.php --}}
@extends('layout')

@section('title','Conceptos')

@section('content')
    <style>
        .panel-box{ background:#fff; border:1px solid rgba(0,0,0,.25); padding:26px; max-width:760px; margin:0 auto; }
        .form-grid{ max-width:520px; margin:0 auto; }
        .form-grid .row{ margin-bottom:10px; }
        .btn-mid{ display:flex; justify-content:center; margin:18px 0 22px; }

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
        .actions{ display:flex; justify-content:flex-end; gap:10px; }
        .icon-btn{ width:28px;height:28px;display:grid;place-items:center;border:0;background:transparent;border-radius:4px;padding:0;color:#111; }
        .icon-btn:hover{ background:rgba(0,0,0,.06); }
        .tbl-grid tbody td{ height:44px; }
    </style>

    <div class="panel-box">
        <div class="form-grid">
            <div class="fw-bold mb-3">Conceptos</div>

            <div class="row align-items-center">
                <div class="col-4 small fw-semibold">Clave</div>
                <div class="col-8"><input class="form-control form-control-sm" placeholder="Placeholder"></div>
            </div>
            <div class="row align-items-center">
                <div class="col-4 small fw-semibold">Subpartida</div>
                <div class="col-8"><input class="form-control form-control-sm" placeholder="Placeholder"></div>
            </div>
            <div class="row align-items-center">
                <div class="col-4 small fw-semibold">Descripción</div>
                <div class="col-8"><input class="form-control form-control-sm" placeholder="Placeholder"></div>
            </div>
            <div class="row align-items-center">
                <div class="col-4 small fw-semibold">Unidad</div>
                <div class="col-8"><input class="form-control form-control-sm" placeholder="Placeholder"></div>
            </div>
            <div class="row align-items-center">
                <div class="col-4 small fw-semibold">Cantidad</div>
                <div class="col-8"><input class="form-control form-control-sm" placeholder="Placeholder"></div>
            </div>
            <div class="row align-items-center">
                <div class="col-4 small fw-semibold">PU</div>
                <div class="col-8"><input class="form-control form-control-sm" placeholder="Placeholder"></div>
            </div>
            <div class="row align-items-center">
                <div class="col-4 small fw-semibold">Importe</div>
                <div class="col-8"><input class="form-control form-control-sm" placeholder="Placeholder"></div>
            </div>

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
                @for($i=0;$i<6;$i++)
                    <tr>
                        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
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
