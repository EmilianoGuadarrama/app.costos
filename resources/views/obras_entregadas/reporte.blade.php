@extends('layout')
@section('title', 'Reporte Final de Obra')

@section('content')
<style>
.pu-hdr {
    background: #fff; padding: 25px 30px; border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 25px;
    text-align: center;
}
.pu-hdr h1 { margin: 0 0 10px 0; font-size: 1.8rem; font-weight: 800; color: #111; }
.btn-back {
    display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 700;
    color: #6b7280; background: #f3f4f6; padding: 8px 14px; border-radius: 8px;
    text-decoration: none; margin-bottom: 10px; transition: 0.2s;
}
.btn-back:hover { background: #e5e7eb; color: #111; }

.report-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 25px; }
.r-card {
    background: #fff; border-radius: 12px; padding: 30px 25px;
    border: 1px solid #e5e7eb; text-align: center;
}
.r-title { font-size: 0.9rem; font-weight: 700; color: #6b7280; text-transform: uppercase; margin-bottom: 10px; }
.r-val { font-size: 2rem; font-weight: 900; }
.r-val.ingreso { color: #059669; }
.r-val.egreso { color: #dc2626; }
.r-val.balance { color: #2563eb; }

.balance-alert {
    padding: 20px; border-radius: 12px; font-weight: 700; text-align: center; font-size: 1.1rem;
}
.bal-positive { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.bal-negative { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
.bal-exact { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
</style>

<a href="{{ route('obras_proceso.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i> Volver a Obras
</a>

@php
    $obra = $entregada->obraProceso->obraIniciada;
    $ingresos = $entregada->ingresos_generales;
    $egresos = $entregada->egresos;
    $balance = $ingresos - $egresos;
    
    $presupuesto = $entregada->obraProceso->con_iva ? ($obra->total_presupuestado * 1.16) : $obra->total_presupuestado;
@endphp

<div class="pu-hdr">
    <h1><i class="bi bi-award-fill text-warning me-2"></i> Reporte Final de Obra</h1>
    <p class="text-muted mb-0" style="font-size: 1.1rem; font-weight: 600;">{{ $obra->datosDeObra->nombre ?? 'Obra #' . $obra->id }}</p>
    <p class="text-muted mt-2 mb-0" style="font-size: 0.85rem;">Entregada el: {{ $entregada->fecha_entrega->format('d/m/Y') }}</p>
</div>

<div class="report-grid">
    <div class="r-card">
        <div class="r-title">Total Ingresado</div>
        <div class="r-val ingreso">${{ number_format($ingresos, 2) }}</div>
    </div>
    <div class="r-card">
        <div class="r-title">Total Gastado (Egresos)</div>
        <div class="r-val egreso">${{ number_format($egresos, 2) }}</div>
    </div>
    <div class="r-card">
        <div class="r-title">Utilidad Bruta</div>
        <div class="r-val balance">${{ number_format($balance, 2) }}</div>
    </div>
</div>

<div class="r-card" style="text-align: left; max-width: 600px; margin: 0 auto 30px auto;">
    <h4 style="font-weight: 800; margin-bottom: 20px;">Comparativa vs Presupuesto Original</h4>
    
    <div class="d-flex justify-content-between mb-2">
        <span style="font-weight: 600; color: #4b5563;">Presupuesto Integrado {{ $entregada->obraProceso->con_iva ? '(Con IVA)' : '(Sin IVA)' }}:</span>
        <strong style="font-size: 1.1rem;">${{ number_format($presupuesto, 2) }}</strong>
    </div>
    
    <div class="d-flex justify-content-between mb-4">
        <span style="font-weight: 600; color: #4b5563;">Total Gastado:</span>
        <strong style="font-size: 1.1rem; color: #dc2626;">${{ number_format($egresos, 2) }}</strong>
    </div>

    @php
        $diferencia = $presupuesto - $egresos;
    @endphp

    @if($diferencia > 0)
        <div class="balance-alert bal-positive">
            <i class="bi bi-graph-up-arrow me-2"></i> Excelente: La obra se realizó con un ahorro de ${{ number_format($diferencia, 2) }} respecto al presupuesto.
        </div>
    @elseif($diferencia < 0)
        <div class="balance-alert bal-negative">
            <i class="bi bi-graph-down-arrow me-2"></i> Atención: La obra excedió el presupuesto estimado por ${{ number_format(abs($diferencia), 2) }}.
        </div>
    @else
        <div class="balance-alert bal-exact">
            <i class="bi bi-check-circle me-2"></i> La obra se completó exactamente con el presupuesto estimado.
        </div>
    @endif
</div>
@endsection
