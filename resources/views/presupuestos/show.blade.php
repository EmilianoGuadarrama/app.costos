@extends('layout')
@section('title', 'Presupuesto — ' . $presupuesto->nombre)
@section('content')
<style>
/* ── Presupuesto — Vista de Detalle ── */
.presup-wrap {
    font-family: "Arial", sans-serif;
    font-size: .82rem;
    color: #111;
}

/* ── Barra de acciones ── */
.presup-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.btn-pa-back {
    text-decoration: none;
    color: #6b7280;
    font-size: .82rem;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 500;
    transition: color .2s;
}
.btn-pa-back:hover { color: #111; }

.btn-pa-action {
    background: #fff;
    color: #374151;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    padding: .42rem 1rem;
    font-size: .78rem;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    letter-spacing: .4px;
    text-transform: uppercase;
    transition: all .2s;
    cursor: pointer;
}
.btn-pa-action:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #111;
}
.btn-pa-action.dark {
    background: #111;
    color: #fff;
    border-color: #111;
}
.btn-pa-action.dark:hover {
    background: #374151;
    border-color: #374151;
    color: #fff;
}
.btn-pa-action.success {
    background: #fff;
    color: #065f46;
    border-color: #a7f3d0;
}
.btn-pa-action.success:hover {
    background: #ecfdf5;
    border-color: #6ee7b7;
}

/* ── Info bar ── */
.presup-info-bar {
    display: flex;
    gap: 0;
    flex-wrap: wrap;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 20px;
    overflow: hidden;
    background: #fff;
}
.presup-info-item {
    display: flex;
    flex-direction: column;
    padding: 14px 20px;
    border-right: 1px solid #e5e7eb;
    flex: 1;
    min-width: 120px;
}
.presup-info-item:last-child { border-right: none; }
.presup-info-label {
    font-size: .65rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #9ca3af;
    font-weight: 700;
    margin-bottom: 4px;
}
.presup-info-value {
    font-weight: 700;
    color: #111;
    font-size: .9rem;
}

