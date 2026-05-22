<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Presupuesto - {{ $obra->datosDeObra?->nombre ?? 'Obra' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #d1d5db; text-transform: uppercase; }
        .text-right { text-align: right; }
        .bg-light { background-color: #f3f4f6; }
        .bold { font-weight: bold; }
        h2 { text-transform: uppercase; margin-bottom: 5px; }
    </style>
</head>
<body>

    <h2>PRESUPUESTO DE OBRA</h2>
    <p><strong>Proyecto:</strong> {{ $obra->datosDeObra?->nombre }}</p>
    <p><strong>Cliente:</strong> {{ $obra->datosDeObra?->cliente ?? 'N/A' }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Bloque</th>
                <th style="width: 45%;">Concepto</th>
                <th style="width: 10%;">Unidad</th>
                <th style="width: 10%;">Cantidad</th>
                <th style="width: 10%;">P.U.</th>
                <th style="width: 15%;">Importe</th>
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
                    <td colspan="6" class="bg-light bold">{{ $b->descripcion }}</td>
                </tr>
                @foreach($conceptos as $oc)
                    <tr>
                        <td></td>
                        <td>{{ $oc->concepto?->descripcion }}</td>
                        <td>{{ $oc->concepto?->unidadMedida?->abreviatura ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($oc->cantidad, 2) }}</td>
                        <td class="text-right">${{ number_format($oc->precio_unitario, 2) }}</td>
                        <td class="text-right">${{ number_format($oc->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                @if($totBloque)
                <tr>
                    <td colspan="5" class="text-right bold">TOTAL {{ $b->descripcion }}</td>
                    <td class="text-right bold">${{ number_format($totBloque->total, 2) }}</td>
                </tr>
                @endif
            @endforeach
            <tr>
                <td colspan="5" class="text-right bold" style="font-size:14px; padding-top:20px;">SUBTOTAL PRESUPUESTO</td>
                <td class="text-right bold" style="font-size:14px; padding-top:20px;">${{ number_format($granTotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right bold" style="font-size:14px;">I.V.A.</td>
                <td class="text-right bold" style="font-size:14px;">${{ number_format($granIva, 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right bold" style="font-size:14px; background:#d1d5db;">GRAN TOTAL</td>
                <td class="text-right bold" style="font-size:14px; background:#d1d5db;">${{ number_format($granTotal + $granIva, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
