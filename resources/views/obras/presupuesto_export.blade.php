<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PRESUPUESTO - {{ $obra->datosDeObra?->nombre ?? 'Obra' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #000000;
            margin: 0;
            padding: 0;
        }

        /* ── ENCABEZADO EMPRESA ── */
        .header-empresa {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .header-empresa td {
            padding: 2px 6px;
            vertical-align: top;
            border: none;
        }
        .empresa-nombre {
            font-size: 16px;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .empresa-subtitulo {
            font-size: 9px;
            color: #555555;
            text-transform: uppercase;
        }
        .empresa-contacto {
            font-size: 9px;
            color: #333333;
        }
        .presupuesto-titulo {
            font-size: 14px;
            font-weight: bold;
            background-color: #000000;
            color: #ffffff;
            text-align: center;
            padding: 6px 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* ── DATOS DEL CLIENTE ── */
        .info-cliente {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 6px;
        }
        .info-cliente td {
            padding: 3px 6px;
            border: 1px solid #cccccc;
            font-size: 10px;
            vertical-align: middle;
        }
        .info-label {
            font-weight: bold;
            background-color: #000000;
            color: #ffffff;
            text-transform: uppercase;
            width: 130px;
            font-size: 9px;
        }
        .info-value {
            background-color: #ffffff;
            color: #000000;
        }
        .info-date-label {
            font-weight: bold;
            background-color: #4a4a4a;
            color: #ffffff;
            text-transform: uppercase;
            width: 130px;
            font-size: 9px;
        }

        /* ── TABLA PRINCIPAL ── */
        .tabla-conceptos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .tabla-conceptos th {
            background-color: #000000;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 9px;
            padding: 5px 4px;
            border: 1px solid #555555;
        }
        .tabla-conceptos td {
            border: 1px solid #cccccc;
            padding: 4px 5px;
            font-size: 10px;
            vertical-align: middle;
        }

        /* Encabezado de bloque: fondo negro, texto blanco */
        .bloque-header td {
            background-color: #000000;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            padding: 5px 6px;
            border: 1px solid #333333;
        }

        /* Fila de datos normales */
        .fila-concepto td {
            background-color: #ffffff;
        }
        .fila-concepto-alt td {
            background-color: #f5f5f5;
        }

        /* Subtotal de bloque */
        .bloque-subtotal td {
            background-color: #333333;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #555555;
        }

        /* Filas de totales finales */
        .total-subtotal td {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #aaaaaa;
        }
        .total-iva td {
            background-color: #e8e8e8;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #aaaaaa;
        }
        .total-gran td {
            background-color: #000000;
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #333333;
        }

        /* Alineaciones */
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .text-left   { text-align: left; }
        .wrap        { white-space: normal; word-wrap: break-word; }

        /* Separador visual */
        .separator {
            width: 100%;
            border: none;
            border-top: 2px solid #000000;
            margin: 4px 0;
        }

        /* Nota al pie */
        .nota-pie {
            font-size: 9px;
            color: #555555;
            margin-top: 10px;
            border-top: 1px solid #cccccc;
            padding-top: 4px;
        }
    </style>
</head>
<body>

@php
    $datosObra   = $obra->datosDeObra;
    $cliente     = $obra->cliente;
    $direccion   = $cliente?->direccionFiscal ?? $datosObra?->direccion ?? null;
    $domicilio   = $direccion
        ? trim(($direccion->calle_y_numero ?? '') . ', ' . ($direccion->colonia ?? '') . ', ' . ($direccion->delegacion ?? ''))
        : '—';
    $domicilio   = trim($domicilio, ', ');

    $fechaInicio = $obra->fecha_inicio ? $obra->fecha_inicio->format('d/m/Y') : '—';
    $duracion    = $obra->duracion ? $obra->duracion . ' días' : '—';

    // Calcular fecha estimada de entrega
    $fechaEntrega = '—';
    if ($obra->fecha_inicio && $obra->duracion) {
        $fechaEntrega = $obra->fecha_inicio->addDays((int)$obra->duracion)->format('d/m/Y');
    }

    $diasTranscurridos = $obra->dias_transcurridos ?? 0;
    $diasFaltan        = $obra->dias_faltan;

    $nombreCliente = $cliente?->nombre ?? $cliente?->nombre_o_razon_social ?? '—';
    $nombreObra    = $datosObra?->nombre ?? "Obra #{$obra->id}";
@endphp

{{-- ══════════════════════════════════════════
     ENCABEZADO EMPRESA
══════════════════════════════════════════ --}}
<table class="header-empresa">
    <tr>
        <td style="width: 70%; vertical-align: top;">
            <div class="empresa-nombre">AKIRAKA</div>
            <div class="empresa-subtitulo">Construcción &amp; Diseño</div>
            <div class="empresa-contacto" style="margin-top:3px;">
                Tel: (55) 0000-0000 &nbsp;|&nbsp; contacto@akiraka.mx &nbsp;|&nbsp; www.akiraka.mx
            </div>
        </td>
        <td style="width: 30%; text-align: right; vertical-align: middle;">
            <div class="presupuesto-titulo">PRESUPUESTO</div>
            <div style="font-size:9px; margin-top:3px; color:#555; text-align:right;">
                Folio: {{ str_pad($obra->id, 4, '0', STR_PAD_LEFT) }}
            </div>
        </td>
    </tr>
</table>

<hr class="separator">

{{-- ══════════════════════════════════════════
     DATOS DEL CLIENTE / PROYECTO
══════════════════════════════════════════ --}}
<table class="info-cliente">
    <tr>
        <td class="info-label">PROYECTO</td>
        <td class="info-value" colspan="3" style="font-weight:bold;">{{ $nombreObra }}</td>
    </tr>
    <tr>
        <td class="info-label">CLIENTE</td>
        <td class="info-value">{{ $nombreCliente }}</td>
        <td class="info-date-label">FECHA INICIO</td>
        <td class="info-value text-center">{{ $fechaInicio }}</td>
    </tr>
    <tr>
        <td class="info-label">DOMICILIO</td>
        <td class="info-value">{{ $domicilio }}</td>
        <td class="info-date-label">ENTREGA ESTIMADA</td>
        <td class="info-value text-center">{{ $fechaEntrega }}</td>
    </tr>
    <tr>
        <td class="info-label">DURACIÓN</td>
        <td class="info-value">{{ $duracion }}</td>
        <td class="info-date-label">DÍAS RESTANTES</td>
        <td class="info-value text-center">
            @if($diasFaltan !== null)
                {{ $diasFaltan }} días
            @else
                —
            @endif
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════════
     TABLA DE CONCEPTOS
══════════════════════════════════════════ --}}
<table class="tabla-conceptos">
    <thead>
        <tr>
            <th style="width:5%;"  class="text-center">DÍAS</th>
            <th style="width:7%;"  class="text-center">DURACIÓN</th>
            <th style="width:35%;" class="text-left">CONCEPTO</th>
            <th style="width:10%;" class="text-right">P.U.</th>
            <th style="width:7%;"  class="text-center">CANT.</th>
            <th style="width:8%;"  class="text-center">UNIDAD</th>
            <th style="width:10%;" class="text-right">INICIAL</th>
            <th style="width:8%;"  class="text-right">IVA</th>
            <th style="width:10%;" class="text-right">TOTAL INICIAL</th>
        </tr>
    </thead>
    <tbody>

    @php
        $granSubtotal = 0;
        $granIva      = 0;
        $diaAcumulado = 0;
    @endphp

    @foreach($bloques as $b)
        @php
            $conceptos = $obra->obraConceptos->where('id_bloque', $b->id);
            if ($conceptos->isEmpty()) continue;
            $totBloque = $totalesPorBloque[$b->id] ?? null;
            if ($totBloque) {
                $granSubtotal += $totBloque->total;
                $granIva      += $totBloque->iva;
            }
        @endphp

        {{-- Encabezado de bloque --}}
        <tr class="bloque-header">
            <td colspan="9">{{ strtoupper($b->descripcion) }}</td>
        </tr>

        {{-- Conceptos del bloque --}}
        @foreach($conceptos as $i => $oc)
            @php
                $durDias  = $oc->concepto?->duracion_en_dias ?? 0;
                $diaAcumulado += $durDias;
                $rowClass = ($i % 2 === 0) ? 'fila-concepto' : 'fila-concepto-alt';
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="text-center">{{ $diaAcumulado > 0 ? $diaAcumulado : '—' }}</td>
                <td class="text-center">{{ $durDias > 0 ? $durDias . 'd' : '—' }}</td>
                <td class="text-left wrap">{{ $oc->concepto?->descripcion ?? '—' }}</td>
                <td class="text-right">${{ number_format($oc->precio_unitario, 2) }}</td>
                <td class="text-center">{{ number_format($oc->cantidad, 2) }}</td>
                <td class="text-center">{{ $oc->concepto?->unidadMedida?->abreviatura ?? '—' }}</td>
                <td class="text-right">${{ number_format($oc->subtotal, 2) }}</td>
                <td class="text-right">${{ number_format($oc->iva ?? 0, 2) }}</td>
                <td class="text-right">${{ number_format($oc->total_final ?? $oc->subtotal, 2) }}</td>
            </tr>
        @endforeach

        {{-- Subtotal del bloque --}}
        @if($totBloque)
        <tr class="bloque-subtotal">
            <td colspan="6" class="text-right">SUBTOTAL {{ strtoupper($b->descripcion) }}</td>
            <td class="text-right">${{ number_format($totBloque->total, 2) }}</td>
            <td class="text-right">${{ number_format($totBloque->iva, 2) }}</td>
            <td class="text-right">${{ number_format($totBloque->total_final ?? ($totBloque->total + $totBloque->iva), 2) }}</td>
        </tr>
        @endif

    @endforeach

    {{-- ── TOTALES FINALES ── --}}
    <tr class="total-subtotal">
        <td colspan="6" class="text-right">SUBTOTAL PRESUPUESTO</td>
        <td class="text-right">${{ number_format($granSubtotal, 2) }}</td>
        <td class="text-right">—</td>
        <td class="text-right">${{ number_format($granSubtotal, 2) }}</td>
    </tr>
    <tr class="total-iva">
        <td colspan="6" class="text-right">I.V.A. (16%)</td>
        <td class="text-right">—</td>
        <td class="text-right">${{ number_format($granIva, 2) }}</td>
        <td class="text-right">${{ number_format($granIva, 2) }}</td>
    </tr>
    <tr class="total-gran">
        <td colspan="6" class="text-right">GRAN TOTAL</td>
        <td class="text-right" colspan="2">&nbsp;</td>
        <td class="text-right">${{ number_format($granSubtotal + $granIva, 2) }}</td>
    </tr>

    </tbody>
</table>

{{-- ══════════════════════════════════════════
     NOTA AL PIE
══════════════════════════════════════════ --}}
<div class="nota-pie">
    <strong>NOTAS:</strong> Los precios están expresados en Pesos Mexicanos (MXN) e incluyen I.V.A. al 16%.
    Este presupuesto tiene una vigencia de 30 días a partir de la fecha de inicio indicada.
    &nbsp;&nbsp;&nbsp;
    <strong>Elaborado:</strong> {{ now()->format('d/m/Y') }}
</div>

</body>
</html>
