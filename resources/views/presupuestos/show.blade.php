@extends('layout')

@section('title','Detalle del Presupuesto')

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

        .detail-card{
            max-width:950px;
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
            <h2 class="page-title">Detalle del Presupuesto</h2>
            <p class="page-subtitle">Consulta la información registrada del presupuesto.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('presupuesto') }}" class="btn btn-outline-secondary btn-back">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            <a href="{{ Route::has('presupuesto.edit') ? route('presupuesto.edit', $presupuesto->id ?? 1) : '#' }}" class="btn btn-edit">
                <i class="bi bi-pencil-square me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="detail-card">
        <h5 class="section-title">Datos del Presupuesto</h5>

        <div class="mb-3">
            <label class="form-label">Clave</label>
            <input type="text" class="form-control" readonly value="{{ $presupuesto->clave ?? 'PRE-001' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Concepto</label>
            <input type="text" class="form-control" readonly value="{{ $presupuesto->concepto ?? 'Concepto demo' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Unidad</label>
            <input type="text" class="form-control" readonly value="{{ $presupuesto->unidad ?? 'm2' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Cantidad</label>
            <input type="text" class="form-control" readonly value="{{ $presupuesto->cantidad ?? '0.00' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Costo Directo</label>
            <input type="text" class="form-control" readonly value="{{ $presupuesto->costo_directo ?? '0.00' }}">
        </div>

        <div class="mb-0">
            <label class="form-label">Importe</label>
            <input type="text" class="form-control" readonly value="{{ $presupuesto->importe ?? '0.00' }}">
        </div>
    </div>
@endsection
