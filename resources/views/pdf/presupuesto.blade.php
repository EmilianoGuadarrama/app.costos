<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Presupuesto — {{ $obra->datosDeObra?->nombre ?? 'Obra' }}</title>
<style>
/* ── RESET & BASE ── */
* { box-sizing: border-box; }
body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 11pt;
    color: #1a1a1a;
    background: #ffffff;
    line-height: 1.5;
}

/* ── PÁGINA ── */
@page {
    size: letter portrait;
    margin: 2cm 1.5cm 2cm 1.5cm; /* Márgenes amplios como en la referencia */
}

/* ── PORTADA ── */
.portada-wrap {
    margin-top: 250px; /* Centrado vertical sin empujar a otra hoja accidentalmente */
}
.portada-logo {
    width: 160pt;
    height: auto;
    margin-bottom: 25pt;
}
.portada-proyecto {
    font-weight: bold;
    font-size: 13pt;
    margin-bottom: 3pt;
}
.portada-asunto {
    font-size: 12pt;
    color: #333;
    margin-bottom: 40pt;
}
.portada-fecha {
    font-size: 12pt;
    color: #333;
}

.page-break { page-break-after: always; }

/* ── CARTA FORMAL ── */
.fecha-derecha {
    text-align: right;
    font-size: 11pt;
    line-height: 1.4;
    margin-bottom: 40pt;
}
.header-logo {
    width: 140pt;
    height: auto;
    margin-bottom: 15pt;
}
.datos-empresa {
    font-size: 11pt;
    line-height: 1.3;
    margin-bottom: 30pt;
}
.datos-cliente {
    font-size: 11pt;
    line-height: 1.3;
    margin-bottom: 35pt;
}

/* ── TEXTO Y LISTADO ── */
.texto-intro {
    font-size: 11pt;
    margin-bottom: 25pt;
    text-align: justify;
}
.listado-bloques {
    margin-bottom: 30pt;
}
.bloque-item {
    margin-bottom: 20pt;
}
.bloque-titulo {
    font-weight: bold;
    margin-bottom: 12pt;
    text-transform: uppercase;
}
.concepto-item {
    margin-left: 20pt;
    margin-bottom: 5pt;
    text-align: justify;
    line-height: 1.5;
}
.concepto-precio {
    text-align: right;
    font-weight: bold;
    margin-top: 5pt;
    margin-bottom: 15pt;
}

/* ── TEXTO CIERRE Y FIRMA ── */
.texto-cierre {
    margin-bottom: 40pt;
    text-align: justify;
}
.firma-wrap {
    text-align: center;
    margin-top: 60pt;
}
.firma-texto {
    font-size: 11pt;
    margin-bottom: 40pt;
}
.firma-nombre {
    font-weight: bold;
    text-transform: uppercase;
}
</style>
</head>
<body>

@php
    $datosObra   = $obra->datosDeObra;
    $cliente     = $obra->cliente;
    $nombreCliente = $cliente?->nombre ?? $cliente?->nombre_o_razon_social ?? 'Cliente';
    $nombreObra    = $datosObra?->nombre ?? "Proyecto";
    $fechaDoc      = now()->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
    $mesAnio       = now()->locale('es')->isoFormat('MMMM YYYY');
    
    $logoPath = public_path('img/logo_akiraka.jpeg');
    $logoExists = file_exists($logoPath);
@endphp



{{-- ── CARTA FORMAL ── --}}
<div class="fecha-derecha">
    <strong>Proyecto:</strong> {{ mb_strtoupper($nombreObra) }}<br>
    <strong>Asunto:</strong> Presupuesto<br>
    {{ $fechaDoc }}
</div>

<div class="header-logo-container">
    @if($logoExists)
        <img src="{{ $logoPath }}" class="header-logo" alt="Logo Akiraka">
    @else
        <div style="font-size:24pt; font-weight:bold; margin-bottom:15pt;">AKIRAKA</div>
    @endif
</div>

<div class="datos-empresa">
    <strong>AKIRAKA ESTUDIO</strong><br>
    Parque Santa María 10, Santa María Ahuacatlán,<br>
    51200 Valle de Bravo, Estado de México<br>
    Cel. 722 165 5901<br>
    C.E: akiraka.estudio@gmail.com
</div>

<div class="datos-cliente">
    <strong>{{ $nombreCliente }}</strong><br>
    PRESENTE:
</div>

<div class="texto-intro">
    <p>Esperando se encuentren bien, por medio del presente enviamos la cotización de los trabajos correspondientes al proyecto <strong>{{ $nombreObra }}</strong>, en las opciones solicitadas.</p>
    <p style="margin-top: 15pt;">El presupuesto se detalla a continuación:</p>
</div>

