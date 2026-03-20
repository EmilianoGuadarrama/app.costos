@extends('layout')

@section('title','Editar P.U')

@section('content')
    <style>
        .form-card{
            max-width:980px;
            margin:0 auto;
            background:#fff;
            border:1px solid rgba(0,0,0,.18);
            padding:32px;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h2 class="fw-bold mb-1">Editar P.U</h2>
            <p class="text-secondary mb-0">Modifica el análisis de precio unitario.</p>
        </div>

        <a href="{{ route('pu') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="#" method="POST">
            @csrf

            <h5 class="fw-bold mb-3">Datos del concepto</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Código</label>
                    <input type="text" class="form-control" value="12344">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Unidad</label>
                    <input type="text" class="form-control" value="M">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <input type="text" class="form-control" value="block">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Precio unitario</label>
                    <input type="text" class="form-control" value="0.00">
                </div>
            </div>

            <h5 class="fw-bold mb-3">Desglose</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Material principal</label>
                    <input type="text" class="form-control" value="Material demo">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cantidad material</label>
                    <input type="text" class="form-control" value="1.00">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mano de obra</label>
                    <input type="text" class="form-control" value="Operador demo">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cantidad mano de obra</label>
                    <input type="text" class="form-control" value="1.00">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary px-4">
                    <i class="bi bi-save me-2"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>
@endsection