/* ── Tarjetas de contadores de tiempo ── */
.presup-counters {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}
.presup-counter-box {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 14px 16px;
    text-align: center;
}
.presup-counter-lbl {
    font-size: .65rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #9ca3af;
    font-weight: 700;
    margin-bottom: 6px;
}
.presup-counter-val {
    font-size: 1.45rem;
    font-weight: 900;
    color: #111;
    font-variant-numeric: tabular-nums;
}
.presup-counter-val.negativo { color: #b91c1c; }

/* ── Barra de totales generales ── */
.presup-totales-bar {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
.ptb-cell {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 14px 18px;
    text-align: center;
}
.ptb-cell.highlight {
    background: #111;
    border-color: #111;
}
.ptb-label {
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #9ca3af;
    margin-bottom: 5px;
}
.ptb-cell.highlight .ptb-label { color: #6b7280; }
.ptb-value {
    font-size: 1.05rem;
    font-weight: 900;
    color: #111;
    font-variant-numeric: tabular-nums;
}
.ptb-cell.highlight .ptb-value { color: #fff; }

/* ── Tabla de presupuesto ── */
.presup-table-wrap {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 4px;
}

/* Header de bloque */
.bloque-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 1fr 80px 80px 90px;
    background: #1c1c1c;
    color: #fff;
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.bloque-header .bh-name {
    padding: 10px 14px;
    font-size: .8rem;
    font-weight: 800;
    letter-spacing: .8px;
    border-right: 1px solid #333;
    grid-column: 1;
    align-self: center;
}
.bh-col {
    padding: 10px 6px;
    text-align: center;
    border-right: 1px solid #333;
    align-self: center;
    line-height: 1.3;
}
.bh-col:last-child { border-right: none; }

/* Sub-área header */
.area-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 1fr 80px 80px 90px;
    background: #f3f4f6;
    color: #374151;
    font-size: .68rem;
    font-weight: 700;
    border-bottom: 1px solid #e5e7eb;
    border-top: 1px solid #e5e7eb;
}
.area-header .ah-name {
    padding: 7px 14px;
    grid-column: 1;
    border-right: 1px solid #e5e7eb;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #6b7280;
}
.ah-col {
    padding: 7px 6px;
    text-align: right;
    border-right: 1px solid #e5e7eb;
    font-size: .68rem;
    color: #374151;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}
.ah-col:last-child { border-right: none; }

/* Fila de concepto */
.concepto-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 1fr 80px 80px 90px;
    border-bottom: 1px solid #f3f4f6;
    background: #fff;
    transition: background .15s;
}
.concepto-row:hover { background: #fafafa; }
.concepto-row.cantidad-cero { opacity: .45; }

.cr-desc {
    padding: 9px 14px;
    border-right: 1px solid #f3f4f6;
    color: #111;
    font-size: .78rem;
    line-height: 1.35;
}
.cr-col {
    padding: 9px 6px;
    text-align: right;
    border-right: 1px solid #f3f4f6;
    font-variant-numeric: tabular-nums;
    font-size: .78rem;
    color: #374151;
}
.cr-col:last-child { border-right: none; }
.cr-col.center { text-align: center; }
.cr-col.comprado { background: #f0fdf4; color: #065f46; font-weight: 700; }
.cr-col.saldo    { background: #eff6ff; color: #1d4ed8; }
.cr-col.saldo-\$ { background: #fefce8; color: #92400e; }

/* Totales de bloque */
.bloque-totales {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 1fr 80px 80px 90px;
    background: #374151;
    color: #fff;
    font-weight: 800;
    font-size: .75rem;
    border-top: 1px solid #4b5563;
}
.bt-label {
    padding: 9px 14px;
    border-right: 1px solid #4b5563;
    text-transform: uppercase;
    font-size: .68rem;
    letter-spacing: .8px;
}
.bt-col {
    padding: 9px 6px;
    text-align: right;
    border-right: 1px solid #4b5563;
    font-variant-numeric: tabular-nums;
}
.bt-col:last-child { border-right: none; }

/* Gran total */
.gran-total {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 1fr 80px 80px 90px;
    background: #111;
    color: #fff;
    font-weight: 900;
    font-size: .85rem;
}
.gt-label {
    padding: 13px 14px;
    border-right: 1px solid #374151;
    text-transform: uppercase;
    letter-spacing: .8px;
    font-size: .75rem;
}
.gt-col {
    padding: 13px 6px;
    text-align: right;
    border-right: 1px solid #374151;
    font-variant-numeric: tabular-nums;
}
.gt-col:last-child { border-right: none; }
.gt-col.accent { color: #fbbf24; }

/* Estado vacío */
.empty-presup {
    text-align: center;
    padding: 60px 40px;
    color: #9ca3af;
    background: #fafafa;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    margin-top: 12px;
}
.empty-presup h4 { color: #374151; font-size: 1.15rem; margin-bottom: 8px; }
.empty-presup p  { font-size: .88rem; margin-bottom: 16px; }

/* Observaciones */
.presup-obs {
    margin-top: 20px;
    background: #fafafa;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: .88rem;
    color: #374151;
    border-left: 3px solid #d1d5db;
}

.bloque-section { margin-bottom: 0; }

@media (max-width: 900px) {
    .presup-counters, .presup-totales-bar { grid-template-columns: 1fr 1fr; }
    .bloque-header, .area-header, .concepto-row, .bloque-totales, .gran-total {
        min-width: 900px;
    }
}
</style>

<div class="presup-wrap">

    {{-- ── Barra de acciones ── --}}
    <div class="presup-actions">
        <a href="{{ route('proyectos.show', $presupuesto->proyecto_id) }}" class="btn-pa-back" id="btn-volver-proyecto">
            <i class="bi bi-arrow-left"></i> Proyecto
        </a>
        <div style="flex:1"></div>
        <a href="{{ route('presupuestos.edit', $presupuesto->id) }}" class="btn-pa-action" id="btn-editar-presupuesto">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="#" onclick="window.print()" class="btn-pa-action success" id="btn-imprimir-presupuesto">
            <i class="bi bi-printer"></i> Imprimir
        </a>
    </div>

    @php
        $proyecto        = $presupuesto->proyecto;
        $hoy             = now();
        $fechaInicio     = $proyecto?->fecha_inicio ? \Carbon\Carbon::parse($proyecto->fecha_inicio) : null;
        $duracion        = (int) ($proyecto?->duracion_estimada ?? 0);
        $diasTranscurr   = $fechaInicio ? (int) $fechaInicio->diffInDays($hoy, false) : 0;
        $diasFaltan      = $duracion > 0 ? $duracion - $diasTranscurr : null;

        // Agrupar detalles: bloque → área → conceptos
        $detalles = $presupuesto->detalles->load([
            'concepto.area', 'concepto.unidadMedida', 'bloque', 'nivel'
        ]);

        // Totales globales
        $totalInicial = 0;
        $totalIva     = 0;
        $totalFinal   = 0;
        $totalComprado = 0;
        $totalSaldo    = 0;
        $totalSaldoMonto = 0;

        foreach ($detalles as $d) {
            $sub = $d->subtotal_calculado;
            $iva = $d->iva_calculado;
            $totalInicial  += $sub;
            $totalIva      += $iva;
            $totalFinal    += $sub + $iva;
            $totalComprado += (float)$d->cantidad_comprada;
            $totalSaldo    += (float)$d->saldo_cantidad;
            $totalSaldoMonto += (float)$d->saldo_monto;
        }

        // Agrupar por bloque
        $porBloque = $detalles->groupBy(function($d) {
            return $d->bloque ? ($d->bloque->orden . '|' . $d->bloque->id . '|' . $d->bloque->nombre) : '99|0|Sin Bloque';
        })->sortKeys();
    @endphp

    {{-- ── Info del presupuesto ── --}}
    <div class="presup-info-bar">
        <div class="presup-info-item">
            <span class="presup-info-label">Proyecto</span>
            <span class="presup-info-value">{{ $proyecto?->nombre ?? '—' }}</span>
        </div>
        <div class="presup-info-item">
            <span class="presup-info-label">Cliente</span>
            <span class="presup-info-value">{{ $proyecto?->cliente?->nombre ?? '—' }}</span>
        </div>
        <div class="presup-info-item">
            <span class="presup-info-label">Presupuesto</span>
            <span class="presup-info-value">{{ $presupuesto->nombre }}</span>
        </div>
        @if($presupuesto->fecha)
        <div class="presup-info-item">
            <span class="presup-info-label">Fecha</span>
            <span class="presup-info-value">{{ \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') }}</span>
        </div>
        @endif
        <div class="presup-info-item">
            <span class="presup-info-label">Estado</span>
            <span class="presup-info-value">{{ ucfirst($presupuesto->estado ?? 'borrador') }}</span>
        </div>
    </div>

    {{-- ── Contadores de tiempo ── --}}
    <div class="presup-counters">
        <div class="presup-counter-box">
            <div class="presup-counter-lbl">Fecha actual</div>
            <div class="presup-counter-val" style="font-size:1.15rem;">{{ $hoy->format('d/m/Y') }}</div>
        </div>
        <div class="presup-counter-box">
            <div class="presup-counter-lbl">Días restantes</div>
            <div class="presup-counter-val {{ $diasFaltan !== null && $diasFaltan < 0 ? 'negativo' : '' }}">
                {{ $diasFaltan ?? '—' }}
            </div>
        </div>
        <div class="presup-counter-box">
            <div class="presup-counter-lbl">Duración estimada</div>
            <div class="presup-counter-val">{{ $duracion ?: '—' }}</div>
        </div>
        <div class="presup-counter-box">
            <div class="presup-counter-lbl">Días transcurridos</div>
            <div class="presup-counter-val">{{ max(0, $diasTranscurr) }}</div>
        </div>
    </div>

    {{-- ── Totales generales ── --}}
    <div class="presup-totales-bar">
        <div class="ptb-cell">
            <div class="ptb-label">Sin IVA</div>
            <div class="ptb-value">${{ number_format($totalInicial, 2) }}</div>
        </div>
        <div class="ptb-cell">
            <div class="ptb-label">IVA</div>
            <div class="ptb-value">${{ number_format($totalIva, 2) }}</div>
        </div>
        <div class="ptb-cell highlight">
            <div class="ptb-label">Total Final</div>
            <div class="ptb-value">${{ number_format($totalFinal, 2) }}</div>
        </div>
        <div class="ptb-cell">
            <div class="ptb-label">Saldo $</div>
            <div class="ptb-value">${{ number_format($totalSaldoMonto, 2) }}</div>
        </div>
    </div>

    @if($detalles->isEmpty())
        <div class="empty-presup">
            <i class="bi bi-file-earmark-text" style="font-size:3rem;color:#d1d5db;display:block;margin-bottom:14px;"></i>
            <h4>Sin renglones en este presupuesto</h4>
            <p>Edita el presupuesto para agregar conceptos.</p>
            <a href="{{ route('presupuestos.edit', $presupuesto->id) }}" class="btn-pa-action dark" style="display:inline-flex;margin:0 auto;">
                <i class="bi bi-pencil"></i> Agregar conceptos
            </a>
        </div>
    @else
    {{-- ═══ PRESUPUESTO POR BLOQUES ═══ --}}
    <div style="overflow-x:auto;">
        <div class="presup-table-wrap">

            @php $granTotalSub=0; $granTotalIva=0; $granTotalFin=0; $granTotalComp=0; $granSaldoCant=0; $granSaldoMonto=0; @endphp

            @foreach($porBloque as $bloqueKey => $detBloque)
            @php
                $parts        = explode('|', $bloqueKey);
                $bloqueNombre = $parts[2] ?? 'Sin Bloque';

                $bSub    = $detBloque->sum(fn($d) => $d->subtotal_calculado);
                $bIva    = $detBloque->sum(fn($d) => $d->iva_calculado);
                $bFin    = $detBloque->sum(fn($d) => $d->total_final_calculado);
                $bComp   = $detBloque->sum(fn($d) => (float)$d->cantidad_comprada);
                $bSaldC  = $detBloque->sum(fn($d) => (float)$d->saldo_cantidad);
                $bSaldM  = $detBloque->sum(fn($d) => (float)$d->saldo_monto);
                $granTotalSub   += $bSub;
                $granTotalIva   += $bIva;
                $granTotalFin   += $bFin;
                $granTotalComp  += $bComp;
                $granSaldoCant  += $bSaldC;
                $granSaldoMonto += $bSaldM;

                $porArea = $detBloque->groupBy(function($d) {
                    return ($d->concepto?->area?->clave ?? 'GEN') . '|' . ($d->concepto?->area?->nombre ?? 'General');
                });
            @endphp

            <div class="bloque-section">
                {{-- Header de bloque --}}
                <div class="bloque-header">
                    <div class="bh-name">{{ strtoupper($bloqueNombre) }}</div>
                    <div class="bh-col">P.U.</div>
                    <div class="bh-col">Cant.</div>
                    <div class="bh-col">U.M.</div>
                    <div class="bh-col">Sub. Ini.</div>
                    <div class="bh-col">IVA</div>
                    <div class="bh-col">Total Ini.</div>
                    <div class="bh-col" style="background:#14532d;color:#bbf7d0;">Compradas</div>
                    <div class="bh-col" style="background:#1e3a8a;color:#bfdbfe;">Saldo</div>
                    <div class="bh-col" style="background:#78350f;color:#fef3c7;">Saldo $</div>
                </div>

                {{-- Por cada área del bloque --}}
                @foreach($porArea as $areaKey => $detArea)
                @php
                    $aParts = explode('|', $areaKey);
                    $aAbr   = $aParts[0];
                    $aDesc  = $aParts[1] ?? '';
                    $aSub   = $detArea->sum(fn($d) => $d->subtotal_calculado);
                    $aIva   = $detArea->sum(fn($d) => $d->iva_calculado);
                    $aFin   = $detArea->sum(fn($d) => $d->total_final_calculado);
                @endphp

                {{-- Sub-encabezado de área --}}
                <div class="area-header">
                    <div class="ah-name">{{ $aAbr }} — {{ $aDesc }}</div>
                    <div class="ah-col"></div>
                    <div class="ah-col"></div>
                    <div class="ah-col"></div>
                    <div class="ah-col">${{ number_format($aSub, 2) }}</div>
                    <div class="ah-col">${{ number_format($aIva, 2) }}</div>
                    <div class="ah-col">${{ number_format($aFin, 2) }}</div>
                    <div class="ah-col"></div>
                    <div class="ah-col"></div>
                    <div class="ah-col"></div>
                </div>

                {{-- Renglones de concepto --}}
                @foreach($detArea as $det)
                @php
                    $pu    = $det->pu_efectivo;
                    $cant  = (float) $det->cantidad;
                    $sub   = $det->subtotal_calculado;
                    $iva   = $det->iva_calculado;
                    $fin   = $det->total_final_calculado;
                    $comp  = (float) $det->cantidad_comprada;
                    $saldC = (float) $det->saldo_cantidad;
                    $saldM = (float) $det->saldo_monto;
                    $um    = $det->concepto?->unidadMedida?->abreviatura ?? '—';
                @endphp
                <div class="concepto-row {{ $cant == 0 ? 'cantidad-cero' : '' }}">
                    <div class="cr-desc">{{ $det->concepto?->descripcion ?? '—' }}</div>
                    <div class="cr-col">${{ number_format($pu, 2) }}</div>
                    <div class="cr-col">{{ number_format($cant, 2) }}</div>
                    <div class="cr-col center">{{ $um }}</div>
                    <div class="cr-col">${{ number_format($sub, 2) }}</div>
                    <div class="cr-col">${{ number_format($iva, 2) }}</div>
                    <div class="cr-col">${{ number_format($fin, 2) }}</div>
                    <div class="cr-col comprado">{{ number_format($comp, 2) }}</div>
                    <div class="cr-col saldo">{{ number_format($saldC, 2) }}</div>
                    <div class="cr-col saldo-$">${{ number_format($saldM, 2) }}</div>
                </div>
                @endforeach
                @endforeach

                {{-- Totales del bloque --}}
                <div class="bloque-totales">
                    <div class="bt-label">Total {{ strtoupper($bloqueNombre) }}</div>
                    <div class="bt-col"></div>
                    <div class="bt-col"></div>
                    <div class="bt-col"></div>
                    <div class="bt-col">${{ number_format($bSub, 2) }}</div>
                    <div class="bt-col">${{ number_format($bIva, 2) }}</div>
                    <div class="bt-col">${{ number_format($bFin, 2) }}</div>
                    <div class="bt-col">{{ number_format($bComp, 2) }}</div>
                    <div class="bt-col">{{ number_format($bSaldC, 2) }}</div>
                    <div class="bt-col">${{ number_format($bSaldM, 2) }}</div>
                </div>
            </div>
            @endforeach

            {{-- Gran Total --}}
            <div class="gran-total">
                <div class="gt-label">Total General</div>
                <div class="gt-col"></div>
                <div class="gt-col"></div>
                <div class="gt-col"></div>
                <div class="gt-col">${{ number_format($granTotalSub, 2) }}</div>
                <div class="gt-col">${{ number_format($granTotalIva, 2) }}</div>
                <div class="gt-col accent">${{ number_format($granTotalFin, 2) }}</div>
                <div class="gt-col">{{ number_format($granTotalComp, 2) }}</div>
                <div class="gt-col">{{ number_format($granSaldoCant, 2) }}</div>
                <div class="gt-col accent">${{ number_format($granSaldoMonto, 2) }}</div>
            </div>

        </div>
    </div>
    @endif

    @if($presupuesto->observaciones)
    <div class="presup-obs">
        <strong>Observaciones:</strong> {{ $presupuesto->observaciones }}
    </div>
    @endif
</div>
@endsection