<div class="listado-bloques">
    @php
        $letras = range('A', 'Z');
        $idxLetra = 0;
    @endphp

    @foreach($bloques as $b)
        @php
            $conceptos = $obra->obraConceptos->where('id_bloque', $b->id);
            if ($conceptos->isEmpty()) continue;
            
            $letra = $letras[$idxLetra] ?? '*';
            $idxLetra++;
        @endphp

        <div class="bloque-item">
            <div class="bloque-titulo">{{ $letra }}) {{ mb_strtoupper($b->descripcion) }}</div>
            
            <table style="width: 100%; border-collapse: collapse;">
            @php $num = 1; @endphp
            @foreach($conceptos as $oc)
                <tr>
                    <td style="width: 82%; vertical-align: top; padding-left: 20pt; padding-bottom: 12pt; text-align: justify;">
                        {{ $num }}. {{ $oc->concepto?->descripcion ?? '' }}
                    </td>
                    <td style="width: 18%; vertical-align: bottom; padding-bottom: 12pt; text-align: right; font-weight: bold; white-space: nowrap;">
                        ${{ number_format($oc->total_final ?? $oc->subtotal, 2) }} más IVA.
                    </td>
                </tr>
                @php $num++; @endphp
            @endforeach
            </table>
        </div>
    @endforeach
</div>

<div class="texto-cierre">
    <p>Todos los trabajos descritos incluyen lo necesario para su correcta ejecución.</p>
    <p style="margin-top: 15pt;">Quedo atento para resolver cualquier duda o inquietud que pueda surgir al respecto, así como para coordinar los trabajos en caso de aceptar el presupuesto.</p>
    <p style="text-align: center; margin-top:25pt;">Sin otro particular, les envío un cordial saludo.</p>
</div>

<div class="firma-wrap">
    <div class="firma-texto">ATENTAMENTE:</div>
    <div class="firma-nombre">ARQ. ALBERTO AKIRA KAMETA MIYAMOTO</div>
</div>

@if(!empty($materialesPorNivelArea))
<div class="page-break"></div>
<div style="font-size: 12pt; font-weight: bold; margin-bottom: 15pt; text-align: center; border-bottom: 2px solid #111; padding-bottom: 5pt;">
    LISTA DE MATERIALES A UTILIZAR POR NIVEL Y ÁREA
</div>
<table style="width: 100%; border-collapse: collapse; font-size: 10pt;">
    <thead>
        <tr>
            <th style="background: #111; color: #fff; padding: 6pt; text-align: left;">MATERIAL</th>
            <th style="background: #111; color: #fff; padding: 6pt; text-align: center;">CANTIDAD TOTAL</th>
            <th style="background: #111; color: #fff; padding: 6pt; text-align: right;">COSTO ESTIMADO</th>
        </tr>
    </thead>
    <tbody>
        @php $granTotalMateriales = 0; @endphp
        @foreach($materialesPorNivelArea as $nivelId => $nivelData)
            <tr>
                <td colspan="3" style="padding: 8pt 6pt; background: #fff; font-weight: bold; font-size: 11pt; border-top: 2px solid #111; border-bottom: 1px solid #ccc;">
                    {{ mb_strtoupper($nivelData['nombre']) }}
                </td>
            </tr>
            @foreach($nivelData['areas'] as $areaId => $areaData)
                @if(!empty($areaData['materiales']))
                    <tr>
                        <td colspan="3" style="padding: 5pt 6pt 5pt 20pt; background: #f0f0f0; font-weight: bold; font-size: 9pt;">
                            {{ strtoupper($areaData['nombre']) }}
                        </td>
                    </tr>
                    @foreach($areaData['materiales'] as $matId => $data)
                        @php $granTotalMateriales += $data['costo_total']; @endphp
                        <tr>
                            <td style="padding: 5pt 6pt 5pt 30pt; border-bottom: 1px solid #eee;">{{ mb_strtoupper($data['material']->nombre) }}</td>
                            <td style="padding: 5pt 6pt; text-align: center; border-bottom: 1px solid #eee;">
                                {{ number_format($data['cantidad_total'], 2) }} {{ $data['material']->unidadMedida?->abreviatura ?? '' }}
                            </td>
                            <td style="padding: 5pt 6pt; text-align: right; border-bottom: 1px solid #eee;">
                                ${{ number_format($data['costo_total'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @endforeach
        <tr>
            <td colspan="2" style="padding: 8pt 6pt; text-align: right; font-weight: bold;">TOTAL ESTIMADO EN MATERIALES:</td>
            <td style="padding: 8pt 6pt; text-align: right; font-weight: bold; font-size: 11pt;">${{ number_format($granTotalMateriales, 2) }}</td>
        </tr>
    </tbody>
</table>
@endif

</body>
</html>
