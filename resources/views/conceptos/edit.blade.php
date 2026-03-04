@extends('layout')
@section('title','Editar Concepto')

@section('content')
    <style>
        .panel-box{ background:#fff; border:1px solid rgba(0,0,0,.25); padding:26px; max-width:760px; margin:0 auto; }
        .form-grid{ max-width:520px; margin:0 auto; }
    </style>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h4 class="fw-bold mb-1">Editar Concepto</h4>
            <div class="text-secondary small">Modifica la información del registro (vista demo).</div>
        </div>
        <a href="{{ route('conceptos') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="panel-box">
        <div class="form-grid">
            <div class="row align-items-center mb-2">
                <div class="col-4 small fw-semibold">Clave</div>
                <div class="col-8"><input class="form-control form-control-sm" value="C-001"></div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-4 small fw-semibold">Subpartida</div>
                <div class="col-8"><input class="form-control form-control-sm" value="SUB-01"></div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-4 small fw-semibold">Descripción</div>
                <div class="col-8"><input class="form-control form-control-sm" value="Concepto demo"></div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-4 small fw-semibold">Unidad</div>
                <div class="col-8"><input class="form-control form-control-sm" value="M2"></div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-4 small fw-semibold">Cantidad</div>
                <div class="col-8"><input class="form-control form-control-sm" value="10"></div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-4 small fw-semibold">PU</div>
                <div class="col-8"><input class="form-control form-control-sm" value="0.00"></div>
            </div>
            <div class="row align-items-center mb-3">
                <div class="col-4 small fw-semibold">Importe</div>
                <div class="col-8"><input class="form-control form-control-sm" value="0.00"></div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm px-3">
                    <i class="bi bi-save me-2"></i> Guardar cambios
                </button>
            </div>
        </div>
    </div>
@endsection
