@extends('layout')

@section('title','Crear Material')

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
            max-width:950px;
            margin:0 auto;
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:24px;
            box-shadow:0 10px 30px rgba(0,0,0,.05);
            padding:30px;
        }

        .form-wrapper{
            max-width:700px;
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
            <h2 class="page-title">Nuevo Material</h2>
            <p class="page-subtitle">Captura la información general del material.</p>
        </div>

        <a href="{{ route('materiales') }}" class="btn btn-outline-secondary btn-back">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="{{ Route::has('materiales.store') ? route('materiales.store') : '#' }}" method="POST" class="form-wrapper">
            @csrf

            <h5 class="section-title">Datos del Material</h5>

            <div class="mb-3">
                <label class="form-label">Clave</label>
                <input type="text" name="clave" class="form-control" placeholder="Ej. MAT-001">
            </div>

            <div class="mb-3">
                <label class="form-label">Material</label>
                <input type="text" name="materiales" class="form-control" placeholder="Nombre del material">
            </div>

            <div class="mb-3">
                <label class="form-label">Marca</label>
                <input type="text" name="marca" class="form-control" placeholder="Marca del material">
            </div>

            <div class="mb-3">
                <label class="form-label">Unidad</label>
                <input type="text" name="unidad" class="form-control" placeholder="Ej. pza, m, kg">
            </div>

            <div class="mb-4">
                <label class="form-label">Precio</label>
                <input type="text" name="precios" class="form-control" placeholder="0.00">
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('materiales') }}" class="btn btn-outline-secondary btn-cancel">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-plus-circle me-2"></i> Guardar Material
                </button>
            </div>
        </form>
    </div>
@endsection
