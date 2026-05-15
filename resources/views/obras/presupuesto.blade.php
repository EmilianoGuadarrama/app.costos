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
.pc-val.neg { color:#dc2626; }
.pc-val.fecha { font-size:1.2rem; }

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
.btn-pa-dark:hover { background:#374151; color:#fff; }
.btn-pa-blue { background:#2563eb; color:#fff; }
.btn-pa-blue:hover { background:#1d4ed8; color:#fff; }
.btn-pa-green { background:#059669; color:#fff; }
.btn-pa-green:hover { background:#047857; color:#fff; }
.btn-back-sm { color:#6b7280; text-decoration:none; font-size:.85rem; display:flex; align-items:center; gap:4px; }
.btn-back-sm:hover { color:#111; }

/* Info bar */
.pres-info { display:flex; gap:20px; flex-wrap:wrap; padding:9px 14px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin-bottom:12px; font-size:.82rem; }
.pi-item { display:flex; flex-direction:column; }
.pi-label { font-size:.63rem; text-transform:uppercase; letter-spacing:1px; color:#9ca3af; font-weight:700; }
.pi-val { font-weight:700; color:#111; }

/* Presupuesto tabla */
.pres-tabla { width:100%; border-collapse:collapse; font-size:.8rem; min-width:780px; }

/* Encabezado de bloque */
.bloque-header td {
    background:#1c1c1c; color:#fff;
    font-size:.72rem; font-weight:900; text-transform:uppercase; letter-spacing:.5px;
    padding:8px 12px; border:1px solid #333;
}
.bloque-header .th-desc { width:38%; }
.bloque-header .th-col  { text-align:center; }

/* Área sub-header */
.area-header td {
    background:#4b5563; color:#e5e7eb;
    font-size:.7rem; font-weight:700;
    padding:5px 10px; border:1px solid #6b7280;
}
.area-header td:not(:first-child) { text-align:right; font-size:.68rem; }

/* Fila de ítem */
.item-row td {
    padding:7px 10px; border-bottom:1px solid #f0f0f0; border-right:1px solid #f0f0f0;
    background:#fff; vertical-align:middle;
}
.item-row:hover td { background:#f9fafb; }

@media print {
    input[type="number"], input[type="text"] {
        border: none !important;
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
        width: auto !important;
        -webkit-appearance: none;
        -moz-appearance: textfield;
    }
    .btn-pa { display: none !important; }
}
.item-row td:not(:first-child):not(:nth-child(2)) { text-align:right; font-variant-numeric:tabular-nums; }
.tipo-badge { font-size:.6rem; font-weight:700; text-transform:uppercase; padding:1px 6px; border-radius:4px; }
.tipo-concepto   { background:#dbeafe; color:#1d4ed8; }
.tipo-material   { background:#d1fae5; color:#065f46; }
.tipo-maquinaria { background:#fef3c7; color:#92400e; }

/* Subtotal área */
.area-sub td {
    background:#f3f4f6; font-weight:700; font-size:.75rem;
    padding:5px 10px; border-bottom:1px solid #e5e7eb;
}
.area-sub td:not(:first-child) { text-align:right; font-variant-numeric:tabular-nums; }

/* Total de bloque */
.bloque-total td {
    background:#374151; color:#fff; font-weight:900; font-size:.78rem;
    padding:7px 10px; border:1px solid #4b5563;
}
.bloque-total td:not(:first-child) { text-align:right; font-variant-numeric:tabular-nums; }

/* Gran total */
.gran-total td {
    background:#111827; color:#fff; font-weight:900; font-size:.88rem;
    padding:10px 12px; border:1px solid #374151;
}
.gran-total td:not(:first-child) { text-align:right; font-variant-numeric:tabular-nums; }
.gran-total td:last-child { color:#fbbf24; }

.empty-presup { text-align:center; padding:60px; color:#9ca3af; }

/* Success alert */
.alert-ok { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:10px 16px; color:#166534; font-size:.85rem; margin-bottom:12px; }
</style>

<div class="pres-wrap">

    @if(session('success'))
    <div class="alert-ok"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div style="background:#fef2f2;color:#b91c1c;padding:12px;border-radius:8px;margin-bottom:12px;">
        <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
    </div>
    @endif

    <!-- Acciones -->
    <div class="pres-actions">
        <a href="{{ route('obras.show', $obra->id) }}" class="btn-back-sm">
            <i class="bi bi-arrow-left"></i> Datos generales
        </a>
        <div style="flex:1"></div>
        <a href="{{ route('obras.presupuesto.unificado.create', $obra->id) }}" class="btn-pa btn-pa-blue" id="btn-agregar-todo">
            <i class="bi bi-layers"></i> + Agregar Renglones
        </a>
        <a href="#" onclick="exportarExcel()" class="btn-pa btn-pa-green" id="btn-exportar">
            <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="#" onclick="window.print()" class="btn-pa btn-pa-dark" id="btn-imprimir">
            <i class="bi bi-printer"></i> PDF / Imprimir
        </a>
        <button type="submit" form="form-update-all" class="btn-pa" style="background:#000;color:#fff;">
            <i class="bi bi-save"></i> Guardar Cambios
        </button>
    </div>

    @php
        $hoy       = now();
        $duracion  = (int) $obra->duracion;
        $diasTrans = $obra->dias_transcurridos;
        $diasFalt  = $obra->dias_faltan;

        // ── Unificar las 3 colecciones en una sola ──────────────────────────
        $todasFilas = collect();

        foreach ($conceptos as $c) {
            $todasFilas->push([
                'id'          => $c->id,
                'tipo'        => 'concepto',
                'id_nivel'    => (int)($c->id_nivel ?? 0),
                'nivel_desc'  => $c->nivel?->descripcion ?? 'General / Sin Nivel',
                'id_bloque'   => (int)($c->id_bloque ?? 0),
                'bloque'      => $c->bloque?->descripcion ?? 'Sin Bloque',
                'id_area'     => (int)($c->id_area ?? 0),
                'area_abr'    => $c->area?->abreviatura ?? '—',
                'area_desc'   => $c->area?->descripcion ?? '—',
                'descripcion' => $c->concepto?->descripcion ?? '—',
                'cantidad'    => (float)$c->cantidad,
                'pu'          => (float)$c->precio_unitario,
                'um'          => $c->concepto?->unidadMedida?->abreviatura ?? '—',
                'subtotal'    => (float)$c->subtotal,
                'iva'         => (float)$c->iva,
                'total'       => (float)$c->total_final,
            ]);
        }
        foreach ($materiales as $m) {
            $todasFilas->push([
                'id'          => $m->id,
                'tipo'        => 'material',
                'id_nivel'    => (int)($m->id_nivel ?? 0),
                'nivel_desc'  => $m->nivel?->descripcion ?? 'General / Sin Nivel',
                'id_bloque'   => (int)($m->id_bloque ?? 0),
                'bloque'      => $m->bloque?->descripcion ?? 'Sin Bloque',
                'id_area'     => (int)($m->id_area ?? 0),
                'area_abr'    => $m->area?->abreviatura ?? '—',
                'area_desc'   => $m->area?->descripcion ?? '—',
                'descripcion' => $m->material?->nombre ?? '—',
                'cantidad'    => (float)$m->cantidad,
                'pu'          => (float)$m->precio_unitario,
                'um'          => $m->material?->unidadMedida?->abreviatura ?? '—',
                'subtotal'    => (float)$m->subtotal,
                'iva'         => (float)$m->iva,
                'total'       => (float)$m->total_final,
            ]);
        }
        foreach ($maquinaria as $ma) {
            $todasFilas->push([
                'id'          => $ma->id,
                'tipo'        => 'maquinaria',
                'id_nivel'    => (int)($ma->id_nivel ?? 0),
                'nivel_desc'  => $ma->nivel?->descripcion ?? 'General / Sin Nivel',
                'id_bloque'   => (int)($ma->id_bloque ?? 0),
                'bloque'      => $ma->bloque?->descripcion ?? 'Sin Bloque',
                'id_area'     => (int)($ma->id_area ?? 0),
                'area_abr'    => $ma->area?->abreviatura ?? '—',
                'area_desc'   => $ma->area?->descripcion ?? '—',
                'descripcion' => $ma->maquinaria?->nombre ?? '—',
                'cantidad'    => (float)$ma->cantidad,
                'pu'          => (float)$ma->precio_unitario,
                'um'          => $ma->maquinaria?->unidadMedida?->abreviatura ?? '—',
                'subtotal'    => (float)$ma->subtotal,
                'iva'         => (float)$ma->iva,
                'total'       => (float)$ma->total_final,
            ]);
        }

        // ── Agrupar por nivel -> bloque ──────────────
        $nivelesList = $obra->niveles->keyBy('id');
        $porNivel = collect();

        foreach ($todasFilas->groupBy('id_nivel') as $idNiv => $filasNiv) {
            $nomNivel = $idNiv ? ($nivelesList[$idNiv]->descripcion ?? "Nivel $idNiv") : 'GENERAL / SIN NIVEL';
            
            $porBloqueOrdenado = collect();
            foreach ($bloques as $blq) {
                $filasBloq = $filasNiv->where('id_bloque', $blq->id);
                if ($filasBloq->isNotEmpty()) {
                    $porBloqueOrdenado->put($blq->id, [
                        'nombre' => $blq->descripcion,
                        'filas'  => $filasBloq,
                    ]);
                }
            }
            $sinBloque = $filasNiv->where('id_bloque', 0);
            if ($sinBloque->isNotEmpty()) {
                $porBloqueOrdenado->put(0, ['nombre' => 'Sin Bloque', 'filas' => $sinBloque]);
            }
            
            $porNivel->put($idNiv, [
                'nombre' => $nomNivel,
                'bloques' => $porBloqueOrdenado,
                'subtotal' => $filasNiv->sum('subtotal'),
                'iva' => $filasNiv->sum('iva'),
                'total' => $filasNiv->sum('total'),
            ]);
        }

        $gSub = $todasFilas->sum('subtotal');
        $gIva = $todasFilas->sum('iva');
        $gTot = $todasFilas->sum('total');
    @endphp

    <!-- Info obra -->
    <div class="pres-info">
        <div class="pi-item">
            <span class="pi-label">Obra</span>
            <span class="pi-val">{{ $obra->datosDeObra?->nombre ?? "Obra #$obra->id" }}</span>
        </div>
        <div class="pi-item">
            <span class="pi-label">Encargado</span>
            <span class="pi-val">{{ $obra->encargado?->persona?->nombre ?? '—' }}</span>
        </div>
        <div class="pi-item">
            <span class="pi-label">Inicio</span>
            <span class="pi-val">{{ $obra->fecha_inicio?->format('d/m/Y') ?? '—' }}</span>
        </div>
        <div class="pi-item">
            <span class="pi-label">Niveles</span>
            <span class="pi-val">{{ $obra->niveles->count() }}</span>
        </div>
        <div class="pi-item">
            <span class="pi-label">Bloques activos</span>
            <span class="pi-val">{{ $porBloqueOrdenado->count() }}</span>
        </div>
        <div class="pi-item">
            <span class="pi-label">Renglones</span>
            <span class="pi-val">{{ $todasFilas->count() }}</span>
        </div>
    </div>

    <!-- Contadores HOY / FALTAN / DURACIÓN / TRANSCURRIDOS -->
    <div class="pres-counters">
        <div class="pc-box"><div class="pc-lbl">Hoy</div><div class="pc-val fecha">{{ $hoy->format('n/j/Y') }}</div></div>
        <div class="pc-box"><div class="pc-lbl">Días que faltan</div>
            <div class="pc-val {{ $diasFalt !== null && $diasFalt < 0 ? 'neg' : '' }}">{{ $diasFalt ?? '—' }}</div></div>
        <div class="pc-box"><div class="pc-lbl">Lo que dura</div><div class="pc-val">{{ $duracion ?: '—' }}</div></div>
        <div class="pc-box"><div class="pc-lbl">Transcurridos</div><div class="pc-val">{{ max(0,$diasTrans) }}</div></div>
    </div>

    <!-- Totales generales -->
    <div class="pres-totales">
        <div class="pt-cell"><div class="pt-lbl">Sin IVA</div><div class="pt-val">${{ number_format($gSub,2) }}</div></div>
        <div class="pt-cell"><div class="pt-lbl">IVA</div><div class="pt-val">${{ number_format($gIva,2) }}</div></div>
        <div class="pt-cell"><div class="pt-lbl">Total Final</div><div class="pt-val">${{ number_format($gTot,2) }}</div></div>
        <div class="pt-cell"><div class="pt-lbl">Renglones</div><div class="pt-val">{{ $todasFilas->count() }}</div></div>
    </div>

    @if($todasFilas->isEmpty())
        <div class="empty-presup">
            <i class="bi bi-file-earmark-text" style="font-size:3.5rem;color:#d1d5db;display:block;margin-bottom:12px;"></i>
            <h4>Sin renglones en el presupuesto</h4>
            <p>Agrega conceptos o materiales para construir el presupuesto por bloques.</p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:14px;">
                <a href="{{ route('obras.presupuesto.create', $obra->id) }}" class="btn-pa btn-pa-blue">
                    <i class="bi bi-plus-lg"></i> Agregar Conceptos
                </a>
                <a href="{{ route('obras.presupuesto.materiales.create', $obra->id) }}" class="btn-pa btn-pa-green">
                    <i class="bi bi-box-seam"></i> Agregar Materiales
                </a>
            </div>
        </div>
    @else
    <div style="overflow-x:auto; margin-top:2px;">
        <form id="form-update-all" method="POST" action="{{ route('obras.presupuesto.updateAll', $obra->id) }}">
        @csrf
        <table class="pres-tabla" id="tabla-presupuesto">
            @php $gSub2=0; $gIva2=0; $gTot2=0; @endphp

            @foreach($porNivel as $idNivel => $nivelData)
                {{-- Encabezado de Nivel --}}
                <tr style="background:#e5e7eb;color:#111;">
                    <td colspan="6" style="padding:14px;font-weight:900;font-size:1.1rem;letter-spacing:1px;">
                        <i class="bi bi-layers me-1"></i> {{ mb_strtoupper($nivelData['nombre']) }}
                    </td>
                    <td style="text-align:right;font-weight:800;">${{ number_format($nivelData['subtotal'],2) }}</td>
                    <td style="text-align:right;font-weight:800;">${{ number_format($nivelData['iva'],2) }}</td>
                    <td style="text-align:right;font-weight:800;color:#2563eb;">${{ number_format($nivelData['total'],2) }}</td>
                </tr>

                @foreach($nivelData['bloques'] as $bloqueId => $bloqueData)
                @php
                    $bloqueNombre = $bloqueData['nombre'];
                    $filasBloq    = $bloqueData['filas'];
                    $bSub = $filasBloq->sum('subtotal');
                    $bIva = $filasBloq->sum('iva');
                    $bTot = $filasBloq->sum('total');
                    $gSub2 += $bSub; $gIva2 += $bIva; $gTot2 += $bTot;
                @endphp

                {{-- Encabezado de bloque --}}
                <tr class="bloque-total" style="background:#111827;color:#fff;">
                    <td style="text-align:center;width:40px;">{{ $bloqueId ?: '-' }}</td>
                    <td colspan="4" style="text-align:center;font-weight:900;font-size:.85rem;letter-spacing:1px;">{{ strtoupper($bloqueNombre) }}</td>
                    <td style="text-align:right;font-weight:900;color:#9ca3af;">TOTAL {{ strtoupper(substr($bloqueNombre,0,3)) }}</td>
                    <td style="text-align:right;">${{ number_format($bSub,2) }}</td>
                    <td style="text-align:right;">${{ number_format($bIva,2) }}</td>
                    <td style="text-align:right;">${{ number_format($bTot,2) }}</td>
                </tr>

                {{-- Renglones (Sin agrupar por área, iteración directa del bloque) --}}
                @foreach($filasBloq as $fila)
                <tr class="item-row">
                    <td style="font-weight:700;color:#111;">{{ $fila['area_abr'] }}</td>
                    <td style="color:#6b7280;font-size:.7rem;text-transform:uppercase;">
                        @if($fila['tipo'] === 'concepto') CONCEPTO @else {{ $fila['tipo'] }} @endif
                    </td>
                    <td style="color:#111;font-size:.8rem;font-weight:600;">{{ $fila['descripcion'] }}</td>
                    <td style="text-align:right;">
                        <input type="number" step="0.01" min="0" name="items[{{ $fila['tipo'] }}][{{ $fila['id'] }}][pu]" value="{{ $fila['pu'] }}" style="width:90px;padding:4px;border:1px solid #d1d5db;border-radius:4px;text-align:right;">
                    </td>
                    <td style="text-align:center;">
                        <input type="number" step="0.01" min="0" name="items[{{ $fila['tipo'] }}][{{ $fila['id'] }}][cantidad]" value="{{ $fila['cantidad'] }}" style="width:70px;padding:4px;border:1px solid #d1d5db;border-radius:4px;text-align:center;">
                    </td>
                    <td style="text-align:center;">{{ $fila['um'] }}</td>
                    <td style="text-align:right;">${{ number_format($fila['subtotal'],2) }}</td>
                    <td style="text-align:right;">${{ number_format($fila['iva'],2) }}</td>
                    <td style="text-align:right;">${{ number_format($fila['total'],2) }}</td>
                </tr>
                @endforeach

                @endforeach {{-- por bloque --}}
            @endforeach {{-- por nivel --}}

            {{-- Gran Total --}}
            <tr class="gran-total" style="background:#000;color:#fff;">
                <td colspan="6" style="text-align:right;padding:16px;font-size:1.1rem;font-weight:900;">TOTAL GENERAL DE LA OBRA</td>
                <td style="text-align:right;font-size:1.1rem;font-weight:900;">${{ number_format($gSub2,2) }}</td>
                <td style="text-align:right;font-size:1.1rem;font-weight:900;">${{ number_format($gIva2,2) }}</td>
                <td style="text-align:right;color:#fbbf24;font-size:1.2rem;font-weight:900;">${{ number_format($gTot2,2) }}</td>
            </tr>

        </table>
        </form>
    </div>
    
    <script>
    function exportarExcel() {
        let tabla = document.getElementById("tabla-presupuesto");
        let html = tabla.outerHTML;
        
        // Reemplazar inputs por sus valores para que se vea bien en excel
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        let inputs = tempDiv.querySelectorAll('input');
        inputs.forEach(input => {
            let val = input.value;
            let parent = input.parentNode;
            parent.innerHTML = val;
        });
        
        html = tempDiv.innerHTML;
        
        let url = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(html);
        let link = document.createElement("a");
        link.href = url;
        link.download = "presupuesto_{{ $obra->id }}.xls";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    </script>
    @endif
</div>
@endsection
