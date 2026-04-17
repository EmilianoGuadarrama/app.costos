@extends('layout')

@section('title', 'Editar Unidad')

@section('content')
    <style>
        .page-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
            gap:16px;
            margin-bottom:20px;
        }

        .page-title{
            font-size:2rem;
            font-weight:800;
            color:#1f2937;
            margin-bottom:4px;
        }

        .page-subtitle{
            color:#6b7280;
            margin:0;
            font-size:.98rem;
        }

        .form-card{
            max-width:900px;
            margin:0 auto;
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:24px;
            box-shadow:0 10px 30px rgba(0,0,0,.05);
            padding:30px;
        }

        .form-wrapper{
            max-width:650px;
            margin:0 auto;
        }

        .section-title{
            font-size:1.08rem;
            font-weight:800;
            color:#111827;
            margin-bottom:18px;
            padding-bottom:10px;
            border-bottom:1px solid #e5e7eb;
        }

        .form-label{
            font-weight:700;
            color:#374151;
            margin-bottom:8px;
        }

        .form-control{
            border-radius:12px;
            border:1px solid #d1d5db;
            padding:.78rem .95rem;
            box-shadow:none;
        }

        .form-control:focus{
            border-color:#9ca3af;
            box-shadow:0 0 0 .15rem rgba(107,114,128,.15);
        }

        .btn-back{
            border-radius:12px;
            padding:.65rem 1rem;
            font-weight:600;
        }

        .btn-save{
            border:none;
            border-radius:12px;
            padding:.80rem 1.25rem;
            font-weight:700;
            background:#6b7280;
            color:#fff;
        }

        .btn-save:hover{
            background:#4b5563;
            color:#fff;
        }

        .btn-cancel{
            border-radius:12px;
            padding:.80rem 1.25rem;
            font-weight:700;
        }
    </style>

    <div class="page-header">
        <div>
            <h2 class="page-title">Editar Unidad de Medida</h2>
            <p class="page-subtitle">Modifica la información registrada de la unidad.</p>
        </div>

        <a href="{{ route('unidad_medida.index') }}" class="btn btn-outline-secondary btn-back">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form class="form-wrapper" method="POST" action="{{ route('unidad_medida.update', $unidad->id) }}">
            @csrf
            @method('PUT')

            <h5 class="section-title">Datos de la Unidad</h5>

            <div class="mb-3">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $unidad->nombre) }}" required maxlength="100">
                @error('nombre') <span class="text-danger mt-1 d-block" style="font-size:0.85rem;">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Abreviatura *</label>
                <input type="text" name="abreviatura" class="form-control" value="{{ old('abreviatura', $unidad->abreviatura) }}" required maxlength="20">
                @error('abreviatura') <span class="text-danger mt-1 d-block" style="font-size:0.85rem;">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion', $unidad->descripcion) }}" maxlength="180">
                @error('descripcion') <span class="text-danger mt-1 d-block" style="font-size:0.85rem;">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('unidad_medida.index') }}" class="btn btn-outline-secondary btn-cancel">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-save me-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection
