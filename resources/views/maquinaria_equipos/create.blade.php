@extends('layout')

@section('title','Crear Maquinaria y Equipo')

@section('content')
    <style>
        .page-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px}
        .page-title{font-size:2rem;font-weight:800;color:#1f2937;margin:0 0 4px}
        .page-subtitle{color:#6b7280;margin:0;font-size:.98rem}
        .form-card{max-width:900px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:24px;box-shadow:0 10px 30px rgba(0,0,0,.05);padding:30px}
        .form-grid{max-width:640px;margin:0 auto}
        .form-label{font-weight:700;color:#374151;margin-bottom:8px}
        .form-control{border-radius:12px;border:1px solid #d1d5db;padding:.78rem .95rem;box-shadow:none}
        .form-control:focus{border-color:#9ca3af;box-shadow:0 0 0 .15rem rgba(107,114,128,.15)}
        .btn-back{border-radius:12px;padding:.65rem 1rem;font-weight:600}
        .btn-save{border:none;border-radius:12px;padding:.80rem 1.25rem;font-weight:700;background:#6b7280;color:#fff}
        .btn-save:hover{background:#4b5563;color:#fff}
        .btn-cancel{border-radius:12px;padding:.80rem 1.25rem;font-weight:700}
    </style>

    @php
        $campos = [
            ['name' => 'clave', 'label' => 'Clave'],
            ['name' => 'equipo', 'label' => 'Equipo'],
            ['name' => 'unidad', 'label' => 'Unidad'],
            ['name' => 'costo_por_hora', 'label' => 'Costo por hora'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h2 class="page-title">Nueva Maquinaria y Equipo</h2>
            <p class="page-subtitle">Captura la información general de maquinaria y equipo.</p>
        </div>

        <a href="{{ route('maquinaria_equipo') }}" class="btn btn-outline-secondary btn-back">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="{{ Route::has('maquinaria_equipo.store') ? route('maquinaria_equipo.store') : '#' }}" method="POST" class="form-grid">
            @csrf

            @foreach($campos as $campo)
                <div class="row mb-3 align-items-center">
                    <label class="col-md-4 form-label">{{ $campo['label'] }}</label>
                    <div class="col-md-8">
                        <input type="text" name="{{ $campo['name'] }}" class="form-control" value="{{ old($campo['name']) }}" placeholder="Ingresa {{ strtolower($campo['label']) }}">
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('maquinaria_equipo') }}" class="btn btn-outline-secondary btn-cancel">Cancelar</a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-plus-circle me-2"></i> Guardar Registro
                </button>
            </div>
        </form>
    </div>
@endsection
