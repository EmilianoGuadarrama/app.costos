<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Catálogo de Conceptos — {{ $obra->datosDeObra?->nombre ?? 'Obra' }}</title>
<style>
/* ── RESET & BASE ── */
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 7pt;
    color: #000;
    background: #ffffff;
    line-height: 1.2;
    margin: 0;
    padding: 0;
}

@page {
    size: letter landscape;
    margin: 1.5cm 1cm 1.5cm 1cm; /* Margen seguro para no cortar tabla y dejar espacio al footer */
}

/* ── PIE DE PÁGINA FIJO ── */
.pie {
    position: fixed;
    bottom: -1.2cm;
    left: 0; right: 0;
    font-size: 6.5pt;
    color: #666;
    border-top: 0.5pt solid #000;
    padding-top: 3pt;
    display: table;
    width: 100%;
}
.pie-left  { display: table-cell; text-align: left; }
.pie-right { display: table-cell; text-align: right; }

/* ── HEADER LAYOUT (Estilo BLI) ── */
.header-table {
    width: 100%;
    margin-bottom: 15pt;
    border-collapse: collapse;
}
.header-table td {
    vertical-align: bottom;
}
.meta-tabla {
    width: 100%;
    border-collapse: collapse;
    font-size: 7pt;
}
.meta-tabla td {
    padding: 2pt 0;
    border-bottom: 0.5pt solid #000;
}
.meta-label {
    font-size: 6pt;
    text-transform: uppercase;
    color: #555;
}
.meta-value {
    font-weight: bold;
    text-transform: uppercase;
    color: #000;
}

.totales-top-tabla {
    width: 100%;
    border-collapse: collapse;
    margin-top: 5pt;
    font-size: 7pt;
}
.totales-top-tabla td {
    border: 1pt solid #000;
    text-align: center;
    padding: 3pt;
}

.box-faltan {
    border: 1pt solid #000;
    text-align: center;
    width: 60pt;
    float: right;
}
.box-faltan-title {
    font-size: 8pt;
    font-weight: bold;
    border-bottom: 1pt solid #000;
    padding: 2pt;
}
.box-faltan-num {
    font-size: 14pt;
    font-weight: bold;
    padding: 8pt 0;
}
.box-faltan-footer {
    font-size: 7pt;
    border-top: 1pt solid #000;
    padding: 2pt;
}

/* ── TABLA PRINCIPAL DE CONCEPTOS ── */
.cat-tabla {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    margin-bottom: 10pt;
}
/* Encabezados */
.cat-tabla thead { display: table-header-group; }
.cat-tabla thead th {
    background: #000000;
    color: #ffffff;
    font-size: 6.5pt;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;
    padding: 4pt 2pt;
    border: 0.5pt solid #fff;
    vertical-align: middle;
}
/* Celdas de datos */
.cat-tabla tbody td {
    font-size: 6.5pt;
    padding: 3pt 3pt;
    border-bottom: 0.5pt dotted #ccc;
    vertical-align: middle;
    word-wrap: break-word;
}
/* Fila encabezado de bloque */
.fila-bloque td {
    background: #000000 !important;
    color: #ffffff !important;
    font-weight: bold;
    font-size: 7pt;
    text-transform: uppercase;
    padding: 4pt 5pt !important;
    text-align: center;
}
.fila-bloque td:first-child { text-align: left; }

/* Fila subtotal de bloque */
.fila-subtotal td {
    background: #f0f0f0 !important;
    font-weight: bold;
    font-size: 6.5pt;
    border-top: 1pt solid #000 !important;
    border-bottom: 1pt solid #000 !important;
}

/* Alineaciones */
.al { text-align: left; }
.ac { text-align: center; }
.ar { text-align: right; }
.money { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }

