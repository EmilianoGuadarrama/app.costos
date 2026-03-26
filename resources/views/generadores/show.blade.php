@extends('layout')

@section('title','Detalle del Generador')

@section('content')
    <style>
        .page-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px}
        .page-title{font-size:2rem;font-weight:800;color:#1f2937;margin:0 0 4px}
        .page-subtitle{color:#6b7280;margin:0;font-size:.98rem}
        .detail-card{max-width:950px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:24px;box-shadow:0 10px 30px rgba(0,0,0,.05);padding:30px}
        .form-grid{max-width:720px;margin:0 auto}
        .form-label{font-weight:700;color:#374151;margin-bottom:8px}
        .form-control[readonly]{background:#f9fafb;border:1px solid #d1d5db;border-radius:12px;padding:.78rem .95rem;color:#374151}
        .btn-back{border-radius:12px;padding:.65rem 1rem;font-weight:600}
        .btn-edit{border:none;border-radius:12px;padding:.75rem 1.1rem;font-weight:700;background:#6b7280;color:#fff}
        .btn-edit:hover{background:#4b5563;color:#fff}
    </style>

    @php
        $campos = [
            ['label' => 'Concepto', 'value' => $generador->concepto ?? 'Demo'],
            ['label' => 'Unidad', 'value' => $generador->unidad ?? 'M2'],
            ['label' => 'Localización', 'value' => $generador->localizacion ?? 'Zona A'],
            ['label' => 'Ejes', 'value' => $generador->ejes ?? 'A-B'],
            ['label' => 'No. de piezas', 'value' => $generador->numero_piezas ?? '1'],
            ['label' => 'Ancho', 'value' => $generador->ancho ?? '1.00'],
            ['label' => 'Largo', 'value' => $generador->largo ?? '1.00'],
            ['label' => 'Alto', 'value' => $generador->alto ?? '1.00'],
            ['label' => 'Resultado', 'value' => $generador->resultado ?? '1.00'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h2 class="page-title">Detalle del Generador</h2>
            <p class="page-subtitle">Consulta la información general registrada del generador.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('generadores') }}" class="btn btn-outline-secondary btn-back">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            <a href="{{ Route::has('generadores.edit') ? route('generadores.edit', $generador->id ?? 1) : '#' }}" class="btn btn-edit">
                <i class="bi bi-pencil-square me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="detail-card">
        <div class="form-grid">
            @foreach($campos as $campo)
                <div class="row mb-3 align-items-center">
                    <label class="col-md-4 form-label">{{ $campo['label'] }}</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly value="{{ $campo['value'] }}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
