@extends('layout')

@section('title','Crear Indirecto')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h2 class="fw-bold mb-1">Nuevo Indirecto</h2>
            <p class="text-secondary mb-0">Captura la información del costo indirecto.</p>
        </div>
        <a href="{{ route('indirectos') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="bg-white border rounded-4 p-4">
        <form>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Clave</label>
                    <input type="text" class="form-control" placeholder="Placeholder">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Concepto</label>
                    <input type="text" class="form-control" placeholder="Placeholder">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Porcentaje</label>
                    <input type="text" class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Importe</label>
                    <input type="text" class="form-control" placeholder="0.00">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-secondary">
                    <i class="bi bi-plus-circle me-2"></i> Agregar Información
                </button>
            </div>
        </form>
    </div>
@endsection
