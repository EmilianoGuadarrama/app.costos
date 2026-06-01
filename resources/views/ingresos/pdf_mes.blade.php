<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos – {{ $mesNombre }}</title>
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
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
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
            background: linear-gradient(90deg, #22c55e, #16a34a, #15803d);
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
            color: #4ade80;
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
            color: #15803d;
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

        tbody tr:nth-child(even) { background: #f8fafb; }
        tbody tr { border-bottom: 1px solid #e9ecef; }
        tbody td {
            padding: 9px 12px;
            font-size: 10.5px;
            vertical-align: middle;
        }
        tbody td.amount {
            text-align: right;
            font-weight: 700;
            color: #15803d;
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

        /* ── Fila Total ── */
        .total-row td {
            padding: 11px 12px;
            background: #e8f5e9;
            border-top: 2px solid #22c55e;
            font-weight: 700;
            font-size: 11px;
        }
        .total-row td.amount {
            font-size: 13px;
            color: #15803d;
        }

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
            <strong>Tipo:</strong> Reporte de Ingresos<br>
            <strong>Periodo:</strong> {{ $mesNombre }}
        </div>
    </div>
    <div class="header-title">
        <h1>Reporte de Ingresos — {{ $mesNombre }}</h1>
        <p>Detalle de todos los ingresos registrados durante el periodo</p>
    </div>
</div>

{{-- Barra de resumen --}}
<div class="summary-bar">
    <div class="summary-item">
        <div class="summary-label">Total de Registros</div>
        <div class="summary-value neutral">{{ $ingresos->count() }}</div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Total Ingresado</div>
        <div class="summary-value">${{ number_format($ingresos->sum('monto_dado'), 2) }}</div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Promedio por Ingreso</div>
        <div class="summary-value">
            ${{ $ingresos->count() > 0 ? number_format($ingresos->sum('monto_dado') / $ingresos->count(), 2) : '0.00' }}
        </div>
    </div>
</div>

{{-- Tabla principal --}}
<div class="content">
    @if($ingresos->isEmpty())
        <div class="empty-state">No hay ingresos registrados para este periodo.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:16%">Fecha</th>
                    <th style="width:32%">Concepto</th>
                    <th style="width:28%">Proyecto</th>
                    <th style="width:24%">Responsable</th>
                    <th style="width:14%; text-align:right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ingresos as $ingreso)
                <tr>
                    <td>
                        <span class="fecha-badge">
                            {{ \Carbon\Carbon::parse($ingreso->fecha)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="concepto-text">{{ $ingreso->concepto ?? '—' }}</div>
                        @if($ingreso->porcentaje_cubierto)
                        <div class="sub-text">{{ number_format($ingreso->porcentaje_cubierto, 1) }}% cubierto</div>
                        @endif
                    </td>
                    <td>{{ $ingreso->obra->datosDeObra->nombre ?? 'N/A' }}</td>
                    <td>
                        @if($ingreso->empleado && $ingreso->empleado->persona)
                            {{ $ingreso->empleado->persona->nombre }}
                            {{ $ingreso->empleado->persona->apellido_paterno ?? '' }}
                        @else
                            <span style="color:#adb5bd">—</span>
                        @endif
                    </td>
                    <td class="amount">${{ number_format($ingreso->monto_dado, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" style="font-size:11px; color:#1a1a1a;">
                        TOTAL DEL MES — {{ strtoupper($mesNombre) }}
                    </td>
                    <td class="amount">${{ number_format($ingresos->sum('monto_dado'), 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <div class="footer-note">Documento generado automáticamente · AppCostos</div>
            <div class="footer-page">{{ $ingresos->count() }} registro(s) incluido(s)</div>
        </div>
    @endif
</div>

</body>
</html>
