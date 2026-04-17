@extends('layout')
@section('title','Detalle Generador')
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
    .btn-back:hover{ color:#111; }
</style>
<div class="dash-form-view">
    <a href="{{ route('generadores.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section">
            <h1>Generador #{{ $generador->id }}</h1>
            <a href="{{ route('generadores.edit', $generador) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-pencil"></i> Editar</a>
        </div>
        <div class="detail-row"><div class="detail-label">Concepto</div><div class="detail-value">{{ $generador->concepto->descripcion ?? 'N/A' }}</div></div>
        <div class="detail-row"><div class="detail-label">Unidad</div><div class="detail-value">{{ $generador->concepto->unidadMedida->abreviatura ?? 'N/A' }}</div></div>
        <div class="detail-row"><div class="detail-label">Localización</div><div class="detail-value">{{ $generador->localizacion ?? '—' }}</div></div>
        <div class="detail-row"><div class="detail-label">Ejes</div><div class="detail-value">{{ $generador->ejes ?? '—' }}</div></div>
        <div class="detail-row"><div class="detail-label">No. Piezas</div><div class="detail-value">{{ $generador->no_piezas }}</div></div>
        <div class="detail-row"><div class="detail-label">Largo</div><div class="detail-value">{{ $generador->largo }}</div></div>
        <div class="detail-row"><div class="detail-label">Ancho</div><div class="detail-value">{{ $generador->ancho }}</div></div>
        <div class="detail-row"><div class="detail-label">Alto</div><div class="detail-value">{{ $generador->alto }}</div></div>
        <div class="detail-row"><div class="detail-label">Resultado</div><div class="detail-value"><strong>{{ $generador->resultado }}</strong></div></div>
        <div class="detail-row"><div class="detail-label">Creado</div><div class="detail-value">{{ $generador->created_at?->format('d/m/Y H:i') ?? '—' }}</div></div>
    </div>
</div>
@endsection
