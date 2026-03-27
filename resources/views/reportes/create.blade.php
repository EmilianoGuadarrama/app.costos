@extends('layout')

@section('title','Crear Reporte')

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
            <h2 class="page-title">Nuevo Reporte</h2>
            <p class="page-subtitle">Configura un reporte de presupuesto.</p>
        </div>

        <a href="{{ route('reportes') }}" class="btn btn-outline-secondary btn-back">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="#" method="POST" class="form-wrapper">
            @csrf

            <h5 class="section-title">Datos del Reporte</h5>

            <div class="mb-3">
                <label class="form-label">Nombre del reporte</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej. Reporte general de presupuesto">
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Presupuesto</label>
                <input type="text" name="id_presupuesto" class="form-control" placeholder="ID o nombre del presupuesto">
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de salida</label>
                <input type="text" name="tipo_salida" class="form-control" placeholder="PDF / Excel / Resumen">
            </div>

            <div class="mb-3">
                <label class="form-label">Total de conceptos</label>
                <input type="text" name="total_conceptos" class="form-control" placeholder="0">
            </div>

            <div class="mb-4">
                <label class="form-label">Importe total</label>
                <input type="text" name="importe_total" class="form-control" placeholder="0.00">
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('reportes') }}" class="btn btn-outline-secondary btn-cancel">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-plus-circle me-2"></i> Guardar Reporte
                </button>
            </div>
        </form>
    </div>
@endsection
