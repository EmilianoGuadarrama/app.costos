@extends('layout')
@section('title','Presupuesto — '.$presupuesto->nombre)
@section('content')
<style>
    .pu-show-wrap{ padding:20px; font-family:"Arial",sans-serif; }
    .pu-panel{ background:#fff; border-radius:14px; box-shadow:0 4px 16px rgba(0,0,0,.07); padding:36px; }
    .pu-header{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:28px; display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; }
    .pu-header h1{ font-size:2rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .pu-header .meta{ color:#666; font-size:.88rem; margin-top:6px; line-height:1.7; }
    .btn-edit{ background:#111; color:#fff; text-decoration:none; padding:9px 20px; border-radius:7px; font-size:.82rem; letter-spacing:.5px; text-transform:uppercase; }
    .btn-edit:hover{ background:#333; color:#fff; }
    .btn-back{ color:#666; text-decoration:none; font-size:.88rem; display:inline-flex; align-items:center; gap:5px; margin-bottom:16px; }
    .btn-back:hover{ color:#111; }

    .section-title{ font-size:.72rem; letter-spacing:2.5px; text-transform:uppercase; color:#888; font-weight:700; margin:24px 0 14px; border-top:1px solid #f0f0f0; padding-top:18px; }
    .presup-table{ width:100%; border-collapse:collapse; font-size:.88rem; }
    .presup-table thead th{ text-align:left; color:#777; font-size:.72rem; letter-spacing:1.5px; text-transform:uppercase; padding:8px 12px; border-bottom:2px solid #eee; }
    .presup-table tbody td{ padding:11px 12px; border-bottom:1px solid #f5f5f5; vertical-align:middle; }
    .presup-table tbody tr:hover{ background:#fafafa; }
    .presup-table .text-right{ text-align:right; }
    .presup-table .num{ font-variant-numeric:tabular-nums; }
    .badge-area{ display:inline-block; background:#f0f0f0; color:#555; border-radius:5px; padding:2px 8px; font-size:.7rem; font-weight:700; }

    .totals-box{ background:#111; color:#fff; border-radius:10px; padding:22px 28px; margin-top:26px; display:flex; flex-direction:column; align-items:flex-end; gap:10px; }
    .t-row{ display:flex; gap:60px; font-size:.9rem; min-width:300px; justify-content:space-between; }
    .t-row span:last-child{ font-weight:700; font-variant-numeric:tabular-nums; }
    .t-grand{ font-size:1.25rem; border-top:1px solid rgba(255,255,255,.2); padding-top:12px; margin-top:4px; }

    .obs-box{ margin-top:20px; background:#f9f9f9; border-radius:8px; padding:14px 18px; font-size:.9rem; color:#555; border-left:3px solid #ddd; }
</style>

<div class="pu-show-wrap">
    <a href="{{ route('presupuestos.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver al listado</a>

    @if(session('success'))
        <div class="alert alert-dark mb-3" style="font-size:.88rem;">{{ session('success') }}</div>
    @endif

    <div class="pu-panel">
        <div class="pu-header">
            <div>
                <h1>{{ $presupuesto->nombre }}</h1>
                <div class="meta">
                    <div><strong>Proyecto:</strong> {{ $presupuesto->proyecto->nombre ?? '—' }}</div>
                    <div><strong>Cliente:</strong> {{ $presupuesto->proyecto->cliente->nombre ?? '—' }}</div>
                    <div><strong>Fecha:</strong> {{ $presupuesto->fecha?->format('d/m/Y') ?? '—' }}</div>
                </div>
            </div>
            <div>
                <a href="{{ route('presupuestos.edit', $presupuesto->id) }}" class="btn-edit"><i class="bi bi-pencil me-1"></i> Editar</a>
            </div>
        </div>

        <div class="section-title"><i class="bi bi-list-columns me-1"></i> Renglones del Presupuesto</div>

        @if($presupuesto->detalles->isNotEmpty())
        <table class="presup-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Clave</th>
                    <th>Descripción del Concepto</th>
                    <th>Área</th>
                    <th>Unidad</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">P.U.</th>
                    <th class="text-right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presupuesto->detalles as $i => $d)
                @php $imp = $d->cantidad * ($d->pu_unitario_snapshot ?? 0); @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $d->concepto->clave }}</strong></td>
                    <td>{{ $d->concepto->descripcion }}</td>
                    <td><span class="badge-area">{{ $d->concepto->area->nombre ?? '—' }}</span></td>
                    <td>{{ $d->concepto->unidadMedida->abreviatura ?? '—' }}</td>
                    <td class="text-right num">{{ number_format($d->cantidad, 4) }}</td>
                    <td class="text-right num">${{ number_format($d->pu_unitario_snapshot ?? 0, 2) }}</td>
                    <td class="text-right num"><strong>${{ number_format($imp, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="color:#aaa;font-style:italic;font-size:.9rem;">Sin renglones registrados en este presupuesto.</p>
        @endif

        <div class="totals-box">
            <div class="t-row t-grand">
                <span>TOTAL DEL PRESUPUESTO</span>
                <span>${{ number_format($presupuesto->total, 2) }}</span>
            </div>
        </div>

        @if($presupuesto->observaciones)
            <div class="obs-box"><strong>Observaciones:</strong> {{ $presupuesto->observaciones }}</div>
        @endif
    </div>
</div>
@endsection
