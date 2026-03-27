@extends('layout')

@section('title','Detalle del Reporte')

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
            <h2 class="page-title">Detalle del Reporte</h2>
            <p class="page-subtitle">Consulta la información general del reporte generado.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('reportes') }}" class="btn btn-outline-secondary btn-back">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            <a href="{{ route('reportes.edit', $reporte->id_presupuesto ?? 1) }}" class="btn btn-edit">
                <i class="bi bi-pencil-square me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="detail-card">
        <h5 class="section-title">Datos del Reporte</h5>

        <div class="mb-3">
            <label class="form-label">ID Presupuesto</label>
            <input type="text" class="form-control" readonly value="{{ $reporte->id_presupuesto ?? '1' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" readonly value="{{ $reporte->nombre ?? 'Reporte general' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="text" class="form-control" readonly value="{{ $reporte->fecha ?? '2026-03-20' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de salida</label>
            <input type="text" class="form-control" readonly value="{{ $reporte->tipo_salida ?? 'PDF' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Total de Conceptos</label>
            <input type="text" class="form-control" readonly value="{{ $reporte->total_conceptos ?? '0' }}">
        </div>

        <div class="mb-0">
            <label class="form-label">Importe Total</label>
            <input type="text" class="form-control" readonly value="{{ $reporte->importe_total ?? '0.00' }}">
        </div>
    </div>
@endsection