/* ── INSUMOS (desglose debajo del concepto) ── */
.ins-wrap td {
    padding: 1pt 5pt 3pt 5pt !important;
    border-bottom: 0.5pt solid #aaa !important;
    background: #fdfdfd;
}
.ins-tabla {
    width: 100%;
    border-collapse: collapse;
    font-size: 6pt;
    margin-top: 1pt;
}
.ins-tabla td {
    padding: 1pt 3pt;
    border: none;
    color: #444;
}
.badge {
    display: inline-block;
    padding: 1pt 3pt;
    font-size: 5.5pt;
    font-weight: bold;
    color: #333;
    background: #eee;
    border-radius: 2pt;
}

/* Evitar cortes feos */
tr { page-break-inside: avoid; }
</style>
</head>
<body>

@php
    $datosObra    = $obra->datosDeObra;
    $cliente      = $obra->cliente;
    $direccion    = $cliente?->direccionFiscal ?? $datosObra?->direccion ?? null;
    $domicilio    = $direccion
        ? trim(($direccion->calle_y_numero ?? '') . ', ' . ($direccion->colonia ?? '') . ', ' . ($direccion->delegacion ?? ''))
        : '—';
    $domicilio    = trim($domicilio, ', ') ?: '—';

    $fechaInicio  = $obra->fecha_inicio ? $obra->fecha_inicio->format('d/m/Y') : '—';
    $fechaEntrega = '—';
    if ($obra->fecha_inicio && $obra->duracion) {
        $fechaEntrega = $obra->fecha_inicio->copy()->addDays((int)$obra->duracion)->format('d/m/Y');
    }
    $diasFaltan   = $obra->dias_faltan;
    $nombreCliente= $cliente?->nombre ?? $cliente?->nombre_o_razon_social ?? '—';
    $nombreObra   = $datosObra?->nombre ?? "Obra #{$obra->id}";
    $folio        = str_pad($obra->id, 4, '0', STR_PAD_LEFT);
    $fechaDoc     = now()->format('d/m/Y');

    $logoPath   = public_path('img/logo_akiraka.jpeg');
    $logoExists = file_exists($logoPath);

    // Totales Globales
    $granSub = $obra->obraConceptos->sum('subtotal');
    $granIva = $obra->obraConceptos->sum('iva');
    $granTot = $obra->obraConceptos->sum('total_final');
@endphp

{{-- PIE FIJO --}}
<div class="pie">
    <span class="pie-left">
        AKIRAKA ESTUDIO &nbsp;|&nbsp; Catálogo de Conceptos &nbsp;|&nbsp; {{ mb_strtoupper($nombreObra) }}
    </span>
    <span class="pie-right">
        FECHA: {{ $fechaDoc }} &nbsp;|&nbsp; FOLIO: {{ $folio }}
    </span>
</div>

