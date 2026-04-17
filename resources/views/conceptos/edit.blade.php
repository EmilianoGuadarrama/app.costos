@extends('layout')

@section('title','Editar Concepto')

@section('content')
    <style>
        .page-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px}
        .page-title{font-size:2rem;font-weight:800;color:#1f2937;margin:0 0 4px}
        .page-subtitle{color:#6b7280;margin:0;font-size:.98rem}
        .form-card{max-width:950px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:24px;box-shadow:0 10px 30px rgba(0,0,0,.05);padding:30px}
        .form-grid{max-width:680px;margin:0 auto}
        .form-label{font-weight:700;color:#374151;margin-bottom:8px}
        .form-control{border-radius:12px;border:1px solid #d1d5db;padding:.78rem .95rem;box-shadow:none}
        .form-control:focus{border-color:#9ca3af;box-shadow:0 0 0 .15rem rgba(107,114,128,.15)}
        .btn-back{border-radius:12px;padding:.65rem 1rem;font-weight:600}
        .btn-save{border:none;border-radius:12px;padding:.80rem 1.25rem;font-weight:700;background:#6b7280;color:#fff}
        .btn-save:hover{background:#4b5563;color:#fff}
        .btn-cancel{border-radius:12px;padding:.80rem 1.25rem;font-weight:700}
    </style>

    <div class="page-header">
        <div>
            <h2 class="page-title">Editar Concepto</h2>
            <p class="page-subtitle">Modifica la información registrada del concepto.</p>
        </div>

        <a href="{{ route('conceptos.index') }}" class="btn btn-outline-secondary btn-back">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="{{ route('conceptos.update', $concepto->id) }}" method="POST" class="form-grid">
            @csrf
            @method('PUT')

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 form-label">Clave *</label>
                <div class="col-md-8">
                    <input type="text" name="clave" class="form-control" value="{{ old('clave', $concepto->clave) }}" placeholder="Ej. ALB-001" required maxlength="50">
                    @error('clave') <span class="text-danger mt-1 d-block" style="font-size:0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 form-label">Área</label>
                <div class="col-md-8">
                    <select name="area_id" class="form-control">
                        <option value="">-- Seleccionar Área --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id', $concepto->area_id) == $area->id ? 'selected' : '' }}>{{ $area->clave }} - {{ $area->nombre }}</option>
                        @endforeach
                    </select>
                    @error('area_id') <span class="text-danger mt-1 d-block" style="font-size:0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 form-label">Partida</label>
                <div class="col-md-8">
                    <input type="text" name="partida" class="form-control" value="{{ old('partida', $concepto->partida) }}" placeholder="Ej. Preliminares" maxlength="100">
                    @error('partida') <span class="text-danger mt-1 d-block" style="font-size:0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 form-label">Subpartida</label>
                <div class="col-md-8">
                    <input type="text" name="subpartida" class="form-control" value="{{ old('subpartida', $concepto->subpartida) }}" placeholder="Ej. Trazo y Nivelación" maxlength="100">
                    @error('subpartida') <span class="text-danger mt-1 d-block" style="font-size:0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 form-label">Descripción *</label>
                <div class="col-md-8">
                    <textarea name="descripcion" class="form-control" rows="3" required maxlength="255" placeholder="Descripción detallada del concepto">{{ old('descripcion', $concepto->descripcion) }}</textarea>
                    @error('descripcion') <span class="text-danger mt-1 d-block" style="font-size:0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 form-label">Unidad de Medida *</label>
                <div class="col-md-8">
                    <select name="unidad_medida_id" class="form-control" required>
                        <option value="">-- Seleccionar Unidad --</option>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id }}" {{ old('unidad_medida_id', $concepto->unidad_medida_id) == $unidad->id ? 'selected' : '' }}>{{ $unidad->nombre }} ({{ $unidad->abreviatura }})</option>
                        @endforeach
                    </select>
                    @error('unidad_medida_id') <span class="text-danger mt-1 d-block" style="font-size:0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('conceptos.index') }}" class="btn btn-outline-secondary btn-cancel">Cancelar</a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-save me-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection
