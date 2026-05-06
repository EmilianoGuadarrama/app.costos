@extends('layout')
@section('title','Análisis P.U. — '.$analisis->concepto->clave)
@section('content')
<style>
    .pu-show-wrap{ padding:20px; font-family:"Arial",sans-serif; }
    .pu-panel{ background:#fff; border-radius:14px; box-shadow:0 4px 16px rgba(0,0,0,.07); padding:36px; }
    .pu-header{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:28px; display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; }
    .pu-header h1{ font-size:2rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .pu-header p{ margin:4px 0 0; color:#666; font-size:.9rem; }
    .header-actions{ display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .btn-edit{ background:#111; color:#fff; text-decoration:none; padding:9px 20px; border-radius:7px; font-size:.82rem; letter-spacing:.5px; text-transform:uppercase; }
    .btn-edit:hover{ background:#333; color:#fff; }
    .btn-back{ color:#666; text-decoration:none; font-size:.88rem; display:inline-flex; align-items:center; gap:5px; margin-bottom:16px; }
    .btn-back:hover{ color:#111; }

    .section-title{ font-size:.72rem; letter-spacing:2.5px; text-transform:uppercase; color:#888; font-weight:700; margin:26px 0 12px; border-top:1px solid #f0f0f0; padding-top:18px; }
    .insumos-table{ width:100%; border-collapse:collapse; font-size:.88rem; }
    .insumos-table thead th{ text-align:left; color:#777; font-size:.72rem; letter-spacing:1.5px; text-transform:uppercase; padding:8px 12px; border-bottom:1px solid #eee; }
    .insumos-table tbody td{ padding:10px 12px; border-bottom:1px solid #f5f5f5; vertical-align:middle; }
    .insumos-table tbody tr:last-child td{ border-bottom:none; }
    .insumos-table .text-right{ text-align:right; font-weight:700; }
    .subtotal-row td{ background:#fafafa; font-weight:700; font-size:.9rem; }
    .badge-area{ display:inline-block; background:#111; color:#fff; border-radius:5px; padding:2px 8px; font-size:.7rem; font-weight:700; }
    .empty-rows{ color:#aaa; font-style:italic; font-size:.88rem; padding:12px; }

    .totals-box{ background:#fafafa; border:1px solid #e8e8e8; border-radius:10px; padding:18px 24px; margin-top:26px; display:flex; flex-direction:column; align-items:flex-end; gap:10px; }
    .t-row{ display:flex; gap:60px; font-size:.9rem; min-width:320px; justify-content:space-between; }
    .t-row span:last-child{ font-weight:700; }
    .t-total{ font-size:1.1rem; border-top:1px solid #ddd; padding-top:10px; }
    .t-indirectos{ color:#777; }

    .obs-box{ margin-top:20px; background:#f9f9f9; border-radius:8px; padding:14px 18px; font-size:.9rem; color:#555; border-left:3px solid #ddd; }
</style>

<div class="pu-show-wrap">
    <a href="{{ route('analisis_pu.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver al listado</a>

    @if(session('success'))
        <div class="alert alert-dark mb-3" style="font-size:.88rem;">{{ session('success') }}</div>
    @endif

    <div class="pu-panel">
        <div class="pu-header">
            <div>
                <h1>{{ $analisis->concepto->clave }}</h1>
                <p>{{ $analisis->concepto->descripcion }}</p>
                <p>
                    <span class="badge-area">{{ $analisis->concepto->area->nombre ?? 'Sin área' }}</span>
                    &nbsp; Unidad: <strong>{{ $analisis->concepto->unidadMedida->abreviatura ?? '—' }}</strong>
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ route('analisis_pu.edit', $analisis->id) }}" class="btn-edit"><i class="bi bi-pencil me-1"></i> Editar</a>
            </div>
        </div>

        {{-- MATERIALES --}}
        <div class="section-title"><i class="bi bi-box-seam me-1"></i> Materiales</div>
        @if($analisis->materiales->isNotEmpty())
        <table class="insumos-table">
            <thead><tr><th>Clave</th><th>Material</th><th>Unidad</th><th class="text-right">Cantidad</th><th class="text-right">Costo Unit.</th><th class="text-right">Importe</th></tr></thead>
            <tbody>
                @php $sumMat = 0; @endphp
                @foreach($analisis->materiales as $r)
                    @php $imp = $r->cantidad * $r->costo_unitario; $sumMat += $imp; @endphp
                    <tr>
                        <td>{{ $r->material->clave }}</td>
                        <td>{{ $r->material->nombre }}</td>
                        <td>{{ $r->material->unidadMedida->abreviatura ?? '—' }}</td>
                        <td class="text-right">{{ number_format($r->cantidad, 4) }}</td>
                        <td class="text-right">${{ number_format($r->costo_unitario, 4) }}</td>
                        <td class="text-right">${{ number_format($imp, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="subtotal-row"><td colspan="5" style="text-align:right">Subtotal materiales:</td><td class="text-right">${{ number_format($sumMat, 2) }}</td></tr>
            </tbody>
        </table>
        @else<p class="empty-rows">Sin materiales registrados.</p>@endif

        {{-- MANO DE OBRA --}}
        <div class="section-title"><i class="bi bi-person-hard-hat me-1"></i> Mano de Obra</div>
        @if($analisis->manoObra->isNotEmpty())
        <table class="insumos-table">
            <thead><tr><th>Clave</th><th>Categoría</th><th>Unidad</th><th class="text-right">Cantidad</th><th class="text-right">Salario Unit.</th><th class="text-right">Importe</th></tr></thead>
            <tbody>
                @php $sumMO = 0; @endphp
                @foreach($analisis->manoObra as $r)
                    @php $imp = $r->cantidad * $r->costo_unitario; $sumMO += $imp; @endphp
                    <tr>
                        <td>{{ $r->manoObra->clave }}</td>
                        <td>{{ $r->manoObra->categoria }}</td>
                        <td>{{ $r->manoObra->unidadMedida->abreviatura ?? '—' }}</td>
                        <td class="text-right">{{ number_format($r->cantidad, 4) }}</td>
                        <td class="text-right">${{ number_format($r->costo_unitario, 4) }}</td>
                        <td class="text-right">${{ number_format($imp, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="subtotal-row"><td colspan="5" style="text-align:right">Subtotal mano de obra:</td><td class="text-right">${{ number_format($sumMO, 2) }}</td></tr>
            </tbody>
        </table>
        @else<p class="empty-rows">Sin mano de obra registrada.</p>@endif

        {{-- MAQUINARIA --}}
        <div class="section-title"><i class="bi bi-truck me-1"></i> Maquinaria y Equipo</div>
        @if($analisis->maquinaria->isNotEmpty())
        <table class="insumos-table">
            <thead><tr><th>Clave</th><th>Equipo</th><th>Unidad</th><th class="text-right">Cantidad</th><th class="text-right">Costo Unit.</th><th class="text-right">Importe</th></tr></thead>
            <tbody>
                @php $sumMaq = 0; @endphp
                @foreach($analisis->maquinaria as $r)
                    @php $imp = $r->cantidad * $r->costo_unitario; $sumMaq += $imp; @endphp
                    <tr>
                        <td>{{ $r->maquinariaEquipo->clave }}</td>
                        <td>{{ $r->maquinariaEquipo->equipo }}</td>
                        <td>{{ $r->maquinariaEquipo->unidadMedida->abreviatura ?? '—' }}</td>
                        <td class="text-right">{{ number_format($r->cantidad, 4) }}</td>
                        <td class="text-right">${{ number_format($r->costo_unitario, 4) }}</td>
                        <td class="text-right">${{ number_format($imp, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="subtotal-row"><td colspan="5" style="text-align:right">Subtotal maquinaria:</td><td class="text-right">${{ number_format($sumMaq, 2) }}</td></tr>
            </tbody>
        </table>
        @else<p class="empty-rows">Sin maquinaria registrada.</p>@endif

        {{-- INDIRECTOS --}}
        <div class="section-title"><i class="bi bi-percent me-1"></i> Indirectos</div>
        @if($analisis->indirectos->isNotEmpty())
        @php $directo = ($sumMat ?? 0) + ($sumMO ?? 0) + ($sumMaq ?? 0); $sumPorc = 0; @endphp
        <table class="insumos-table">
            <thead><tr><th>Clave</th><th>Concepto</th><th class="text-right">% Aplicado</th><th class="text-right">Importe</th></tr></thead>
            <tbody>
                @foreach($analisis->indirectos as $r)
                    @php $imp = $directo * ($r->porcentaje_aplicado / 100); $sumPorc += $r->porcentaje_aplicado; @endphp
                    <tr>
                        <td>{{ $r->indirecto->clave }}</td>
                        <td>{{ $r->indirecto->concepto }}</td>
                        <td class="text-right">{{ number_format($r->porcentaje_aplicado, 2) }}%</td>
                        <td class="text-right">${{ number_format($imp, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else<p class="empty-rows">Sin indirectos registrados.</p>@endif

        {{-- TOTALES --}}
        @php
            $mat     = isset($sumMat) ? $sumMat : 0;
            $mo      = isset($sumMO) ? $sumMO : 0;
            $maq     = isset($sumMaq) ? $sumMaq : 0;
            $directo = $mat + $mo + $maq;
            $sumPorc = isset($sumPorc) ? $sumPorc : 0;
            $indMonto = $directo * ($sumPorc / 100);
            $totalPu  = $directo + $indMonto;
        @endphp
        <div class="totals-box">
            <div class="t-row t-indirectos"><span>Materiales</span><span>${{ number_format($mat, 2) }}</span></div>
            <div class="t-row t-indirectos"><span>Mano de Obra</span><span>${{ number_format($mo, 2) }}</span></div>
            <div class="t-row t-indirectos"><span>Maquinaria</span><span>${{ number_format($maq, 2) }}</span></div>
            <div class="t-row"><span>Costo Directo</span><span>${{ number_format($directo, 2) }}</span></div>
            <div class="t-row t-indirectos"><span>Indirectos ({{ number_format($sumPorc, 2) }}%)</span><span>${{ number_format($indMonto, 2) }}</span></div>
            <div class="t-row t-total"><span><strong>Total P.U. / {{ $analisis->concepto->unidadMedida->abreviatura ?? 'unidad' }}</strong></span><span><strong>${{ number_format($totalPu, 2) }}</strong></span></div>
        </div>

        @if($analisis->observaciones)
            <div class="obs-box"><strong>Observaciones:</strong> {{ $analisis->observaciones }}</div>
        @endif
    </div>
</div>
@endsection
