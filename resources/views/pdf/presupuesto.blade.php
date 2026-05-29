<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Presupuesto — {{ $obra->datosDeObra?->nombre ?? 'Obra' }}</title>
<style>
/* ── RESET & BASE ── */
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 11pt;
    color: #1a1a1a;
    background: #ffffff;
    line-height: 1.5;
    margin: 0;
    padding: 0;
}

/* ── PÁGINA ── */
@page {
    size: letter portrait;
    margin: 3.5cm 2.5cm 3cm 2.5cm; /* Márgenes amplios como en la referencia */
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

{{-- ── PORTADA ── --}}
<div class="portada-wrap">
    @if($logoExists)
        <img src="{{ $logoPath }}" class="portada-logo" alt="Logo Akiraka">
    @else
        <div style="font-size:30pt; font-weight:bold; margin-bottom:25pt; letter-spacing:2pt;">AKIRAKA</div>
    @endif
    <div class="portada-proyecto">{{ mb_strtoupper($nombreObra) }}</div>
    <div class="portada-asunto">PRESUPUESTO: {{ mb_strtoupper($nombreObra) }}</div>
    <div class="portada-fecha">{{ mb_strtoupper($mesAnio) }}</div>
</div>

<div class="page-break"></div>

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
            
            @php $num = 1; @endphp
            @foreach($conceptos as $oc)
                <div class="concepto-item">
                    {{ $num }}. {{ $oc->concepto?->descripcion ?? '' }}
                </div>
                <div class="concepto-precio">
                    ${{ number_format($oc->total_final ?? $oc->subtotal, 2) }} más IVA.
                </div>
                @php $num++; @endphp
            @endforeach
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

</body>
</html>
