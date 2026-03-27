@extends('layout')

@section('title','Editar P.U')

@section('content')
    @php
        $registro = $puItem ?? $item ?? null;
    @endphp

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
            max-width:1100px;
            margin:0 auto;
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:24px;
            box-shadow:0 10px 30px rgba(0,0,0,.05);
            padding:30px;
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
            <h2 class="page-title">Editar P.U</h2>
            <p class="page-subtitle">Modifica el análisis de precio unitario registrado.</p>
        </div>

        <a href="{{ route('pu') }}" class="btn btn-outline-secondary btn-back">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="{{ Route::has('pu.update') ? route('pu.update', $registro->id_concepto ?? 1) : '#' }}" method="POST">
            @csrf
            @method('PUT')

            <h5 class="section-title">Datos del Concepto</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Código</label>
                    <input type="text" name="codigo" class="form-control" value="{{ $registro->codigo ?? '12344' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Unidad</label>
                    <input type="text" name="unidad" class="form-control" value="{{ $registro->unidad ?? 'M' }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" value="{{ $registro->descripcion ?? 'block' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Precio Unitario</label>
                    <input type="text" name="precio_unitario" class="form-control" value="{{ $registro->precio_unitario ?? '0.00' }}">
                </div>
            </div>

            <h5 class="section-title">Desglose</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Material Principal</label>
                    <input type="text" name="material_principal" class="form-control" value="{{ $registro->material_principal ?? 'Material demo' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cantidad Material</label>
                    <input type="text" name="cantidad_material" class="form-control" value="{{ $registro->cantidad_material ?? '1.00' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mano de Obra</label>
                    <input type="text" name="mano_obra" class="form-control" value="{{ $registro->mano_obra ?? 'Operador demo' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cantidad Mano de Obra</label>
                    <input type="text" name="cantidad_mano_obra" class="form-control" value="{{ $registro->cantidad_mano_obra ?? '1.00' }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('pu') }}" class="btn btn-outline-secondary btn-cancel">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-save me-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection
