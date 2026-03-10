@extends('layout')

@section('title','Crear Material')

@section('content')
    <style>
        .form-card{
            max-width:900px;
            margin:0 auto;
            background:#fff;
            border:1px solid rgba(0,0,0,.18);
            padding:32px;
        }
        .form-wrapper{
            max-width:620px;
            margin:0 auto;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h2 class="fw-bold mb-1">Nuevo Material</h2>
            <p class="text-secondary mb-0">Captura la información del material.</p>
        </div>

        <a href="{{ route('materiales') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form class="form-wrapper">
            @php
                $fields = ['Clave','Materiales','Marca','Unidad','Precios'];
            @endphp

            @foreach($fields as $field)
                <div class="row mb-3 align-items-center">
                    <label class="col-md-4 fw-semibold">{{ $field }}</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="Placeholder">
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-4">
                <button type="button" class="btn btn-secondary px-4">
                    <i class="bi bi-plus-circle me-2"></i> Agregar Información
                </button>
            </div>
        </form>
    </div>
@endsection
