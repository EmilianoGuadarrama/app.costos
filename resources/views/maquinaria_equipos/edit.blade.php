@extends('layout')
@section('title','Editar Maquinaria y Equipo')

@section('content')
    <style>
        .panel-box{ background:#fff; border:1px solid rgba(0,0,0,.25); padding:26px; max-width:760px; margin:0 auto; }
        .form-grid{ max-width:520px; margin:0 auto; }
    </style>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h4 class="fw-bold mb-1">Editar Maquinaria y Equipo</h4>
            <div class="text-secondary small">Vista demo para edición.</div>
        </div>
        <a href="{{ route('maquinaria_equipo') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="panel-box">
        <div class="form-grid">
            @php $fields = ['Clave','Equipo','Unidad','Costo por hora']; @endphp
            @foreach($fields as $f)
                <div class="row align-items-center mb-2">
                    <div class="col-5 small fw-semibold">{{ $f }}</div>
                    <div class="col-7"><input class="form-control form-control-sm" value="Demo"></div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-3">
                <button type="button" class="btn btn-secondary btn-sm px-3">
                    <i class="bi bi-save me-2"></i> Guardar cambios
                </button>
            </div>
        </div>
    </div>
@endsection
