<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto de Obra</title>
    <style>
        body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #111; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 11px; }
        .details { margin-bottom: 20px; font-size: 12px; }
        .details table { width: 100%; border-collapse: collapse; }
        .details td { padding: 4px 0; }
        .presupuesto-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .presupuesto-table th, .presupuesto-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .presupuesto-table th { background-color: #f4f4f4; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #555; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .totals { width: 50%; float: right; border-collapse: collapse; }
        .totals td { padding: 8px; border: 1px solid #ddd; }
        .totals .bold { font-weight: bold; background-color: #f4f4f4; width: 40%; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .bloque-row { background-color: #eaeaea; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Presupuesto de Obra</h1>
        <p>{{ $obra->datosDeObra ? $obra->datosDeObra->nombre : 'Obra sin nombre' }}</p>
    </div>

    <div class="details">
        <table>
            <tr>
                <td><strong>Cliente:</strong> {{ $obra->cliente ? $obra->cliente->persona->nombre : 'N/D' }}</td>
                <td class="text-right"><strong>Fecha:</strong> {{ date('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Ubicación:</strong> {{ $obra->datosDeObra && $obra->datosDeObra->direccion ? $obra->datosDeObra->direccion->calle_y_numero : 'N/D' }}</td>
                <td class="text-right"><strong>Folio:</strong> #{{ str_pad($obra->id, 4, '0', STR_PAD_LEFT) }}</td>
            </tr>
        </table>
    </div>

    <table class="presupuesto-table">
        <thead>
            <tr>
                <th style="width: 50%">Concepto</th>
                <th class="text-center">Unidad</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">P.U.</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalMonto = 0; @endphp
            @foreach($obraConceptos as $renglon)
                @php 
                    $subtotal = clone $renglon->cantidad * clone $renglon->precio_unitario; 
                    $totalMonto += $subtotal;
                @endphp
                <tr>
                    <td>{{ $renglon->concepto ? $renglon->concepto->descripcion : 'Sin descripción' }}</td>
                    <td class="text-center">{{ ($renglon->concepto && $renglon->concepto->unidadMedida) ? $renglon->concepto->unidadMedida->abreviatura : 'N/D' }}</td>
                    <td class="text-right">{{ number_format((float) $renglon->cantidad, 4) }}</td>
                    <td class="text-right">${{ number_format((float) $renglon->precio_unitario, 2) }}</td>
                    <td class="text-right">${{ number_format((float) $subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="clearfix">
        <table class="totals">
            <tr>
                <td class="bold">Subtotal:</td>
                <td class="text-right">${{ number_format($totalMonto, 2) }}</td>
            </tr>
            <tr>
                <td class="bold">IVA (16%):</td>
                <td class="text-right">${{ number_format($totalMonto * 0.16, 2) }}</td>
            </tr>
            <tr>
                <td class="bold">Total:</td>
                <td class="text-right"><strong>${{ number_format($totalMonto * 1.16, 2) }}</strong></td>
            </tr>
        </table>
    </div>

</body>
</html>
