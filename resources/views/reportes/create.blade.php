@extends('layout')

@section('title','Crear Reporte')

@section('content')
    <style>
        .form-card{
            max-width:900px;
            margin:0 auto;
            background:#fff;
            border:1px solid rgba(0,0,0,.18);
            padding:32px;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h2 class="fw-bold mb-1">Nuevo Reporte</h2>
            <p class="text-secondary mb-0">Configura un reporte de presupuesto.</p>
        </div>

        <a href="{{ route('reportes') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="#" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre del reporte</label>
                    <input type="text" class="form-control" placeholder="Ej. Reporte general de presupuesto">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fecha</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Presupuesto</label>
                    <input type="text" class="form-control" placeholder="ID o nombre del presupuesto">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipo de salida</label>
                    <input type="text" class="form-control" placeholder="PDF / Excel / Resumen">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-secondary px-4">
                    <i class="bi bi-plus-circle me-2"></i> Agregar Información
                </button>
            </div>
        </form>
    </div>
@endsection
