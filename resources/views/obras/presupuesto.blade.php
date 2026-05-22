@extends('layout')
@section('title', 'Presupuesto — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))
@section('content')
<style>
.pres-wrap { font-family:"Arial",sans-serif; font-size:.82rem; }

/* Contadores */
.pres-counters { display:grid; grid-template-columns:repeat(4,1fr); border:1px solid #bbb; margin-bottom:0; }
.pc-box { padding:10px 14px; text-align:center; border-right:1px solid #bbb; }
.pc-box:last-child { border-right:none; }
.pc-lbl { font-size:.65rem; text-transform:uppercase; letter-spacing:1px; color:#666; font-weight:700; }
.pc-val { font-size:1.5rem; font-weight:900; color:#111; }

/* Totales bar */
.pres-totales { display:grid; grid-template-columns:repeat(4,1fr); border:1px solid #bbb; border-top:none; background:#f0f0f0; margin-bottom:16px; }
.pt-cell { padding:8px 12px; border-right:1px solid #bbb; text-align:center; }
.pt-cell:last-child { border-right:none; }
.pt-lbl { font-size:.63rem; font-weight:700; text-transform:uppercase; color:#666; }
.pt-val { font-size:.95rem; font-weight:900; color:#111; font-variant-numeric:tabular-nums; }

/* Acciones */
.pres-actions { display:flex; align-items:center; gap:8px; margin-bottom:14px; flex-wrap:wrap; }
.btn-pa { border-radius:9px; padding:.48rem 1rem; font-size:.8rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:5px; }
.btn-pa-dark { background:#111827; color:#fff; }
.btn-pa-blue { background:#2563eb; color:#fff; }
.btn-pa-green { background:#059669; color:#fff; }
.btn-pa-outline { background:#fff; color:#374151; border:1.5px solid #e5e7eb; cursor:pointer;}
.btn-back-sm { color:#6b7280; text-decoration:none; font-size:.85rem; display:flex; align-items:center; gap:4px; }

/* Presupuesto tabla */
.pres-tabla { width:100%; border-collapse:collapse; font-size:.8rem; min-width:780px; }

/* Fila de ítem */
.item-row td {
    padding:7px 10px; border-bottom:1px solid #f0f0f0; border-right:1px solid #f0f0f0;
    background:#fff; vertical-align:middle;
}
.item-row:hover td { background:#f9fafb; }

.tr-desglose { display: none; }
.tr-desglose.active { display: table-row; }
.tr-desglose td { background:#f0f9ff; padding:8px 14px; font-size:.75rem; color:#374151; }

.comp-badge { display:inline-block; padding:2px 6px; border-radius:6px; font-size:.63rem; font-weight:700; margin-right:3px; }
.cb-mat { background:#dbeafe; color:#1d4ed8; }
.cb-maq { background:#fef3c7; color:#92400e; }
.cb-mo  { background:#d1fae5; color:#065f46; }

/* Total de bloque */
.bloque-total td {
    background:#374151; color:#fff; font-weight:900; font-size:.78rem;
    padding:7px 10px; border:1px solid #4b5563; text-align:right;
}

/* Gran total */
.gran-total td {
    background:#111827; color:#fff; font-weight:900; font-size:.88rem;
    padding:10px 12px; border:1px solid #374151; text-align:right;
}
.gran-total td:last-child { color:#fbbf24; }
</style>

<div class="pres-wrap">
    <div class="pres-actions">
        <a href="{{ route('obras.show', $obra->id) }}" class="btn-back-sm"><i class="bi bi-arrow-left"></i> Datos generales</a>
        <div style="flex:1"></div>
        <a href="{{ route('obras.presupuesto.export_excel', $obra->id) }}" class="btn-pa btn-pa-outline" style="border-color:#107c41; color:#107c41;"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
        <a href="{{ route('obras.presupuesto.export_pdf', $obra->id) }}" class="btn-pa btn-pa-outline" style="border-color:#da0b20; color:#da0b20;"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
        <button type="button" class="btn-pa btn-pa-outline" id="btn-toggle-desglose" onclick="toggleTodosDesgloses()">
            <i class="bi bi-diagram-3 me-1"></i> Desglosar Matrices
        </button>
        <a href="{{ route('obras.presupuesto.unificado.create', $obra->id) }}" class="btn-pa btn-pa-blue">
            <i class="bi bi-layers"></i> + Agregar Renglones
        </a>
    </div>

    @php
        $conceptos = $obra->obraConceptos;
        $gSub = $conceptos->sum('subtotal');
        $gIva = $conceptos->sum('iva');
        $gTot = $conceptos->sum('total_final');

        // Agrupar por nivel -> bloque
        $nivelesList = $obra->niveles->keyBy('id');
        $porNivel = collect();

        foreach ($conceptos->groupBy('id_nivel') as $idNiv => $filasNiv) {
            $nomNivel = $idNiv ? ($nivelesList[$idNiv]->descripcion ?? "Nivel $idNiv") : 'GENERAL / SIN NIVEL';
            
            $porBloque = collect();
            foreach ($bloques as $blq) {
                $filasBloq = $filasNiv->where('id_bloque', $blq->id);
                if ($filasBloq->isNotEmpty()) {
                    $porBloque->put($blq->id, ['nombre' => $blq->descripcion, 'filas' => $filasBloq]);
                }
            }
            $sinBloque = $filasNiv->where('id_bloque', null);
            if ($sinBloque->isNotEmpty()) {
                $porBloque->put(0, ['nombre' => 'Sin Bloque', 'filas' => $sinBloque]);
            }
            
            $porNivel->put($idNiv, [
                'nombre' => $nomNivel,
                'bloques' => $porBloque,
                'subtotal' => $filasNiv->sum('subtotal'),
                'iva' => $filasNiv->sum('iva'),
                'total' => $filasNiv->sum('total_final'),
            ]);
        }
    @endphp

    <div class="pres-totales">
        <div class="pt-cell"><div class="pt-lbl">Subtotal</div><div class="pt-val">${{ number_format($gSub,2) }}</div></div>
        <div class="pt-cell"><div class="pt-lbl">IVA</div><div class="pt-val">${{ number_format($gIva,2) }}</div></div>
        <div class="pt-cell"><div class="pt-lbl">Total Final</div><div class="pt-val">${{ number_format($gTot,2) }}</div></div>
        <div class="pt-cell"><div class="pt-lbl">Conceptos</div><div class="pt-val">{{ $conceptos->count() }}</div></div>
    </div>

    @if($conceptos->isEmpty())
        <div style="text-align:center; padding:40px; color:#9ca3af;">
            <h4>Sin Presupuesto</h4>
            <a href="{{ route('obras.presupuesto.export_excel', $obra->id) }}" class="btn-filter" style="border-color:#107c41; color:#107c41;"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
            <a href="{{ route('obras.presupuesto.export_pdf', $obra->id) }}" class="btn-filter" style="border-color:#da0b20; color:#da0b20;"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
            <a href="{{ route('obras.presupuesto.create', $obra->id) }}" class="btn-add-new"><i class="bi bi-plus-circle me-1"></i> Conceptos</a>
            <a href="{{ route('obras.presupuesto.unificado.create', $obra->id) }}" class="btn-add-new"><i class="bi bi-plus-circle me-1"></i> Insumos a Concepto</a>
        </div>
    @else
        <table class="pres-tabla">
            @foreach($porNivel as $idNivel => $nivelData)
                <tr style="background:#e5e7eb;color:#111;">
                    <td colspan="4" style="padding:14px;font-weight:900;font-size:1.1rem;"><i class="bi bi-layers me-1"></i> {{ mb_strtoupper($nivelData['nombre']) }}</td>
                    <td style="text-align:right;font-weight:800;">${{ number_format($nivelData['subtotal'],2) }}</td>
                    <td style="text-align:right;font-weight:800;">${{ number_format($nivelData['iva'],2) }}</td>
                    <td style="text-align:right;font-weight:800;color:#2563eb;">${{ number_format($nivelData['total'],2) }}</td>
                </tr>

                @foreach($nivelData['bloques'] as $bloqueId => $bloqueData)
                    <tr class="bloque-total">
                        <td colspan="4" style="text-align:center;">{{ strtoupper($bloqueData['nombre']) }}</td>
                        <td>${{ number_format($bloqueData['filas']->sum('subtotal'),2) }}</td>
                        <td>${{ number_format($bloqueData['filas']->sum('iva'),2) }}</td>
                        <td>${{ number_format($bloqueData['filas']->sum('total_final'),2) }}</td>
                    </tr>

                    @foreach($bloqueData['filas'] as $fila)
                        <tr class="item-row">
                            <td style="font-weight:700;">{{ $fila->area?->abreviatura ?? '—' }}</td>
                            <td style="font-weight:600; font-size:.85rem;">
                                {{ $fila->concepto?->descripcion ?? 'Concepto sin nombre' }}
                                <button type="button" class="btn-pa-outline btn-desglose-icon" style="padding:2px 5px; font-size:.65rem;" onclick="document.getElementById('desglose_{{ $fila->id }}').classList.toggle('active')">Desglose <i class="bi bi-chevron-down"></i></button>
                            </td>
                            <td style="text-align:center;">{{ $fila->cantidad }} {{ $fila->concepto?->unidadMedida?->abreviatura ?? 'UM' }}</td>
                            <td style="text-align:right;">${{ number_format($fila->precio_unitario,2) }}</td>
                            <td style="text-align:right;">${{ number_format($fila->subtotal,2) }}</td>
                            <td style="text-align:right;">${{ number_format($fila->iva,2) }}</td>
                            <td style="text-align:right;">${{ number_format($fila->total_final,2) }}</td>
                        </tr>
                        <tr class="tr-desglose" id="desglose_{{ $fila->id }}">
                            <td colspan="7">
                                <strong>Matriz de Costos:</strong><br>
                                @foreach($fila->materiales as $mat)
                                    <span class="comp-badge cb-mat">Material</span> {{ $mat->material?->nombre }} ({{ $mat->cantidad }} {{ $mat->material?->unidadMedida?->abreviatura }})<br>
                                @endforeach
                                @foreach($fila->maquinaria as $maq)
                                    <span class="comp-badge cb-maq">Maquinaria</span> {{ $maq->maquinaria?->nombre }} ({{ $maq->cantidad }} {{ $maq->maquinaria?->unidadMedida?->abreviatura }})<br>
                                @endforeach
                                @foreach($fila->manoObra as $mo)
                                    <span class="comp-badge cb-mo">Mano de Obra</span> {{ $mo->manoObra?->nombre }} ({{ $mo->cantidad }} {{ $mo->manoObra?->unidadMedida?->abreviatura }})<br>
                                @endforeach
                                @if($fila->materiales->isEmpty() && $fila->maquinaria->isEmpty() && $fila->manoObra->isEmpty())
                                    <em style="color:#9ca3af;">Este concepto no tiene insumos desglosados, se está usando su precio unitario base.</em>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach

            <tr class="gran-total">
                <td colspan="4">TOTAL GENERAL</td>
                <td>${{ number_format($gSub,2) }}</td>
                <td>${{ number_format($gIva,2) }}</td>
                <td>${{ number_format($gTot,2) }}</td>
            </tr>
        </table>
    @endif
</div>

<script>
let todosDesglosados = false;
function toggleTodosDesgloses() {
    todosDesglosados = !todosDesglosados;
    document.querySelectorAll('.tr-desglose').forEach(el => {
        if(todosDesglosados) el.classList.add('active');
        else el.classList.remove('active');
    });
}
</script>
@endsection
