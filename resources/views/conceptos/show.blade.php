@extends('layout')

@section('title','Detalle del Concepto')

@section('content')
    <style>
        .page-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px}
        .page-title{font-size:2rem;font-weight:800;color:#1f2937;margin:0 0 4px}
        .page-subtitle{color:#6b7280;margin:0;font-size:.98rem}
        .detail-card{max-width:950px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:24px;box-shadow:0 10px 30px rgba(0,0,0,.05);padding:30px}
        .form-grid{max-width:680px;margin:0 auto}
        .form-label{font-weight:700;color:#374151;margin-bottom:8px}
        .form-control[readonly]{background:#f9fafb;border:1px solid #d1d5db;border-radius:12px;padding:.78rem .95rem;color:#374151}
        .btn-back{border-radius:12px;padding:.65rem 1rem;font-weight:600}
        .btn-edit{border:none;border-radius:12px;padding:.75rem 1.1rem;font-weight:700;background:#6b7280;color:#fff}
        .btn-edit:hover{background:#4b5563;color:#fff}
    </style>

    @php
        $campos = [
            ['label' => 'Clave', 'value' => $concepto->codigo ?? 'C-001'],
            ['label' => 'Partida', 'value' => $concepto->partida ?? 'PART-01'],
            ['label' => 'Subpartida', 'value' => $concepto->subpartida ?? 'SUB-01'],
            ['label' => 'Descripción', 'value' => $concepto->descripcion ?? 'Concepto demo'],
            ['label' => 'Unidad', 'value' => $concepto->unidad ?? 'M2'],
            ['label' => 'Cantidad', 'value' => $concepto->cantidad ?? '10'],
            ['label' => 'PU', 'value' => $concepto->pu ?? '0.00'],
            ['label' => 'Importe', 'value' => $concepto->importe ?? '0.00'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h2 class="page-title">Detalle del Concepto</h2>
            <p class="page-subtitle">Consulta la información general registrada del concepto.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('conceptos') }}" class="btn btn-outline-secondary btn-back">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            <a href="{{ Route::has('conceptos.edit') ? route('conceptos.edit', $concepto->id ?? 1) : '#' }}" class="btn btn-edit">
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
