@extends('layout')
@section('title', 'Caja General — Detalles')
@section('content')
<style>
.cgs-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:24px; }
.cgs-title { font-size:1.8rem; font-weight:800; color:#111; margin:0 0 8px; }
.btn-back-sm { color:#6b7280; text-decoration:none; font-size:.85rem; display:inline-flex; align-items:center; gap:4px; margin-bottom:12px; }
.btn-back-sm:hover { color:#111; }

.cgs-totales { display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-bottom:30px; }
.cgs-box { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,.03); }
.cgs-box.dark { background:#111827; color:#fff; border-color:#111827; }
.cgs-lbl { font-size:.7rem; text-transform:uppercase; letter-spacing:1px; color:#6b7280; font-weight:700; margin-bottom:8px; display:block; }
.cgs-box.dark .cgs-lbl { color:#9ca3af; }
.cgs-val { font-size:1.8rem; font-weight:900; }
.val-ing { color:#059669; }
.val-eg  { color:#dc2626; }
.val-sal { color:#fbbf24; }

.cgs-grid-tabs { display:grid; grid-template-columns:1fr 1fr; gap:24px; }
.cgs-section { background:#fff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; }
.cgs-section-header { padding:14px 20px; border-bottom:1px solid #e5e7eb; font-weight:800; font-size:.95rem; display:flex; align-items:center; justify-content:space-between; }
.hdr-ing { background:#f0fdf4; color:#166534; border-bottom-color:#bbf7d0; }
.hdr-eg  { background:#fef2f2; color:#991b1b; border-bottom-color:#fecaca; }

.cgs-table { width:100%; border-collapse:collapse; font-size:.8rem; }
.cgs-table th { background:#f9fafb; padding:8px 16px; text-align:left; font-size:.68rem; text-transform:uppercase; color:#6b7280; letter-spacing:.5px; border-bottom:1px solid #e5e7eb; }
.cgs-table td { padding:10px 16px; border-bottom:1px solid #f3f4f6; vertical-align:top; }
.cgs-table tr:last-child td { border-bottom:none; }
.td-fecha { color:#6b7280; font-variant-numeric:tabular-nums; white-space:nowrap; }
.td-monto { font-weight:700; font-variant-numeric:tabular-nums; text-align:right; }
.td-monto.ing { color:#059669; }
.td-monto.eg { color:#dc2626; }
.td-desc { color:#111; font-weight:600; }
.td-sub { font-size:.7rem; color:#6b7280; font-weight:normal; margin-top:3px; display:block; }

.empty-state-sm { padding:40px 20px; text-align:center; color:#9ca3af; }

@media(max-width:800px) { .cgs-grid-tabs { grid-template-columns:1fr; } .cgs-totales { grid-template-columns:1fr; } }
</style>

@php
    $ingresos = $caja->obra && $caja->obra->ingresos ? $caja->obra->ingresos->sum('monto_dado') : 0;
    $egresos  = $caja->obra && $caja->obra->egresos ? $caja->obra->egresos->sum('pago') : 0;
    $saldo    = $ingresos - $egresos;
@endphp

<div>
    <a href="{{ route('caja_general.index') }}" class="btn-back-sm">
        <i class="bi bi-arrow-left"></i> Volver a Caja General
    </a>

    <div class="cgs-header">
        <div>
            <h1 class="cgs-title">Detalle de Caja General</h1>
            <p style="color:#6b7280;margin:0;">Obra: <strong>{{ $caja->obra?->datosDeObra?->nombre ?? "Obra #{$caja->id_obra}" }}</strong></p>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('ingresos.create') }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;font-weight:600;">+ Ingreso</a>
            <a href="{{ route('egresos.create') }}" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-weight:600;">+ Egreso</a>
        </div>
    </div>

    <!-- Totales -->
    <div class="cgs-totales">
        <div class="cgs-box">
            <span class="cgs-lbl">Total Ingresos</span>
            <div class="cgs-val val-ing">${{ number_format($ingresos, 2) }}</div>
        </div>
        <div class="cgs-box">
            <span class="cgs-lbl">Total Egresos</span>
            <div class="cgs-val val-eg">${{ number_format($egresos, 2) }}</div>
        </div>
        <div class="cgs-box dark">
            <span class="cgs-lbl">Saldo Disponible</span>
            <div class="cgs-val val-sal">${{ number_format($saldo, 2) }}</div>
        </div>
    </div>

    <!-- Tablas Ingresos / Egresos -->
    <div class="cgs-grid-tabs">
        
        <!-- INGRESOS -->
        <div class="cgs-section">
            <div class="cgs-section-header hdr-ing">
                <span><i class="bi bi-arrow-down-circle me-1"></i> Ingresos Registrados</span>
                <span class="badge bg-success bg-opacity-25 text-success rounded-pill">{{ $caja->obra?->ingresos->count() ?? 0 }}</span>
            </div>
            @if($caja->obra && $caja->obra->ingresos->isNotEmpty())
            <div style="overflow-x:auto;">
                <table class="cgs-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto / Detalles</th>
                            <th style="text-align:right;">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $meses = ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'];
                            $ingresosAgrupados = $caja->obra->ingresos->sortByDesc('fecha')->groupBy(function($item) use ($meses) {
                                $c = \Carbon\Carbon::parse($item->fecha);
                                return $meses[$c->format('m')] . ' ' . $c->format('Y');
                            });
                        @endphp
                        @foreach($ingresosAgrupados as $mes => $items)
                        <tr style="background:#f8fafc;">
                            <td colspan="2" style="font-weight:700; text-transform:uppercase; color:#475569; border-bottom:1px solid #e2e8f0; padding-top:12px; padding-bottom:12px; font-size:0.75rem;">
                                <i class="bi bi-calendar3 me-1"></i> {{ $mes }}
                            </td>
                            <td style="text-align:right; font-weight:800; color:#059669; border-bottom:1px solid #e2e8f0; padding-top:12px; padding-bottom:12px;">
                                +${{ number_format($items->sum('monto_dado'), 2) }}
                            </td>
                        </tr>
                        @foreach($items as $ing)
                        <tr>
                            <td class="td-fecha">{{ \Carbon\Carbon::parse($ing->fecha)->format('d/m/Y') }}</td>
                            <td>
                                <span class="td-desc">{{ $ing->concepto ?: 'Ingreso general' }}</span>
                                @if($ing->empleado)
                                    <span class="td-sub"><i class="bi bi-person me-1"></i>{{ $ing->empleado->persona?->nombre }} {{ $ing->empleado->persona?->apellido_paterno }}</span>
                                @endif
                            </td>
                            <td class="td-monto ing">+${{ number_format($ing->monto_dado, 2) }}</td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state-sm">No hay ingresos registrados en esta obra.</div>
            @endif
        </div>

        <!-- EGRESOS -->
        <div class="cgs-section">
            <div class="cgs-section-header hdr-eg">
                <span><i class="bi bi-arrow-up-circle me-1"></i> Egresos Registrados</span>
                <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill">{{ $caja->obra?->egresos->count() ?? 0 }}</span>
            </div>
            @if($caja->obra && $caja->obra->egresos->isNotEmpty())
            <div style="overflow-x:auto;">
                <table class="cgs-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto / Detalles</th>
                            <th style="text-align:right;">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $meses = ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'];
                            $egresosAgrupados = $caja->obra->egresos->sortByDesc('fecha')->groupBy(function($item) use ($meses) {
                                $c = \Carbon\Carbon::parse($item->fecha);
                                return $meses[$c->format('m')] . ' ' . $c->format('Y');
                            });
                        @endphp
                        @foreach($egresosAgrupados as $mes => $items)
                        <tr style="background:#fef2f2;">
                            <td colspan="2" style="font-weight:700; text-transform:uppercase; color:#991b1b; border-bottom:1px solid #fecaca; padding-top:12px; padding-bottom:12px; font-size:0.75rem;">
                                <i class="bi bi-calendar3 me-1"></i> {{ $mes }}
                            </td>
                            <td style="text-align:right; font-weight:800; color:#dc2626; border-bottom:1px solid #fecaca; padding-top:12px; padding-bottom:12px;">
                                -${{ number_format($items->sum('pago'), 2) }}
                            </td>
                        </tr>
                        @foreach($items as $eg)
                        <tr>
                            <td class="td-fecha">{{ \Carbon\Carbon::parse($eg->fecha)->format('d/m/Y') }}</td>
                            <td>
                                <span class="td-desc">{{ $eg->concepto ?: 'Egreso general' }}</span>
                                @if($eg->area)
                                    <span class="td-sub"><i class="bi bi-tag me-1"></i>Área: {{ $eg->area->abreviatura }}</span>
                                @endif
                            </td>
                            <td class="td-monto eg">-${{ number_format($eg->pago, 2) }}</td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state-sm">No hay egresos registrados en esta obra.</div>
            @endif
        </div>

    </div>
</div>
@endsection