{{-- ══════════════════════
     HEADER LAYOUT TIPO BLI.B2
══════════════════════ --}}
<table class="header-table">
    <tr>
        {{-- COLUMNA 1: Datos de Obra --}}
        <td style="width: 45%; padding-right: 15pt;">
            <table class="meta-tabla">
                <tr>
                    <td class="meta-label" style="width: 25%;">OBRA:</td>
                    <td class="meta-value">{{ mb_strtoupper($nombreObra) }}</td>
                </tr>
                <tr>
                    <td class="meta-label">CLIENTE:</td>
                    <td class="meta-value">{{ mb_strtoupper($nombreCliente) }}</td>
                </tr>
                <tr>
                    <td class="meta-label">DOMICILIO:</td>
                    <td class="meta-value">{{ mb_strtoupper($domicilio) }}</td>
                </tr>
                <tr>
                    <td class="meta-label">FECHA INICIO:</td>
                    <td class="meta-value">{{ $fechaInicio }} &nbsp;&nbsp;&nbsp;&nbsp; <span class="meta-label">ENTREGA ESTIMADA:</span> {{ $fechaEntrega }}</td>
                </tr>
            </table>
        </td>

        {{-- COLUMNA 2: Logo y Totales Top --}}
        <td style="width: 40%; text-align: center; padding-right: 15pt;">
            @if($logoExists)
                <img src="{{ $logoPath }}" style="height: 40px; margin-bottom: 5px;">
            @else
                <div style="font-size: 20pt; font-weight: bold; letter-spacing: 2pt;">AKIRAKA</div>
            @endif
            
            <table class="totales-top-tabla">
                <tr>
                    <td style="background: #fff;">SIN IVA<br><strong>${{ number_format($granSub, 2) }}</strong></td>
                    <td style="background: #fff;">IVA<br><strong>${{ number_format($granIva, 2) }}</strong></td>
                    <td style="background: #f0f0f0;">TOTAL INICIAL<br><strong>${{ number_format($granTot, 2) }}</strong></td>
                </tr>
            </table>
        </td>

        {{-- COLUMNA 3: Días Faltan --}}
        <td style="width: 15%;">
            @if($diasFaltan !== null)
            <div class="box-faltan">
                <div class="box-faltan-title">FALTAN</div>
                <div class="box-faltan-num">{{ $diasFaltan }}</div>
                <div class="box-faltan-footer">DÍAS</div>
            </div>
            @endif
        </td>
    </tr>
</table>

