@extends('layout')
@section('title','Detalle Indirecto')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .dash-form-view{ min-height:100%; background:#f8f8f8; font-family:"Arial",sans-serif; color:#111; padding:20px; }
    .form-panel{ background:#fff; padding:40px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); max-width:700px; margin:0 auto; }
    .header-section{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:30px; display:flex; justify-content:space-between; align-items:center; }
    .header-section h1{ font-size:1.8rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .detail-row{ display:flex; padding:12px 0; border-bottom:1px solid #f0f0f0; }
    .detail-label{ width:180px; font-weight:700; color:#555; font-size:.9rem; }
    .detail-value{ flex:1; font-size:.95rem; }
    .btn-back{ display:inline-block; margin-bottom:20px; color:#666; text-decoration:none; font-size:.9rem; }
</style>
<div class="dash-form-view">
    <a href="{{ route('indirectos.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section">
            <h1>Indirecto #{{ $indirecto->id }}</h1>
            <a href="{{ route('indirectos.edit', $indirecto) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-pencil"></i> Editar</a>
        </div>
        <div class="detail-row"><div class="detail-label">Concepto</div><div class="detail-value">{{ $indirecto->concepto }}</div></div>
        <div class="detail-row"><div class="detail-label">Porcentaje</div><div class="detail-value">{{ $indirecto->porcentaje }}%</div></div>
        <div class="detail-row"><div class="detail-label">Descripción</div><div class="detail-value">{{ $indirecto->descripcion ?? '—' }}</div></div>
        <div class="detail-row"><div class="detail-label">Creado</div><div class="detail-value">{{ $indirecto->created_at?->format('d/m/Y H:i') ?? '—' }}</div></div>
    </div>
</div>
@endsection
