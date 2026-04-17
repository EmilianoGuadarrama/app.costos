@extends('layout')
@section('title','Detalle Compra')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .dash-form-view{ min-height:100%; background:#f8f8f8; font-family:"Arial",sans-serif; color:#111; padding:20px; }
    .form-panel{ background:#fff; padding:40px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); max-width:800px; margin:0 auto; }
    .header-section{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:30px; display:flex; justify-content:space-between; align-items:center; }
    .header-section h1{ font-size:1.8rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .detail-row{ display:flex; padding:12px 0; border-bottom:1px solid #f0f0f0; }
    .detail-label{ width:180px; font-weight:700; color:#555; font-size:.9rem; }
    .detail-value{ flex:1; font-size:.95rem; }
    .btn-back{ display:inline-block; margin-bottom:20px; color:#666; text-decoration:none; font-size:.9rem; }
    .badge-soft{ display:inline-flex; align-items:center; justify-content:center; padding:4px 10px; border-radius:12px; background:#eee; color:#333; font-family:Arial,sans-serif; font-size:.75rem; font-weight:700; text-transform:uppercase;}
</style>
<div class="dash-form-view">
    <a href="{{ route('compras.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section">
            <h1>Compra #{{ $compra->id }}</h1>
            <a href="{{ route('compras.edit', $compra) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-pencil"></i> Editar</a>
        </div>
        <div class="detail-row"><div class="detail-label">Proyecto</div><div class="detail-value">{{ $compra->proyecto->nombre ?? 'N/A' }}</div></div>
        <div class="detail-row"><div class="detail-label">Proveedor</div><div class="detail-value">{{ $compra->proveedor->nombre ?? 'N/A' }}</div></div>
        <div class="detail-row"><div class="detail-label">Área</div><div class="detail-value">{{ $compra->area->nombre ?? 'N/A' }}</div></div>
        <div class="detail-row"><div class="detail-label">Fecha de Compra</div><div class="detail-value">{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</div></div>
        <div class="detail-row"><div class="detail-label">Estado</div><div class="detail-value"><span class="badge-soft">{{ $compra->estado }}</span></div></div>
        <div class="detail-row"><div class="detail-label">Factura</div><div class="detail-value">{{ $compra->factura ?? 'S/F' }}</div></div>
        <div class="detail-row"><div class="detail-label">Total</div><div class="detail-value font-weight-bold">${{ number_format($compra->total, 2) }}</div></div>
        <div class="detail-row"><div class="detail-label">Creado</div><div class="detail-value">{{ $compra->created_at?->format('d/m/Y H:i') ?? '—' }}</div></div>
    </div>
</div>
@endsection