{{-- ══════════════════════
     TABLA DE CONCEPTOS
══════════════════════ --}}
<table class="cat-tabla">
    <thead>
        <tr>
            <th style="width:3%;">DÍAS</th>
            <th style="width:4%;">DUR.</th>
            <th style="width:4%;">ÁREA</th>
            <th style="width:31%;">CONCEPTO</th>
            <th style="width:9%;">P.U.</th>
            <th style="width:5%;">CANT.</th>
            <th style="width:4%;">UNID.</th>
            <th style="width:9%;">SUBTOTAL</th>
            <th style="width:7%;">IVA</th>
            <th style="width:10%;">TOTAL INICIAL</th>
            <th style="width:14%;">INSUMOS DETALLE</th>
        </tr>
    </thead>
    <tbody>
    @php
        $diaAcum = 0;
    @endphp
    @foreach($bloques as $b)
        @php
            $conceptos = $obra->obraConceptos->where('id_bloque', $b->id);
            if ($conceptos->isEmpty()) continue;
            $totBloque = $totalesPorBloque[$b->id] ?? null;
            $bSub = $totBloque?->total ?? $conceptos->sum('subtotal');
            $bIva = $totBloque?->iva ?? $conceptos->sum('iva');
            $bTot = $totBloque?->total_final ?? ($bSub + $bIva);
        @endphp

        {{-- ENCABEZADO BLOQUE --}}
        <tr class="fila-bloque">
            <td colspan="5">{{ strtoupper($b->descripcion) }}</td>
            <td colspan="6" style="text-align: right;">TOTAL BLOQUE: ${{ number_format($bTot, 2) }}</td>
        </tr>

        {{-- CONCEPTOS --}}
        @foreach($conceptos as $oc)
            @php
                $durDias  = $oc->concepto?->duracion_en_dias ?? 0;
                $diaAcum += $durDias;
                $tieneInsumos = $oc->materiales->isNotEmpty() || $oc->maquinaria->isNotEmpty() || $oc->manoObra->isNotEmpty();
            @endphp
            <tr>
                <td class="ac">{{ $diaAcum > 0 ? $diaAcum : '' }}</td>
                <td class="ac" style="color:#666;">{{ $durDias > 0 ? $durDias.'d' : '' }}</td>
                <td class="ac" style="font-weight:bold; color:#444;">{{ $oc->area?->abreviatura ?? $oc->concepto?->area?->abreviatura ?? '' }}</td>
                <td class="al" style="text-transform: uppercase;">{{ $oc->concepto?->descripcion ?? '' }}</td>
                <td class="ar money">${{ number_format($oc->precio_unitario, 2) }}</td>
                <td class="ac">{{ number_format($oc->cantidad, 2) }}</td>
                <td class="ac" style="text-transform: uppercase;">{{ $oc->concepto?->unidadMedida?->abreviatura ?? '' }}</td>
                <td class="ar money">${{ number_format($oc->subtotal, 2) }}</td>
                <td class="ar money">${{ number_format($oc->iva ?? 0, 2) }}</td>
                <td class="ar money" style="font-weight:bold;">${{ number_format($oc->total_final ?? $oc->subtotal, 2) }}</td>
                <td class="ac">
                    @if($tieneInsumos)
                        @if($oc->materiales->isNotEmpty())<span class="badge">MAT</span>@endif
                        @if($oc->maquinaria->isNotEmpty())<span class="badge">MAQ</span>@endif
                        @if($oc->manoObra->isNotEmpty())<span class="badge">MO</span>@endif
                    @endif
                </td>
            </tr>
            {{-- DESGLOSE DE INSUMOS --}}
            @if($tieneInsumos)
            <tr class="ins-wrap">
                <td colspan="3"></td>
                <td colspan="8">
                    <table class="ins-tabla">
                        @foreach($oc->materiales as $mat)
                        <tr>
                            <td style="width:8%;">[MAT]</td>
                            <td style="width:40%;">{{ mb_strtoupper($mat->material?->nombre ?? '') }}</td>
                            <td style="width:15%; text-align:center;">{{ number_format($mat->cantidad, 2) }} {{ $mat->material?->unidadMedida?->abreviatura ?? '' }}</td>
                            <td style="width:15%; text-align:right;">${{ number_format($mat->precio_unitario, 2) }}</td>
                            <td style="width:22%; text-align:right;"><strong>Sub:</strong> ${{ number_format($mat->cantidad * $mat->precio_unitario, 2) }}</td>
                        </tr>
                        @endforeach
                        @foreach($oc->maquinaria as $maq)
                        <tr>
                            <td>[MAQ]</td>
                            <td>{{ mb_strtoupper($maq->maquinaria?->nombre ?? '') }}</td>
                            <td style="text-align:center;">{{ number_format($maq->cantidad, 2) }} {{ $maq->maquinaria?->unidadMedida?->abreviatura ?? '' }}</td>
                            <td style="text-align:right;">${{ number_format($maq->precio_unitario, 2) }}</td>
                            <td style="text-align:right;"><strong>Sub:</strong> ${{ number_format($maq->cantidad * $maq->precio_unitario, 2) }}</td>
                        </tr>
                        @endforeach
                        @foreach($oc->manoObra as $mo)
                        <tr>
                            <td>[M.O]</td>
                            <td>{{ mb_strtoupper($mo->manoObra?->nombre ?? '') }}</td>
                            <td style="text-align:center;">{{ number_format($mo->cantidad, 2) }} {{ $mo->manoObra?->unidadMedida?->abreviatura ?? '' }}</td>
                            <td style="text-align:right;">${{ number_format($mo->precio_unitario, 2) }}</td>
                            <td style="text-align:right;"><strong>Sub:</strong> ${{ number_format($mo->cantidad * $mo->precio_unitario, 2) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            @endif
        @endforeach

        {{-- SUBTOTAL BLOQUE --}}
        @if($totBloque)
        <tr class="fila-subtotal">
            <td colspan="4" class="ar" style="letter-spacing:1pt;">TOTAL {{ strtoupper($b->descripcion) }}</td>
            <td colspan="3"></td>
            <td class="ar money">${{ number_format($bSub, 2) }}</td>
            <td class="ar money">${{ number_format($bIva, 2) }}</td>
            <td class="ar money">${{ number_format($bTot, 2) }}</td>
            <td></td>
        </tr>
        @endif
    @endforeach
    </tbody>
</table>

</body>
</html>
