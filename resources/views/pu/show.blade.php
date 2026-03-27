@extends('layout')

@section('title','Detalle P.U')

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

        .detail-card{
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

        .form-control[readonly]{
            background:#f9fafb;
            border:1px solid #d1d5db;
            border-radius:12px;
            padding:.78rem .95rem;
            color:#374151;
        }

        .btn-back{
            border-radius:12px;
            padding:.65rem 1rem;
            font-weight:600;
        }

        .btn-edit{
            border:none;
            border-radius:12px;
            padding:.75rem 1.1rem;
            font-weight:700;
            background:#6b7280;
            color:#fff;
        }

        .btn-edit:hover{
            background:#4b5563;
            color:#fff;
        }
    </style>

    <div class="page-header">
        <div>
            <h2 class="page-title">Detalle P.U</h2>
            <p class="page-subtitle">Consulta el análisis de precio unitario por concepto.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('pu') }}" class="btn btn-outline-secondary btn-back">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            <a href="{{ route('pu.edit', $registro->id_concepto ?? 1) }}" class="btn btn-edit">
                <i class="bi bi-pencil-square me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="detail-card">
        <h5 class="section-title">Datos del Concepto</h5>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Código</label>
                <input type="text" class="form-control" readonly value="{{ $registro->codigo ?? '12344' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Unidad</label>
                <input type="text" class="form-control" readonly value="{{ $registro->unidad ?? 'M' }}">
            </div>

            <div class="col-md-12">
                <label class="form-label">Descripción</label>
                <input type="text" class="form-control" readonly value="{{ $registro->descripcion ?? 'block' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Precio Unitario</label>
                <input type="text" class="form-control" readonly value="{{ $registro->precio_unitario ?? '0.00' }}">
            </div>
        </div>

        <h5 class="section-title">Desglose</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Material Principal</label>
                <input type="text" class="form-control" readonly value="{{ $registro->material_principal ?? 'Material demo' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Cantidad Material</label>
                <input type="text" class="form-control" readonly value="{{ $registro->cantidad_material ?? '1.00' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Mano de Obra</label>
                <input type="text" class="form-control" readonly value="{{ $registro->mano_obra ?? 'Operador demo' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Cantidad Mano de Obra</label>
                <input type="text" class="form-control" readonly value="{{ $registro->cantidad_mano_obra ?? '1.00' }}">
            </div>
        </div>
    </div>
@endsection
