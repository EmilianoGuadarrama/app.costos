<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Egresos – {{ $mesNombre }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: #fff;
        }

        /* ── Encabezado ── */
        .header {
            background: linear-gradient(135deg, #1a0a0a 0%, #3d1515 50%, #5c1e1e 100%);
            color: #fff;
            padding: 28px 32px 22px;
            position: relative;
            overflow: hidden;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ef4444, #dc2626, #b91c1c);
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header-brand {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header-brand span {
            color: #f87171;
        }
        .header-meta {
            text-align: right;
            font-size: 9.5px;
            color: rgba(255,255,255,.7);
            line-height: 1.7;
        }
        .header-meta strong { color: #fff; }
        .header-title {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid rgba(255,255,255,.15);
        }
        .header-title h1 {
            font-size: 16px;
            font-weight: 700;
            text-transform: capitalize;
            letter-spacing: .5px;
        }
        .header-title p {
            font-size: 10px;
            color: rgba(255,255,255,.65);
            margin-top: 3px;
        }

        /* ── Resumen ── */
        .summary-bar {
            display: flex;
            gap: 0;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        .summary-item {
            flex: 1;
            padding: 14px 20px;
            border-right: 1px solid #e9ecef;
            text-align: center;
        }
        .summary-item:last-child { border-right: none; }
        .summary-label {
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 17px;
            font-weight: 700;
            color: #b91c1c;
        }
        .summary-value.neutral { color: #1a1a1a; }

        /* ── Tabla ── */
        .content { padding: 20px 28px 28px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        thead th {
            background: #1a1a1a;
            color: #fff;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            padding: 9px 12px;
            text-align: left;
        }
        thead th:last-child { text-align: right; }

        tbody tr:nth-child(even) { background: #fdf8f8; }
        tbody tr { border-bottom: 1px solid #e9ecef; }
        tbody td {
            padding: 9px 12px;
            font-size: 10.5px;
            vertical-align: middle;
        }
        tbody td.amount {
            text-align: right;
            font-weight: 700;
            color: #b91c1c;
            white-space: nowrap;
        }
        .fecha-badge {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            font-size: 9px;
            padding: 2px 7px;
            border-radius: 3px;
        }
        .concepto-text { font-weight: 600; color: #1a1a1a; }
        .sub-text { color: #6c757d; font-size: 9.5px; margin-top: 2px; }
        .categoria-badge {
            display: inline-block;
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            font-size: 8.5px;
            padding: 2px 7px;
            border-radius: 3px;
            font-weight: 600;
            text-transform: capitalize;
        }

        /* ── Fila Total ── */
        .total-row td {
            padding: 11px 12px;
            background: #fef2f2;
            border-top: 2px solid #ef4444;
            font-weight: 700;
            font-size: 11px;
        }
        .total-row td.amount {
            font-size: 13px;
            color: #b91c1c;
        }

        /* ── Desglose por categoría ── */
        .category-breakdown {
            margin-top: 22px;
            padding: 14px 16px;
            background: #f9fafb;
            border: 1px solid #e9ecef;
            border-radius: 6px;
        }
        .category-breakdown h3 {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6c757d;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .cat-table { width: 100%; }
        .cat-table td {
            padding: 5px 8px;
            font-size: 10px;
        }
        .cat-table tr:nth-child(even) { background: #f0f0f0; }
        .cat-amount { text-align: right; font-weight: 700; color: #b91c1c; }
        .cat-pct { text-align: right; color: #6c757d; font-size: 9px; }

        /* ── Pie de página ── */
        .footer {
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-note {
            font-size: 9px;
            color: #adb5bd;
            font-style: italic;
        }
        .footer-page {
            font-size: 9px;
            color: #adb5bd;
        }

        /* ── Sin datos ── */
        .empty-state {
            text-align: center;
            padding: 32px;
            color: #adb5bd;
            font-style: italic;
            font-size: 12px;
        }
    </style>
</head>
<body>

{{-- Encabezado --}}
<div class="header">
    <div class="header-top">
        <div class="header-brand">App<span>Costos</span></div>
        <div class="header-meta">
            <strong>Generado el:</strong> {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM [de] YYYY, HH:mm') }}<br>
            <strong>Tipo:</strong> Reporte de Egresos<br>
            <strong>Periodo:</strong> {{ $mesNombre }}
        </div>
    </div>
    <div class="header-title">
        <h1>Reporte de Egresos — {{ $mesNombre }}</h1>
        <p>Detalle de todos los egresos registrados durante el periodo</p>
    </div>
</div>

{{-- Barra de resumen --}}
<div class="summary-bar">
    <div class="summary-item">
        <div class="summary-label">Total de Registros</div>
        <div class="summary-value neutral">{{ $egresos->count() }}</div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Total Egresado</div>
        <div class="summary-value">${{ number_format($egresos->sum('pago'), 2) }}</div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Promedio por Egreso</div>
        <div class="summary-value">
            ${{ $egresos->count() > 0 ? number_format($egresos->sum('pago') / $egresos->count(), 2) : '0.00' }}
        </div>
    </div>
</div>

{{-- Tabla principal --}}
<div class="content">
    @if($egresos->isEmpty())
        <div class="empty-state">No hay egresos registrados para este periodo.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:14%">Fecha</th>
                    <th style="width:28%">Concepto</th>
                    <th style="width:22%">Proyecto</th>
                    <th style="width:18%">Categoría</th>
                    <th style="width:18%">Responsable / Área</th>
                    <th style="width:12%; text-align:right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($egresos as $egreso)
                <tr>
                    <td>
                        <span class="fecha-badge">
                            {{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="concepto-text">{{ $egreso->concepto ?? '—' }}</div>
                        @if($egreso->material)
                            <div class="sub-text">Mat: {{ $egreso->material->nombre }}</div>
                        @endif
                    </td>
                    <td>{{ $egreso->obra->datosDeObra->nombre ?? ($egreso->obra->nombre ?? 'N/A') }}</td>
                    <td>
                        @if($egreso->categoria)
                            <span class="categoria-badge">{{ $egreso->categoria }}</span>
                        @else
                            <span style="color:#adb5bd">—</span>
                        @endif
                    </td>
                    <td>
                        @if($egreso->persona)
                            {{ $egreso->persona->nombre }} {{ $egreso->persona->apellido_paterno ?? '' }}
                        @elseif($egreso->area)
                            {{ $egreso->area->abreviatura ?? $egreso->area->nombre }}
                        @else
                            <span style="color:#adb5bd">—</span>
                        @endif
                    </td>
                    <td class="amount">${{ number_format($egreso->pago, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" style="font-size:11px; color:#1a1a1a;">
                        TOTAL DEL MES — {{ strtoupper($mesNombre) }}
                    </td>
                    <td class="amount">${{ number_format($egresos->sum('pago'), 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Desglose por categoría --}}
        @php
            $porCategoria = $egresos->groupBy('categoria');
            $totalGeneral = $egresos->sum('pago');
        @endphp
        @if($porCategoria->count() > 1)
        <div class="category-breakdown">
            <h3>Desglose por Categoría</h3>
            <table class="cat-table">
                @foreach($porCategoria as $cat => $items)
                <tr>
                    <td style="width:50%; font-weight:600;">{{ $cat ?: 'Sin categoría' }}</td>
                    <td style="width:15%;" class="cat-pct">{{ $items->count() }} reg.</td>
                    <td style="width:20%;" class="cat-pct">
                        {{ $totalGeneral > 0 ? number_format(($items->sum('pago') / $totalGeneral) * 100, 1) : '0.0' }}%
                    </td>
                    <td style="width:15%;" class="cat-amount">${{ number_format($items->sum('pago'), 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <div class="footer">
            <div class="footer-note">Documento generado automáticamente · AppCostos</div>
            <div class="footer-page">{{ $egresos->count() }} registro(s) incluido(s)</div>
        </div>
    @endif
</div>

</body>
</html>
