<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Presupuesto - {{ $obra->datosDeObra?->nombre ?? 'Obra' }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        h2 { text-transform: uppercase; margin-bottom: 5px; font-size: 18px; color: #111827; }
        p { margin: 3px 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #9ca3af; padding: 6px 8px; text-align: left; vertical-align: middle; }
        th { background-color: #e5e7eb; text-transform: uppercase; font-size: 10px; font-weight: bold; color: #374151; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bg-light { background-color: #f3f4f6; }
        .bg-darker { background-color: #d1d5db; }
        .bold { font-weight: bold; }
        .bloque-header { background-color: #1f2937; color: #ffffff; font-weight: bold; text-transform: uppercase; padding: 8px; }
    </style>
</head>
<body>

    <h2>PRESUPUESTO DE OBRA</h2>
    <p><strong>Proyecto:</strong> {{ $obra->datosDeObra?->nombre }}</p>
    <p><strong>Cliente:</strong> {{ $obra->datosDeObra?->cliente ?? 'N/A' }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Concepto</th>
                <th style="width: 10%; text-align: center;">Unidad</th>
                <th style="width: 10%; text-align: center;">Cantidad</th>
                <th style="width: 15%; text-align: right;">P.U.</th>
                <th style="width: 15%; text-align: right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $granTotal = 0; 
                $granIva = 0;
            @endphp
            @foreach($bloques as $b)
                @php 
                    $conceptos = $obra->obraConceptos->where('id_bloque', $b->id); 
                    if($conceptos->isEmpty()) continue;
                    $totBloque = $totalesPorBloque[$b->id] ?? null;
                    if($totBloque) {
                        $granTotal += $totBloque->total;
                        $granIva += $totBloque->iva;
                    }
                @endphp
                <tr>
                    <td colspan="5" class="bloque-header">{{ $b->descripcion }}</td>
                </tr>
                @foreach($conceptos as $oc)
                    <tr>
                        <td style="padding-left: 15px;">{{ $oc->concepto?->descripcion }}</td>
                        <td class="text-center">{{ $oc->concepto?->unidadMedida?->abreviatura ?? 'N/A' }}</td>
                        <td class="text-center">{{ number_format($oc->cantidad, 2) }}</td>
                        <td class="text-right">${{ number_format($oc->precio_unitario, 2) }}</td>
                        <td class="text-right">${{ number_format($oc->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                @if($totBloque)
                <tr>
                    <td colspan="4" class="text-right bold bg-light">TOTAL {{ $b->descripcion }}</td>
                    <td class="text-right bold bg-light">${{ number_format($totBloque->total, 2) }}</td>
                </tr>
                @endif
            @endforeach
            <tr>
                <td colspan="4" class="text-right bold" style="font-size:13px; padding-top:15px; border-bottom: none;">SUBTOTAL PRESUPUESTO</td>
                <td class="text-right bold" style="font-size:13px; padding-top:15px; border-bottom: none;">${{ number_format($granTotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right bold" style="font-size:13px; border-top: none; border-bottom: none;">I.V.A. (16%)</td>
                <td class="text-right bold" style="font-size:13px; border-top: none; border-bottom: none;">${{ number_format($granIva, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right bold bg-darker" style="font-size:14px; border-top: 2px solid #374151;">GRAN TOTAL</td>
                <td class="text-right bold bg-darker" style="font-size:14px; border-top: 2px solid #374151;">${{ number_format($granTotal + $granIva, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
