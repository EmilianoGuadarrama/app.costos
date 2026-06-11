@extends('layout')
@section('title', 'Presupuesto — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root {
    --dark:#111111; --mid:#374151; --soft:#6b7280; --line:#e5e7eb;
    --bg:#f0f0f0;   --white:#fff;
    --blue:#374151; --green:#059669; --red:#b91c1c; --amber:#d97706;
}
body { background:var(--bg); font-family:'Inter','Segoe UI',sans-serif; }

/* ══════════════════════════════
   HEADER EMPRESA
══════════════════════════════ */
.pres-hdr {
    background:#111111; color:#fff;
    padding:0; position:sticky; top:0; z-index:200;
    border-bottom:3px solid #000;
    box-shadow:0 2px 14px rgba(0,0,0,.35);
}
/* Franja superior: logo + nombre empresa + botones */
.pres-hdr-top {
    display:flex; justify-content:space-between; align-items:center;
    padding:10px 24px 8px;
    border-bottom:1px solid rgba(255,255,255,.08);
}
.hdr-brand { display:flex; align-items:center; gap:14px; }
.hdr-logo {
    height:48px; width:auto; object-fit:contain;
    border-radius:4px;
    background:#fff;
    padding:3px 6px;
    flex-shrink:0;
}
.hdr-empresa-info {}
.hdr-empresa-nombre {
    font-size:1.15rem; font-weight:800; letter-spacing:.6px;
    text-transform:uppercase; color:#fff; line-height:1.1;
}
.hdr-empresa-sub {
    font-size:.65rem; color:#9ca3af; text-transform:uppercase;
    letter-spacing:.8px; margin-top:2px;
}
/* Fila de back + acciones */
.hdr-actions { display:flex; gap:8px; align-items:center; }
.btn-back {
    background:rgba(255,255,255,.08); color:#d1d5db;
    border:1px solid rgba(255,255,255,.15);
    border-radius:7px; padding:5px 13px; font-size:.8rem; text-decoration:none;
    transition:.2s; display:inline-flex; align-items:center; gap:5px;
}
.btn-back:hover { background:rgba(255,255,255,.18); color:#fff; }
.btn-hdr {
    border:none; border-radius:8px; padding:8px 16px; font-size:.78rem; font-weight:700;
    letter-spacing:.3px; text-transform:uppercase;
    cursor:pointer; display:inline-flex; align-items:center; gap:6px;
    text-decoration:none; transition:.2s;
}
.btn-hdr-blue  { background:#fff; color:#111; border:1px solid rgba(255,255,255,.3); }
.btn-hdr-blue:hover  { background:#f3f4f6; color:#111; }
.btn-hdr-green { background:var(--green); color:#fff; }
.btn-hdr-green:hover { background:#047857; color:#fff; }
.btn-hdr-dark  { background:rgba(255,255,255,.08); color:#d1d5db; border:1px solid rgba(255,255,255,.15); }
.btn-hdr-dark:hover  { background:rgba(255,255,255,.16); }
.btn-hdr-excel { background:#107c41; color:#fff; }
.btn-hdr-excel:hover { background:#0a5c30; color:#fff; }
.btn-hdr-pdf   { background:#b91c1c; color:#fff; }
.btn-hdr-pdf:hover   { background:#991b1b; color:#fff; }

/* Franja inferior del header: datos del proyecto */
.pres-hdr-info {
    display:grid;
    grid-template-columns: minmax(0,2fr) minmax(0,2fr) minmax(0,1.2fr) minmax(0,1.2fr) minmax(0,1fr) minmax(0,1fr);
    gap:0;
    border-top:1px solid rgba(255,255,255,.08);
    background:#1a1a1a;
}
.hdr-info-cell {
    padding:7px 16px;
    border-right:1px solid rgba(255,255,255,.07);
    display:flex; flex-direction:column; justify-content:center;
}
.hdr-info-cell:last-child { border-right:none; }
.hdr-info-lbl {
    font-size:.58rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.7px; color:#6b7280; margin-bottom:2px;
}
.hdr-info-val {
    font-size:.78rem; font-weight:600; color:#e5e7eb;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    max-width:100%;
}

/* ══════════════════════════════
   TOTALES BAR
══════════════════════════════ */
.totales-bar {
    display:grid; grid-template-columns:repeat(4,1fr);
    background:#fff;
    border-bottom:2px solid #111;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}
.tot-cell {
    padding:14px 18px; text-align:center;
    border-right:1px solid var(--line);
}
.tot-cell:last-child { border-right:none; }
.tot-lbl {
    font-size:.62rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.7px; color:var(--soft);
}
.tot-val {
    font-size:1.35rem; font-weight:900; color:var(--dark); margin-top:4px;
}
.tot-val.highlight { color:#111111; font-weight:900; }
.tot-cell-dark {
    background:#111; text-align:center; padding:14px 18px;
}
.tot-cell-dark .tot-lbl { color:#9ca3af; }
.tot-cell-dark .tot-val { color:#fff; }

/* ══════════════════════════════
   BODY
══════════════════════════════ */
.pres-body { padding:20px 26px; }

/* ══════════════════════════════
   TABLA PRINCIPAL
══════════════════════════════ */
.pres-table-wrap {
    overflow-x:auto; border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
    border:1px solid #d1d5db;
}
.pres-tabla {
    width:100%; border-collapse:collapse; font-size:.8rem;
    min-width:900px; background:#fff;
}
.pres-tabla thead th {
    background:#111; color:#fff; padding:10px 11px;
    font-size:.67rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.5px; white-space:nowrap;
    border-right:1px solid #333;
}
.pres-tabla thead th:last-child { border-right:none; }

/* Fila nivel */
.row-nivel td {
    background:#fff; color:#111;
    padding:12px 14px; font-weight:900; font-size:.88rem;
    border-top:3px solid #111; border-bottom:1px solid #e5e7eb;
    text-transform:uppercase; letter-spacing:.5px;
}

/* Fila bloque */
.row-bloque td {
    background:#1c1c1c; color:#fff; padding:7px 11px;
    font-weight:700; font-size:.74rem; text-transform:uppercase;
    letter-spacing:.5px; text-align:right;
    border-bottom:1px solid #333;
}
.row-bloque td:first-child { text-align:left; }

/* Fila item */
.row-item td {
    padding:8px 11px; border-bottom:1px solid #f0f0f0;
    vertical-align:middle; background:#fff; transition:background .15s;
    border-right:1px solid #f5f5f5;
}
.row-item td:last-child { border-right:none; }
.row-item:hover td { background:#fafafa; }

/* Fila desglose */
.row-desglose { display:none; }
.row-desglose.open { display:table-row; }
.row-desglose td {
    background:#f9fafb; padding:10px 18px; font-size:.76rem;
    border-bottom:1px solid #e5e7eb;
}
.comp-badge { display:inline-block; padding:2px 7px; border-radius:6px;
    font-size:.63rem; font-weight:700; margin-right:4px; margin-bottom:2px; }
.cb-mat { background:#dbeafe; color:#1d4ed8; }
.cb-maq { background:#fef3c7; color:#92400e; }
.cb-mo  { background:#d1fae5; color:#065f46; }

/* Fila subtotal bloque */
.row-bloque-sub td {
    background:#333; color:#fff; padding:7px 11px;
    font-weight:700; font-size:.78rem;
    text-align:right;
    border-bottom:1px solid #444;
}
.row-bloque-sub td:first-child { text-align:left; }

/* Fila gran total */
.row-gran-total td {
    background:#111; color:#fff; padding:12px 11px;
    font-weight:900; font-size:.88rem; text-align:right;
}
.row-gran-total td:first-child { text-align:left; }
.row-gran-total .highlight { color:#fbbf24; font-size:1rem; }

/* ── BOTONES DE ACCIÓN EN FILA ── */
.action-cell { white-space:nowrap; width:80px; text-align:center; }
.btn-row-edit, .btn-row-del {
    border:none; border-radius:6px; padding:4px 8px;
    font-size:.76rem; cursor:pointer; transition:.2s;
    display:inline-flex; align-items:center; gap:3px; font-weight:600;
}
.btn-row-edit { background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; }
.btn-row-edit:hover { background:#374151; color:#fff; }
.btn-row-del  { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
.btn-row-del:hover  { background:#b91c1c; color:#fff; }
.btn-desglose-toggle { background:transparent; border:1px solid var(--line);
    border-radius:5px; padding:2px 7px; font-size:.68rem; cursor:pointer;
    color:var(--soft); transition:.15s; margin-left:5px; }
.btn-desglose-toggle:hover { background:var(--line); }

/* ── MODAL EDITAR ── */
.modal-overlay {
    display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,.65); z-index:1000;
    align-items:center; justify-content:center;
}
.modal-overlay.open { display:flex; }
.modal-box {
    background:#fff; border-radius:16px; width:100%; max-width:520px;
    box-shadow:0 20px 40px rgba(0,0,0,.3); overflow:hidden;
    animation:fadeUp .25s ease;
}
@keyframes fadeUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
.modal-head {
    background:var(--dark); color:#fff; padding:18px 22px;
    display:flex; justify-content:space-between; align-items:center;
}
.modal-head h3 { margin:0; font-size:1rem; font-weight:700; }
.btn-close-modal { background:rgba(255,255,255,.1); border:none; color:#fff;
    border-radius:7px; padding:4px 10px; cursor:pointer; font-size:1.1rem; transition:.2s; }
.btn-close-modal:hover { background:rgba(255,255,255,.2); }
.modal-body { padding:22px; }
.modal-field { margin-bottom:16px; }
.modal-field label { display:block; font-size:.75rem; font-weight:700; color:var(--soft);
    text-transform:uppercase; letter-spacing:.4px; margin-bottom:5px; }
.modal-field input {
    width:100%; border:1.5px solid var(--line); border-radius:8px;
    padding:9px 12px; font-size:.9rem; color:var(--dark);
}
.modal-field input:focus { border-color:#374151; outline:none; box-shadow:0 0 0 3px rgba(55,65,81,.06); }
.modal-footer { padding:16px 22px; background:#f9fafb; border-top:1px solid var(--line);
    display:flex; gap:8px; justify-content:flex-end; }
.btn-modal-cancel { background:#f3f4f6; color:var(--mid); border:1.5px solid var(--line);
    border-radius:8px; padding:8px 18px; font-weight:600; cursor:pointer; font-size:.85rem; }
.btn-modal-save   { background:#111; color:#fff; border:none;
    border-radius:8px; padding:8px 20px; font-weight:700; cursor:pointer; font-size:.85rem;
    transition:.2s; }
.btn-modal-save:hover { background:#374151; }

/* ── TOAST ── */
#toast { position:fixed; bottom:22px; right:22px; z-index:9999;
    padding:12px 20px; border-radius:10px; font-weight:600; font-size:.88rem;
    display:none; align-items:center; gap:9px; box-shadow:0 10px 25px rgba(0,0,0,.2); }
#toast.ok  { background:#059669; color:#fff; }
#toast.err { background:#dc2626; color:#fff; }

/* ── RESPONSIVE ── */
@media(max-width:900px) {
    .pres-hdr-info { grid-template-columns:repeat(3,1fr); }
    .hdr-info-cell:nth-child(3n) { border-right:none; }
    .hdr-info-cell:nth-child(4) { border-top:1px solid rgba(255,255,255,.07); }
}
@media(max-width:600px) {
    .pres-hdr-info { grid-template-columns:repeat(2,1fr); }
    .totales-bar { grid-template-columns:repeat(2,1fr); }
    .pres-hdr-top { flex-direction:column; align-items:flex-start; gap:10px; }
    .hdr-actions { flex-wrap:wrap; }
}
</style>

{{-- ══════════════════════════════════════════
     HEADER EMPRESA + PROYECTO
══════════════════════════════════════════ --}}
@php
    $clienteHdr   = $obra->cliente;
    $dirHdr       = $clienteHdr?->direccionFiscal ?? $obra->datosDeObra?->direccion ?? null;
    $domHdr       = $dirHdr
        ? trim(($dirHdr->calle_y_numero ?? '') . ', ' . ($dirHdr->colonia ?? '') . ', ' . ($dirHdr->delegacion ?? ''))
        : '—';
    $domHdr       = trim($domHdr, ', ') ?: '—';
    $fechaInicioHdr  = $obra->fecha_inicio ? $obra->fecha_inicio->format('d/m/Y') : '—';
    $duracionHdr     = $obra->duracion ? $obra->duracion . ' días' : '—';
    $fechaEntregaHdr = '—';
    if ($obra->fecha_inicio && $obra->duracion) {
        $fechaEntregaHdr = $obra->fecha_inicio->copy()->addDays((int)$obra->duracion)->format('d/m/Y');
    }
    $diasFaltanHdr = $obra->dias_faltan;
    $nomClienteHdr = $clienteHdr?->nombre ?? $clienteHdr?->nombre_o_razon_social ?? '—';
@endphp
<div class="pres-hdr">
    {{-- Franja superior --}}
    <div class="pres-hdr-top" style="display:flex; justify-content:space-between; align-items:center; padding:15px 25px; background:#111; gap:15px; flex-wrap:wrap;">
        
        {{-- LADO IZQUIERDO: BRAND + ACCIONES DE EDICIÓN --}}
        <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
            
            {{-- 1. BRAND & INFO --}}
            <div class="hdr-brand" style="display:flex; align-items:center; gap:10px;">
                @if(file_exists(public_path('img/logo_akiraka.jpeg')))
                <img src="{{ asset('img/logo_akiraka.jpeg') }}" alt="Logo" class="hdr-logo">
                @endif
                <div class="hdr-empresa-info">
                    <div class="hdr-empresa-nombre">AKIRAKA</div>
                    <div class="hdr-empresa-sub" style="font-size:0.65rem;">Construcción &amp; Diseño</div>
                </div>
                <div style="width:1px;height:36px;background:rgba(255,255,255,.15);margin:0 5px;"></div>
                <div style="display:flex;flex-direction:column;gap:2px;">
                    <a href="{{ route('obras.show', $obra->id) }}" class="btn-back" style="margin-bottom:0;">
                        <i class="bi bi-arrow-left"></i> Datos Generales
                    </a>
                    <span style="font-size:.65rem;color:#6b7280;padding-left:2px;">
                        <i class="bi bi-file-earmark-text me-1"></i>Presupuesto de Obra
                    </span>
                </div>
            </div>

            {{-- 2. ACCIONES DE EDICIÓN & VERSIONADO --}}
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                {{-- Selector de Versión --}}
                <select class="form-select" style="background:#1f2937; color:#fff; border-color:#374151; padding: 0.25rem 1.8rem 0.25rem 0.5rem; font-size: 0.8rem; height:32px; width:auto;" onchange="window.location.href='?version='+this.value">
                    <option value="1" {{ $versionConsulta == 1 ? 'selected' : '' }}>V1 (Original)</option>
                    @if(isset($obra->versionesPresupuesto))
                        @foreach($obra->versionesPresupuesto as $vp)
                            @if($vp->numero_version > 1)
                            <option value="{{ $vp->numero_version }}" {{ $versionConsulta == $vp->numero_version ? 'selected' : '' }}>
                                V{{ $vp->numero_version }} {{ $vp->es_activa ? '(Activa)' : '' }}
                            </option>
                            @endif
                        @endforeach
                    @endif
                </select>

                {{-- Botón Nueva Versión --}}
                <form action="{{ route('obras.presupuesto.version.crear', $obra->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('¿Estás seguro de crear una nueva versión? Esto congelará la actual.')">
                    @csrf
                    <button type="submit" class="btn-hdr btn-hdr-dark" title="Congelar esta versión y crear una nueva">
                        <i class="bi bi-files"></i> Nueva V.
                    </button>
                </form>

                <button class="btn-hdr btn-hdr-dark" onclick="toggleTodosDesgloses()">
                    <i class="bi bi-diagram-3"></i> Desglosar
                </button>

                @if($obra->obraConceptos->isNotEmpty() && !\App\Models\ObraProceso::where('id_obra', $obra->id)->exists())
                <button type="button" class="btn-hdr btn-hdr-green" onclick="abrirModalAprobar()">
                    <i class="bi bi-check-circle-fill"></i> Aprobar
                </button>
                @endif

                <a href="{{ route('obras.presupuesto.unificado.create', $obra->id) }}" class="btn-hdr btn-hdr-blue">
                    <i class="bi bi-plus-lg"></i> AGREGAR
                </a>
            </div>
        </div>

        {{-- LADO DERECHO: ACCIONES DE EXPORTACIÓN --}}
        <div class="hdr-actions" style="display:flex; align-items:center; gap:8px;">
            <a href="{{ route('obras.presupuesto.export_excel', $obra->id) }}" class="btn-hdr btn-hdr-excel">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('obras.presupuesto.pdf_formal', $obra->id) }}"
               class="btn-hdr" style="background:#d97706; color:#fff;" title="Carta/cotización">
                <i class="bi bi-file-earmark-pdf-fill"></i> PDF PRESUPUESTO
            </a>
            <a href="{{ route('obras.presupuesto.pdf_catalogo', $obra->id) }}"
               class="btn-hdr" style="background:#1d4ed8; color:#fff;" title="Tabla completa de conceptos">
                <i class="bi bi-table"></i> PDF CATÁLOGO
            </a>
        </div>

    </div>
    {{-- Franja inferior: datos del proyecto --}}
    <div class="pres-hdr-info">
        <div class="hdr-info-cell">
            <span class="hdr-info-lbl">Proyecto</span>
            <span class="hdr-info-val" title="{{ $obra->datosDeObra?->nombre }}">{{ $obra->datosDeObra?->nombre ?? '—' }}</span>
        </div>
        <div class="hdr-info-cell">
            <span class="hdr-info-lbl">Cliente</span>
            <span class="hdr-info-val" title="{{ $nomClienteHdr }}">{{ $nomClienteHdr }}</span>
        </div>
        <div class="hdr-info-cell">
            <span class="hdr-info-lbl">Domicilio</span>
            <span class="hdr-info-val" title="{{ $domHdr }}">{{ $domHdr }}</span>
        </div>
        <div class="hdr-info-cell">
            <span class="hdr-info-lbl">Fecha Inicio</span>
            <span class="hdr-info-val">{{ $fechaInicioHdr }}</span>
        </div>
        <div class="hdr-info-cell">
            <span class="hdr-info-lbl">Entrega Estimada</span>
            <span class="hdr-info-val">{{ $fechaEntregaHdr }}</span>
        </div>
        <div class="hdr-info-cell">
            <span class="hdr-info-lbl">Días Restantes</span>
            <span class="hdr-info-val">
                @if($diasFaltanHdr !== null)
                    {{ $diasFaltanHdr }} días
                @else
                    {{ $duracionHdr }}
                @endif
            </span>
        </div>
    </div>
</div>

{{-- ── TOTALES BAR ── --}}
@php
    $conceptos = $obra->obraConceptos;
    $gSub = $conceptos->sum('subtotal');
    $gIva = $conceptos->sum('iva');
    $gTot = $conceptos->sum('total_final');

    $nivelesList = $obra->niveles->keyBy('id');
    $porNivel = collect();
    foreach ($conceptos->groupBy('id_nivel') as $idNiv => $filasNiv) {
        $nomNivel = $idNiv ? ($nivelesList[$idNiv]->descripcion ?? "Nivel $idNiv") : 'GENERAL / SIN NIVEL';
        $porBloque = collect();
        foreach ($bloques as $blq) {
            $filasBloq = $filasNiv->where('id_bloque', $blq->id);
            if ($filasBloq->isNotEmpty())
                $porBloque->put($blq->id, ['nombre' => $blq->descripcion, 'filas' => $filasBloq]);
        }
        $sinBloque = $filasNiv->where('id_bloque', null);
        if ($sinBloque->isNotEmpty())
            $porBloque->put(0, ['nombre' => 'Sin Bloque', 'filas' => $sinBloque]);
        $porNivel->put($idNiv, [
            'nombre'   => $nomNivel,
            'bloques'  => $porBloque,
            'subtotal' => $filasNiv->sum('subtotal'),
            'iva'      => $filasNiv->sum('iva'),
            'total'    => $filasNiv->sum('total_final'),
        ]);
    }
@endphp

<div class="totales-bar">
    <div class="tot-cell">
        <div class="tot-lbl"><i class="bi bi-calculator me-1"></i>Subtotal</div>
        <div class="tot-val">${{ number_format($gSub, 2) }}</div>
    </div>
    <div class="tot-cell">
        <div class="tot-lbl"><i class="bi bi-percent me-1"></i>I.V.A. (16%)</div>
        <div class="tot-val">${{ number_format($gIva, 2) }}</div>
    </div>
    <div class="tot-cell tot-cell-dark">
        <div class="tot-lbl"><i class="bi bi-check2-circle me-1"></i>Total Final</div>
        <div class="tot-val highlight">${{ number_format($gTot, 2) }}</div>
    </div>
    <div class="tot-cell">
        <div class="tot-lbl"><i class="bi bi-list-ul me-1"></i>Renglones</div>
        <div class="tot-val">{{ $conceptos->count() }}</div>
    </div>
</div>

{{-- ── BODY ── --}}
<div class="pres-body">
@if($conceptos->isEmpty())
    <div style="text-align:center;padding:60px 20px;background:#fff;border-radius:14px;box-shadow:0 2px 8px rgba(0,0,0,.04);">
        <i class="bi bi-file-earmark-x" style="font-size:4rem;color:#d1d5db;display:block;margin-bottom:16px;"></i>
        <h3 style="font-size:1.3rem;font-weight:800;color:#374151;margin-bottom:8px;">Sin Presupuesto</h3>
        <p style="color:#9ca3af;margin-bottom:20px;">Aún no se han agregado renglones a esta obra.</p>
        <a href="{{ route('obras.presupuesto.unificado.create', $obra->id) }}" class="btn-hdr btn-hdr-blue" style="display:inline-flex;">
            <i class="bi bi-plus-circle"></i> Agregar Primer Renglón
        </a>
    </div>
@else
    <div class="pres-table-wrap">
        <table class="pres-tabla">
            <thead>
                <tr>
                    <th style="width:5%;text-align:center;">Área</th>
                    <th style="width:30%;text-align:left;">Concepto / Descripción</th>
                    <th style="width:7%;text-align:center;">Cantidad</th>
                    <th style="width:6%;text-align:center;">Unidad</th>
                    <th style="width:11%;text-align:right;">P.U.</th>
                    <th style="width:11%;text-align:right;">Subtotal</th>
                    <th style="width:8%;text-align:right;">IVA</th>
                    <th style="width:11%;text-align:right;">Total</th>
                    <th style="width:7%;text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($porNivel as $idNivel => $nivelData)
                {{-- NIVEL --}}
                <tr class="row-nivel">
                    <td colspan="6"><i class="bi bi-layers me-2"></i>{{ mb_strtoupper($nivelData['nombre']) }}</td>
                    <td style="text-align:right;font-family:monospace;">${{ number_format($nivelData['iva'],2) }}</td>
                    <td style="text-align:right;font-weight:800;color:#111;font-family:monospace;">${{ number_format($nivelData['total'],2) }}</td>
                    <td></td>
                </tr>
                @foreach($nivelData['bloques'] as $bloqueId => $bloqueData)
                    <tr class="row-bloque">
                        <td colspan="5" style="text-align:left;padding-left:28px;">
                            <i class="bi bi-grid-3x3-gap me-1"></i> {{ strtoupper($bloqueData['nombre']) }}
                        </td>
                        <td style="font-family:monospace;">${{ number_format($bloqueData['filas']->sum('subtotal'),2) }}</td>
                        <td style="font-family:monospace;">${{ number_format($bloqueData['filas']->sum('iva'),2) }}</td>
                        <td style="font-family:monospace;">${{ number_format($bloqueData['filas']->sum('total_final'),2) }}</td>
                        <td></td>
                    </tr>
                    @foreach($bloqueData['filas'] as $fila)
                        <tr class="row-item" id="row_fila_{{ $fila->id }}">
                            <td style="text-align:center;font-weight:700;color:#374151;font-size:.75rem;">{{ $fila->area?->abreviatura ?? '—' }}</td>
                            <td style="max-width:260px;">
                                <span style="font-weight:600;display:block;line-height:1.3;" id="nom_{{ $fila->id }}">{{ $fila->concepto?->descripcion ?? 'Concepto sin nombre' }}</span>
                                <button class="btn-desglose-toggle" onclick="toggleDesglose({{ $fila->id }})">
                                    <i class="bi bi-chevron-down" id="ico_{{ $fila->id }}"></i> detalle
                                </button>
                            </td>
                            <td style="text-align:center;">
                                <span id="cant_{{ $fila->id }}">{{ number_format($fila->cantidad, 2) }}</span>
                            </td>
                            <td style="text-align:center;font-size:.75rem;color:#6b7280;">{{ $fila->concepto?->unidadMedida?->abreviatura ?? '—' }}</td>
                            <td style="text-align:right;font-family:monospace;" id="pu_{{ $fila->id }}">${{ number_format($fila->precio_unitario,2) }}</td>
                            <td style="text-align:right;font-family:monospace;" id="sub_{{ $fila->id }}">${{ number_format($fila->subtotal,2) }}</td>
                            <td style="text-align:right;font-family:monospace;color:#6b7280;">${{ number_format($fila->iva,2) }}</td>
                            <td style="text-align:right;font-weight:800;font-family:monospace;" id="tot_{{ $fila->id }}">${{ number_format($fila->total_final,2) }}</td>
                            <td class="action-cell">
                                <button class="btn-row-edit" title="Editar"
                                    onclick="openEditPanel({{ $fila->id }})">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form method="POST" action="{{ route('obras.presupuesto.conceptos.destroy', [$obra->id, $fila->id]) }}"
                                    id="form_del_{{ $fila->id }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                </form>
                                <button class="btn-row-del" title="Eliminar"
                                    onclick="confirmDel({{ $fila->id }}, '{{ addslashes($fila->concepto?->descripcion ?? 'este concepto') }}')">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- DESGLOSE --}}
                        <tr class="row-desglose" id="desglose_{{ $fila->id }}">
                            <td></td>
                            <td colspan="7">
                                <strong style="font-size:.85rem;color:var(--dark);"><i class="bi bi-diagram-3-fill me-1"></i>Matriz de Costos:</strong>
                                @if($fila->materiales->isEmpty() && $fila->maquinaria->isEmpty() && $fila->manoObra->isEmpty())
                                    <div style="margin-top:6px;"><em style="color:#9ca3af; font-size: 0.8rem;">Sin insumos desglosados — se usa el P.U. base.</em></div>
                                @else
                                    <table style="width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 0.8rem; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                                        <thead style="background: #f9fafb; border-bottom: 2px solid #e5e7eb; color: #4b5563; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <tr>
                                                <th style="padding: 8px 12px; text-align: left; font-weight: 700; width: 12%;">Tipo</th>
                                                <th style="padding: 8px 12px; text-align: left; font-weight: 700; width: 45%;">Insumo</th>
                                                <th style="padding: 8px 12px; text-align: center; font-weight: 700;">Cantidad</th>
                                                <th style="padding: 8px 12px; text-align: right; font-weight: 700;">Precio Unit.</th>
                                                <th style="padding: 8px 12px; text-align: right; font-weight: 700;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($fila->materiales as $mat)
                                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                                    <td style="padding: 8px 12px;"><span class="comp-badge cb-mat" style="margin:0;">Material</span></td>
                                                    <td style="padding: 8px 12px; color: #111827;"><strong>{{ $mat->material?->nombre }}</strong></td>
                                                    <td style="padding: 8px 12px; text-align: center; color: #4b5563;">{{ number_format($mat->cantidad, 2) }} {{ $mat->material?->unidadMedida?->abreviatura }}</td>
                                                    <td style="padding: 8px 12px; text-align: right; color: #4b5563;">${{ number_format($mat->precio_unitario, 2) }}</td>
                                                    <td style="padding: 8px 12px; text-align: right; font-weight: 600; color: #111827;">${{ number_format($mat->cantidad * $mat->precio_unitario, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            @foreach($fila->maquinaria as $maq)
                                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                                    <td style="padding: 8px 12px;"><span class="comp-badge cb-maq" style="margin:0;">Maquinaria</span></td>
                                                    <td style="padding: 8px 12px; color: #111827;"><strong>{{ $maq->maquinaria?->nombre }}</strong></td>
                                                    <td style="padding: 8px 12px; text-align: center; color: #4b5563;">{{ number_format($maq->cantidad, 2) }} {{ $maq->maquinaria?->unidadMedida?->abreviatura }}</td>
                                                    <td style="padding: 8px 12px; text-align: right; color: #4b5563;">${{ number_format($maq->precio_unitario, 2) }}</td>
                                                    <td style="padding: 8px 12px; text-align: right; font-weight: 600; color: #111827;">${{ number_format($maq->cantidad * $maq->precio_unitario, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            @foreach($fila->manoObra as $mo)
                                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                                    <td style="padding: 8px 12px;"><span class="comp-badge cb-mo" style="margin:0;">Mano de Obra</span></td>
                                                    <td style="padding: 8px 12px; color: #111827;"><strong>{{ $mo->manoObra?->nombre }}</strong></td>
                                                    <td style="padding: 8px 12px; text-align: center; color: #4b5563;">{{ number_format($mo->cantidad, 2) }} {{ $mo->manoObra?->unidadMedida?->abreviatura }}</td>
                                                    <td style="padding: 8px 12px; text-align: right; color: #4b5563;">${{ number_format($mo->precio_unitario, 2) }}</td>
                                                    <td style="padding: 8px 12px; text-align: right; font-weight: 600; color: #111827;">${{ number_format($mo->cantidad * $mo->precio_unitario, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach

            {{-- GRAN TOTAL --}}
            <tr class="row-gran-total">
                <td colspan="2" style="text-align:left;"><i class="bi bi-check2-circle me-1"></i> TOTAL GENERAL DEL PRESUPUESTO</td>
                <td></td>
                <td></td>
                <td style="font-family:monospace;"></td>
                <td style="font-family:monospace;">${{ number_format($gSub,2) }}</td>
                <td style="font-family:monospace;">${{ number_format($gIva,2) }}</td>
                <td class="highlight" style="font-family:monospace;">${{ number_format($gTot,2) }}</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
@endif

@if(!empty($materialesPorNivelArea))
    <div style="margin-top:40px;">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: #111; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
            <i class="bi bi-box-seam me-2"></i>Lista de Materiales a Utilizar por Nivel y Área
        </h3>
        <div class="pres-table-wrap" style="border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <table class="pres-tabla" style="min-width: 100%;">
                <thead style="background: #111; color: #fff;">
                    <tr>
                        <th style="padding: 10px 14px; text-align: left; font-size: 0.75rem;">Material</th>
                        <th style="padding: 10px 14px; text-align: center; font-size: 0.75rem;">Cantidad Total</th>
                        <th style="padding: 10px 14px; text-align: right; font-size: 0.75rem;">Costo Estimado</th>
                    </tr>
                </thead>
                <tbody style="background: #fff;">
                    @php $granTotalMateriales = 0; @endphp
                    
                    @foreach($materialesPorNivelArea as $nivelId => $nivelData)
                        <tr class="row-nivel">
                            <td colspan="3" style="padding: 12px 14px; background: #fff; color: #111; font-weight: 900; font-size: 0.88rem; border-top: 3px solid #111; border-bottom: 1px solid #e5e7eb; text-transform: uppercase;">
                                <i class="bi bi-layers me-2"></i>{{ mb_strtoupper($nivelData['nombre']) }}
                            </td>
                        </tr>
                        @foreach($nivelData['areas'] as $areaId => $areaData)
                            @if(!empty($areaData['materiales']))
                                <tr class="row-bloque">
                                    <td colspan="3" style="text-align:left; padding: 7px 11px; padding-left: 28px; background: #1c1c1c; color: #fff; font-weight: 700; font-size: 0.74rem; text-transform: uppercase;">
                                        <i class="bi bi-geo-alt me-1"></i> {{ strtoupper($areaData['nombre']) }}
                                    </td>
                                </tr>
                                @foreach($areaData['materiales'] as $matId => $data)
                                    @php $granTotalMateriales += $data['costo_total']; @endphp
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 12px 14px; color: #1f2937; font-weight: 600; padding-left: 40px;">
                                            {{ $data['material']->nombre }}
                                        </td>
                                        <td style="padding: 12px 14px; text-align: center; color: #4b5563;">
                                            {{ number_format($data['cantidad_total'], 2) }} {{ $data['material']->unidadMedida?->abreviatura ?? '' }}
                                        </td>
                                        <td style="padding: 12px 14px; text-align: right; color: #111; font-family: monospace; font-weight: 700;">
                                            ${{ number_format($data['costo_total'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                    
                    <tr style="background: #f9fafb; border-top: 2px solid #e5e7eb;">
                        <td colspan="2" style="padding: 12px 14px; text-align: right; font-weight: 800; color: #111; text-transform: uppercase;">Total Gastos en Materiales:</td>
                        <td style="padding: 12px 14px; text-align: right; font-weight: 900; color: #b91c1c; font-family: monospace; font-size: 1.05rem;">
                            ${{ number_format($granTotalMateriales, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif

</div>

{{-- ── PANEL LATERAL DE EDICIÓN COMPLETA ── --}}
<style>
/* ── Panel Lateral ── */
.edit-overlay {
    display:none;position:fixed;top:0;left:0;width:100%;height:100%;
    background:rgba(0,0,0,.6);z-index:900;
}
.edit-overlay.open { display:block; }
.edit-panel {
    position:fixed;top:0;right:-680px;width:660px;height:100%;
    background:#fff;z-index:901;display:flex;flex-direction:column;
    transition:right .3s cubic-bezier(.4,0,.2,1);
    box-shadow:-8px 0 40px rgba(0,0,0,.25);
}
.edit-panel.open { right:0; }
.ep-head {
    background:var(--dark);color:#fff;padding:16px 20px;
    display:flex;justify-content:space-between;align-items:center;
    flex-shrink:0;
}
.ep-head h2 { margin:0;font-size:1rem;font-weight:700;display:flex;align-items:center;gap:8px; }
.ep-body { flex:1;overflow-y:auto;padding:20px; }
.ep-footer {
    padding:14px 20px;background:#f9fafb;border-top:1px solid var(--line);
    display:flex;gap:8px;justify-content:flex-end;flex-shrink:0;
}

/* Sección dentro del panel */
.ep-section { margin-bottom:18px; }
.ep-section-title {
    font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;
    color:var(--soft);margin-bottom:8px;padding-bottom:5px;border-bottom:1px solid var(--line);
    display:flex;justify-content:space-between;align-items:center;
}
.ep-field { margin-bottom:12px; }
.ep-field label { display:block;font-size:.72rem;font-weight:700;color:var(--soft);
    text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px; }
.ep-field input, .ep-field select {
    width:100%;border:1.5px solid var(--line);border-radius:8px;
    padding:8px 11px;font-size:.88rem;color:var(--dark);transition:.15s;
}
.ep-field input:focus, .ep-field select:focus { border-color:#374151;outline:none; box-shadow:0 0 0 3px rgba(55,65,81,.06); }
.ep-row { display:flex;gap:10px; }
.ep-row .ep-field { flex:1; }

/* Tabla insumos en panel */
.ep-ins-head {
    display:flex;justify-content:space-between;align-items:center;
    padding:7px 11px;border-radius:8px 8px 0 0;
    font-size:.78rem;font-weight:700;
    border:1px solid;
}
.ep-ins-head.mat  { background:#f3f4f6;color:#374151; border-color:#e5e7eb; }
.ep-ins-head.mo   { background:#f0fdf4;color:#065f46;border-color:#bbf7d0; }
.ep-ins-head.maq  { background:#fffbeb;color:#92400e;border-color:#fde68a; }
.ep-ins-table { width:100%;border-collapse:collapse;border:1px solid var(--line);border-top:none;border-radius:0 0 8px 8px;overflow:hidden;margin-bottom:12px; }
.ep-ins-table th { padding:5px 8px;font-size:.67rem;font-weight:700;text-transform:uppercase;color:var(--soft);background:#fafafa;border-bottom:1px solid var(--line); }
.ep-ins-table td { padding:5px 7px;border-bottom:1px solid #f8f9fa;vertical-align:middle; }
.ep-ins-table input,.ep-ins-table select { width:100%;border:1.5px solid var(--line);border-radius:5px;padding:4px 7px;font-size:.82rem;background:#fff; }
.ep-ins-table input:focus,.ep-ins-table select:focus { border-color:#374151;outline:none; }

/* PU calculado */
.ep-pu-bar { background:#f3f4f6;border:1px solid #e5e7eb;border-radius:8px;
    padding:9px 14px;display:flex;justify-content:space-between;align-items:center;
    font-size:.85rem;font-weight:700;color:#111;margin-top:4px; }

/* Autocomplete en panel */
.ep-ac-wrap { position:relative; }
.ep-ac-list {
    position:absolute;top:100%;left:0;right:0;background:#fff;
    border:1.5px solid var(--line);border-top:none;max-height:180px;overflow-y:auto;
    z-index:9999;display:none;border-radius:0 0 9px 9px;
    box-shadow:0 8px 20px rgba(0,0,0,.1);
}
.ep-ac-item { padding:8px 12px;cursor:pointer;font-size:.84rem;color:var(--mid);border-bottom:1px solid #f3f4f6; }
.ep-ac-item:hover { background:#f3f4f6;color:#111; }
.ep-ac-item.nuevo { color:#374151;font-weight:700;background:#f9fafb; }

.btn-ep-add { border:none;border-radius:7px;padding:4px 11px;font-size:.76rem;font-weight:700;
    cursor:pointer;display:inline-flex;align-items:center;gap:4px;transition:.2s; }
.btn-ep-add.mat  { background:#374151; color:#fff; }
.btn-ep-add.mo   { background:#059669;color:#fff; }
.btn-ep-add.maq  { background:#d97706;color:#fff; }
.btn-ep-add:hover { opacity:.85; }
.btn-ep-close { background:rgba(255,255,255,.1);border:none;color:#fff;
    border-radius:7px;padding:5px 11px;cursor:pointer;font-size:1rem;transition:.2s; }
.btn-ep-close:hover { background:rgba(255,255,255,.2); }
.btn-ep-save { background:#111;color:#fff;border:none;border-radius:8px;
    padding:9px 22px;font-weight:700;font-size:.88rem;cursor:pointer;transition:.2s; }
.btn-ep-save:hover { background:#374151; }
.btn-ep-save:disabled { opacity:.6;cursor:not-allowed; }
.btn-ep-cancel { background:#f3f4f6;color:var(--mid);border:1.5px solid var(--line);
    border-radius:8px;padding:9px 18px;font-weight:600;cursor:pointer;font-size:.88rem; }
.btn-del-ep { background:#fef2f2;color:var(--red);border:1px solid #fecaca;
    border-radius:5px;padding:3px 7px;cursor:pointer;transition:.15s;font-size:.78rem; }
.btn-del-ep:hover { background:var(--red);color:#fff; }

/* Loading spinner */
.ep-loading { display:flex;align-items:center;justify-content:center;
    height:200px;font-size:.9rem;color:var(--soft);gap:10px; }
@keyframes spin { from{transform:rotate(0)}to{transform:rotate(360deg)} }
.spin-icon { animation:spin 1s linear infinite; }
</style>

<div class="edit-overlay" id="epOverlay" onclick="closeEditPanel()"></div>
<div class="edit-panel" id="editPanel">
    <div class="ep-head">
        <h2><i class="bi bi-pencil-square" style="color:#9ca3af;"></i> Editar Renglón</h2>
        <button class="btn-ep-close" onclick="closeEditPanel()">×</button>
    </div>
    <div class="ep-body" id="epBody">
        <div class="ep-loading"><i class="bi bi-arrow-repeat spin-icon"></i> Cargando datos…</div>
    </div>
    <div class="ep-footer">
        <button class="btn-ep-cancel" onclick="closeEditPanel()">Cancelar</button>
        <button class="btn-ep-save" id="btnEpSave" onclick="guardarEdicionCompleta()">
            <i class="bi bi-check-lg me-1"></i> Guardar Cambios
        </button>
    </div>
</div>

{{-- ── MODAL ELIMINAR ── --}}
<div class="modal-overlay" id="modalEliminar">
    <div class="modal-box">
        <div class="modal-head" style="background:#dc2626;">
            <h3><i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Renglón</h3>
            <button class="btn-close-modal" onclick="closeDel()">×</button>
        </div>
        <div class="modal-body" style="text-align:center;padding:30px 22px;">
            <i class="bi bi-trash3-fill" style="font-size:3rem;color:#fca5a5;display:block;margin-bottom:12px;"></i>
            <p style="font-size:.95rem;color:#374151;margin-bottom:4px;">¿Eliminar el renglón:</p>
            <p style="font-weight:800;color:#111;font-size:1rem;" id="del_nombre"></p>
            <p style="font-size:.82rem;color:#9ca3af;">Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="closeDel()">Cancelar</button>
            <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 20px;font-weight:700;cursor:pointer;font-size:.85rem;"
                onclick="document.getElementById('form_del_'+currentDelId).submit()">
                <i class="bi bi-trash3-fill me-1"></i> Sí, Eliminar
            </button>
        </div>
    </div>
</div>

<div id="toast"></div>

{{-- MODAL APROBAR PRESUPUESTO ──────────────────────────────────────────── --}}
<div class="modal-overlay" id="modalAprobarOverlay">
    <div class="modal-box" style="max-width: 450px;">
        <div class="modal-head" style="background:#059669;">
            <h3><i class="bi bi-check2-circle me-2"></i>Aprobar Presupuesto</h3>
            <button class="btn-close-modal" onclick="cerrarModalAprobar()">×</button>
        </div>
        <div class="modal-body" style="padding:25px 22px;">
            <p style="font-size:1rem; color:#111; font-weight:700; margin-bottom:15px; text-align:center;">¿Cómo se aplicará el presupuesto de esta obra?</p>
            <form action="{{ route('obras.presupuesto.aprobar', $obra->id) }}" method="POST" id="formAprobarPresupuesto">
                @csrf
                <div class="modal-field">
                    <label>Selecciona el tipo de IVA</label>
                    <select name="con_iva" style="width:100%; padding:10px; border-radius:8px; border:1.5px solid #e5e7eb; font-size:0.95rem; font-weight:600;" required>
                        <option value="1">Con IVA (Presupuesto + IVA)</option>
                        <option value="0">Sin IVA (Solo Subtotal)</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal-cancel" onclick="cerrarModalAprobar()">Cancelar</button>
            <button type="submit" class="btn-modal-save" style="background:#059669;" onclick="document.getElementById('formAprobarPresupuesto').submit();">
                <i class="bi bi-check-lg me-1"></i> Confirmar
            </button>
        </div>
    </div>
</div>

<script>
function abrirModalAprobar() {
    document.getElementById('modalAprobarOverlay').classList.add('open');
}
function cerrarModalAprobar() {
    document.getElementById('modalAprobarOverlay').classList.remove('open');
}

const csrfToken   = '{{ csrf_token() }}';
const editBaseUrl = '{{ url("obra-conceptos") }}';

// Catálogos para autocomplete y selects
@php
    $catConceptosArr  = \App\Models\Concepto::orderBy('descripcion')->get()
        ->map(fn($c) => ['id'=>$c->id,'texto'=>$c->descripcion,'pu'=>$c->p_u,'uni'=>$c->id_unidad_medida])->values()->toJson();
    $catMaterialesArr = \App\Models\Material::orderBy('nombre')->get()
        ->map(fn($m) => ['id'=>$m->id,'texto'=>$m->nombre,'pu'=>$m->precio_x_unidad,'uni'=>$m->id_unidad_medida])->values()->toJson();
    $catMaquinariaArr = \App\Models\Maquinaria::orderBy('nombre')->get()
        ->map(fn($m) => ['id'=>$m->id,'texto'=>$m->nombre,'pu'=>$m->precio_x_unidad,'uni'=>$m->id_unidad_medida])->values()->toJson();
    $catManoObraArr   = \App\Models\ManoObra::orderBy('nombre')->get()
        ->map(fn($m) => ['id'=>$m->id,'texto'=>$m->nombre,'pu'=>$m->precio_x_unidad,'uni'=>$m->id_unidad_medida])->values()->toJson();
    $unidadesArr      = \App\Models\UnidadMedida::orderBy('abreviatura')->get()
        ->map(fn($u) => ['id'=>$u->id,'txt'=>$u->abreviatura])->values()->toJson();
    $bloquesArr       = \App\Models\Bloque::orderBy('id')->get()
        ->map(fn($b) => ['id'=>$b->id,'txt'=>$b->descripcion])->values()->toJson();
    $areasArr         = \App\Models\Area::orderBy('abreviatura')->get()
        ->map(fn($a) => ['id'=>$a->id,'txt'=>$a->abreviatura . ' — ' . $a->descripcion])->values()->toJson();
    $nivelesArr       = $obra->niveles
        ->map(fn($n) => ['id'=>$n->id,'txt'=>$n->descripcion])->values()->toJson();
@endphp
const catConceptos  = {!! $catConceptosArr !!};
const catMateriales = {!! $catMaterialesArr !!};
const catMaquinaria = {!! $catMaquinariaArr !!};
const catManoObra   = {!! $catManoObraArr !!};
const unidades      = {!! $unidadesArr !!};
const catBloques    = {!! $bloquesArr !!};
const catAreas      = {!! $areasArr !!};
const catNiveles    = {!! $nivelesArr !!};

const editRouteTemplate   = '{{ route("obra_conceptos.edit", ["id" => ":id"]) }}';
const updateRouteTemplate = '{{ route("obra_conceptos.update", ["id" => ":id"]) }}';

function optsUni(selId = '') {
    return '<option value="">N/A</option>' +
        unidades.map(u => `<option value="${u.id}" ${u.id == selId ? 'selected' : ''}>${u.txt}</option>`).join('');
}

/* ── DESGLOSE ── */
let todosOpen = false;
function toggleDesglose(id) {
    const row = document.getElementById('desglose_' + id);
    const ico = document.getElementById('ico_' + id);
    row.classList.toggle('open');
    ico.className = row.classList.contains('open') ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
}
function toggleTodosDesgloses() {
    todosOpen = !todosOpen;
    document.querySelectorAll('.row-desglose').forEach(el => el.classList.toggle('open', todosOpen));
    document.querySelectorAll('[id^="ico_"]').forEach(ico => {
        ico.className = todosOpen ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
    });
}

/* ── PANEL EDICIÓN COMPLETA ── */
let currentEditId = null;

async function openEditPanel(id) {
    if (!id) {
        console.error("openEditPanel: El ID del renglón no es válido o está vacío:", id);
        return;
    }
    currentEditId = id;
    document.getElementById('epOverlay').classList.add('open');
    document.getElementById('editPanel').classList.add('open');
    document.getElementById('epBody').innerHTML = '<div class="ep-loading"><i class="bi bi-arrow-repeat spin-icon"></i> Cargando datos…</div>';
    document.getElementById('btnEpSave').disabled = true;

    const targetUrl = editRouteTemplate.replace(':id', id);
    console.log("openEditPanel: Cargando datos desde la URL:", targetUrl);

    try {
        const res  = await fetch(targetUrl);
        console.log("openEditPanel: Código de respuesta HTTP:", res.status);
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        const data = await res.json();
        console.log("openEditPanel: Datos cargados exitosamente:", data);
        renderEditPanel(data);
        document.getElementById('btnEpSave').disabled = false;
    } catch(e) {
        console.error("openEditPanel: Error al cargar/procesar datos:", e);
        document.getElementById('epBody').innerHTML = '<div class="ep-loading" style="color:#dc2626;"><i class="bi bi-x-circle"></i> Error al cargar datos</div>';
    }
}

function closeEditPanel() {
    document.getElementById('epOverlay').classList.remove('open');
    document.getElementById('editPanel').classList.remove('open');
    currentEditId = null;
}

function renderEditPanel(d) {
    let insIdx = Date.now();

    const matRows = d.materiales.map((m,i) => insRow(insIdx+i, 'material', m)).join('');
    const moRows  = d.mano_obra.map((m,i) => insRow(insIdx+100+i, 'mano_obra', m)).join('');
    const maqRows = d.maquinaria.map((m,i) => insRow(insIdx+200+i, 'maquinaria', m)).join('');

    const optUnidades = unidades.map(u => 
        `<option value="${u.id}" ${u.id == d.id_unidad_medida ? 'selected' : ''}>${u.txt}</option>`
    ).join('');
    const optBloques = catBloques.map(b => 
        `<option value="${b.id}" ${b.id == d.id_bloque ? 'selected' : ''}>${b.txt}</option>`
    ).join('');
    const optAreas = catAreas.map(a => 
        `<option value="${a.id}" ${a.id == d.id_area ? 'selected' : ''}>${a.txt}</option>`
    ).join('');
    const optNiveles = '<option value="">— Sin Nivel —</option>' + catNiveles.map(n => 
        `<option value="${n.id}" ${n.id == d.id_nivel ? 'selected' : ''}>${n.txt}</option>`
    ).join('');

    document.getElementById('epBody').innerHTML = `
    <div class="ep-section">
        <div class="ep-section-title">Concepto</div>
        <div class="ep-field">
            <label>Descripción</label>
            <div class="ep-ac-wrap">
                <input type="text" id="ep_txt_cpt" value="${esc(d.descripcion)}" placeholder="Buscar o escribir…" autocomplete="off">
                <input type="hidden" id="ep_id_cpt" value="${d.id_concepto ?? ''}">
                <div class="ep-ac-list" id="ep_list_cpt"></div>
            </div>
        </div>
        <div class="ep-row">
            <div class="ep-field">
                <label>Unidad de Medida</label>
                <select id="ep_uni_cpt">${optUnidades}</select>
            </div>
            <div class="ep-field">
                <label>Nivel / Planta</label>
                <select id="ep_nivel_cpt">${optNiveles}</select>
            </div>
        </div>
        <div class="ep-row" style="margin-top: 10px;">
            <div class="ep-field">
                <label>Bloque</label>
                <select id="ep_bloque_cpt">${optBloques}</select>
            </div>
            <div class="ep-field">
                <label>Área</label>
                <select id="ep_area_cpt">${optAreas}</select>
            </div>
        </div>
        <div class="ep-row" style="margin-top: 10px;">
            <div class="ep-field">
                <label>Cantidad</label>
                <input type="number" id="ep_cant" value="${d.cantidad}" min="0.001" step="0.001" oninput="recalcEp()">
            </div>
            <div class="ep-field">
                <label>% IVA</label>
                <input type="number" id="ep_iva" value="${d.porcentaje_iva}" min="0" max="100" step="1">
            </div>
        </div>
    </div>

    <!-- Materiales -->
    <div class="ep-section">
        <div class="ep-ins-head mat">
            <span><i class="bi bi-box-seam me-1"></i>Materiales</span>
            <button class="btn-ep-add mat" onclick="addEpIns('material')"><i class="bi bi-plus"></i> Agregar</button>
        </div>
        <table class="ep-ins-table" id="ep_tb_mat">
            <thead><tr><th style="width:42%">Insumo</th><th style="width:16%">Unidad</th><th style="width:14%">Cant.</th><th style="width:18%">P.U.</th><th style="width:10%">Sub.</th><th></th></tr></thead>
            <tbody>${matRows}</tbody>
        </table>
    </div>

    <!-- Mano de Obra -->
    <div class="ep-section">
        <div class="ep-ins-head mo">
            <span><i class="bi bi-person-lines-fill me-1"></i>Mano de Obra</span>
            <button class="btn-ep-add mo" onclick="addEpIns('mano_obra')"><i class="bi bi-plus"></i> Agregar</button>
        </div>
        <table class="ep-ins-table" id="ep_tb_mo">
            <thead><tr><th style="width:42%">Insumo</th><th style="width:16%">Unidad</th><th style="width:14%">Cant.</th><th style="width:18%">P.U.</th><th style="width:10%">Sub.</th><th></th></tr></thead>
            <tbody>${moRows}</tbody>
        </table>
    </div>

    <!-- Maquinaria -->
    <div class="ep-section">
        <div class="ep-ins-head maq">
            <span><i class="bi bi-truck me-1"></i>Maquinaria</span>
            <button class="btn-ep-add maq" onclick="addEpIns('maquinaria')"><i class="bi bi-plus"></i> Agregar</button>
        </div>
        <table class="ep-ins-table" id="ep_tb_maq">
            <thead><tr><th style="width:42%">Insumo</th><th style="width:16%">Unidad</th><th style="width:14%">Cant.</th><th style="width:18%">P.U.</th><th style="width:10%">Sub.</th><th></th></tr></thead>
            <tbody>${maqRows}</tbody>
        </table>
    </div>

    <div class="ep-pu-bar">
        <span><i class="bi bi-calculator-fill me-1"></i>P.U. Calculado</span>
        <strong id="ep_pu_display">$0.00</strong>
    </div>`;

    // Setup autocomplete concepto
    setupEpAC(document.getElementById('ep_txt_cpt'), document.getElementById('ep_id_cpt'), document.getElementById('ep_list_cpt'), catConceptos);

    // Setup autocompletes de insumos ya renderizados
    document.querySelectorAll('[data-ep-ai]').forEach(inp => {
        const ii   = inp.dataset.epAi;
        const tipo = inp.dataset.epTipo;
        const cat  = tipo === 'material' ? catMateriales : tipo === 'maquinaria' ? catMaquinaria : catManoObra;
        const idFld = document.getElementById(`ep_id_${ii}`);
        const puFld = document.getElementById(`ep_pu_${ii}`);
        const uniFld= document.getElementById(`ep_uni_${ii}`);
        setupEpAC(inp, idFld, document.getElementById(`ep_list_${ii}`), cat, puFld, uniFld, ii);
    });

    recalcEp();
}

function insRow(ii, tipo, data = null) {
    const nombre = data?.nombre ?? '';
    const refId  = data ? (tipo === 'material' ? data.id_material : tipo === 'maquinaria' ? data.id_maquinaria : data.id_mano_obra) : '';
    const cant   = data?.cantidad ?? 1;
    const pu     = data?.precio_unitario ?? 0;
    const uniId  = data?.id_unidad_medida ?? '';
    const sub    = (cant * pu).toFixed(2);
    return `
    <tr id="ep_row_${ii}" data-tipo="${tipo}">
        <td>
            <div class="ep-ac-wrap">
                <input type="text" data-ep-ai="${ii}" data-ep-tipo="${tipo}" value="${esc(nombre)}" placeholder="Buscar…" autocomplete="off">
                <input type="hidden" id="ep_id_${ii}" value="${refId}">
                <div class="ep-ac-list" id="ep_list_${ii}"></div>
            </div>
        </td>
        <td><select class="ep-ins-uni" id="ep_uni_${ii}">${optsUni(uniId)}</select></td>
        <td><input type="number" id="ep_cant_${ii}" value="${cant}" min="0.001" step="0.001" oninput="updEpSub('${ii}');recalcEp();"></td>
        <td><input type="number" id="ep_pu_${ii}" value="${pu}" min="0" step="0.01" oninput="updEpSub('${ii}');recalcEp();"></td>
        <td><span id="ep_sub_${ii}" style="font-size:.8rem;font-weight:700;">$${sub}</span></td>
        <td><button type="button" class="btn-del-ep" onclick="document.getElementById('ep_row_${ii}').remove();recalcEp();"><i class="bi bi-x-lg"></i></button></td>
    </tr>`;
}

function updEpSub(ii) {
    const c = parseFloat(document.getElementById(`ep_cant_${ii}`)?.value) || 0;
    const p = parseFloat(document.getElementById(`ep_pu_${ii}`)?.value)   || 0;
    const s = document.getElementById(`ep_sub_${ii}`);
    if (s) s.textContent = '$' + (c*p).toFixed(2);
}

function recalcEp() {
    let total = 0;
    ['ep_tb_mat','ep_tb_mo','ep_tb_maq'].forEach(tbId => {
        const tb = document.getElementById(tbId);
        if (!tb) return;
        tb.querySelectorAll('tbody tr').forEach(tr => {
            const cant = parseFloat(tr.querySelector('[id^="ep_cant_"]')?.value) || 0;
            const pu   = parseFloat(tr.querySelector('[id^="ep_pu_"]')?.value)   || 0;
            total += cant * pu;
        });
    });
    const d = document.getElementById('ep_pu_display');
    if (d) d.textContent = '$' + total.toFixed(2);
}

let epInsIdx = 5000;
function addEpIns(tipo) {
    epInsIdx++;
    const tbId  = tipo === 'material' ? 'ep_tb_mat' : tipo === 'maquinaria' ? 'ep_tb_maq' : 'ep_tb_mo';
    const tbody = document.querySelector(`#${tbId} tbody`);
    const ii    = epInsIdx;
    tbody.insertAdjacentHTML('beforeend', insRow(ii, tipo));

    const inp   = document.querySelector(`[data-ep-ai="${ii}"]`);
    const idFld = document.getElementById(`ep_id_${ii}`);
    const puFld = document.getElementById(`ep_pu_${ii}`);
    const uFld  = document.getElementById(`ep_uni_${ii}`);
    const cat   = tipo === 'material' ? catMateriales : tipo === 'maquinaria' ? catMaquinaria : catManoObra;
    setupEpAC(inp, idFld, document.getElementById(`ep_list_${ii}`), cat, puFld, uFld, ii);
}

function setupEpAC(inp, idFld, list, cat, puFld = null, uniFld = null, ii = null) {
    inp.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        const filtered = cat.filter(c => c.texto.toLowerCase().includes(q)).slice(0,10);
        list.innerHTML = '';
        filtered.forEach(c => {
            const div = document.createElement('div');
            div.className   = 'ep-ac-item';
            div.textContent = c.texto;
            div.onclick = () => {
                inp.value   = c.texto;
                idFld.value = c.id;
                if (puFld)  puFld.value  = c.pu ?? 0;
                if (uniFld) uniFld.value = c.uni ?? '';
                list.style.display = 'none';
                if (ii) { updEpSub(ii); recalcEp(); }
            };
            list.appendChild(div);
        });
        const nv = document.createElement('div');
        nv.className   = 'ep-ac-item nuevo';
        nv.innerHTML   = '<i class="bi bi-plus-circle me-1"></i>Registrar como nuevo';
        nv.onclick = () => { idFld.value = ''; list.style.display = 'none'; };
        list.appendChild(nv);
        list.style.display = q.length > 0 ? 'block' : 'none';
    });
    document.addEventListener('click', e => { if (e.target !== inp) list.style.display = 'none'; });
}

async function guardarEdicionCompleta() {
    if (!currentEditId) return;
    const btn = document.getElementById('btnEpSave');
    btn.disabled = true;
    btn.textContent = 'Guardando…';

    const idCpt  = document.getElementById('ep_id_cpt').value;
    const txtCpt = document.getElementById('ep_txt_cpt').value.trim();
    const cant   = parseFloat(document.getElementById('ep_cant').value) || 1;
    const pIva   = parseFloat(document.getElementById('ep_iva').value)  || 0;
    
    const idNivel  = document.getElementById('ep_nivel_cpt').value;
    const idBloque = document.getElementById('ep_bloque_cpt').value;
    const idArea   = document.getElementById('ep_area_cpt').value;
    const idUni    = document.getElementById('ep_uni_cpt').value;

    // Calcular PU total de insumos
    let totalPU = 0;
    ['ep_tb_mat','ep_tb_mo','ep_tb_maq'].forEach(tbId => {
        const tb = document.getElementById(tbId);
        if (!tb) return;
        tb.querySelectorAll('tbody tr').forEach(tr => {
            const c2 = parseFloat(tr.querySelector('[id^="ep_cant_"]')?.value) || 0;
            const p2 = parseFloat(tr.querySelector('[id^="ep_pu_"]')?.value)   || 0;
            totalPU += c2 * p2;
        });
    });

    // Recolectar insumos
    const materiales = [], maquinaria = [], mano_obra = [];
    [['ep_tb_mat','material',materiales],['ep_tb_mo','mano_obra',mano_obra],['ep_tb_maq','maquinaria',maquinaria]].forEach(([tbId, tipo, arr]) => {
        const tb = document.getElementById(tbId);
        if (!tb) return;
        tb.querySelectorAll('tbody tr').forEach(tr => {
            const ii    = tr.id.replace('ep_row_','');
            const iId   = document.getElementById(`ep_id_${ii}`)?.value;
            const iTxt  = tr.querySelector('[data-ep-ai]')?.value?.trim();
            const iCant = parseFloat(document.getElementById(`ep_cant_${ii}`)?.value) || 0;
            const iPu   = parseFloat(document.getElementById(`ep_pu_${ii}`)?.value)   || 0;
            const iUni  = document.getElementById(`ep_uni_${ii}`)?.value;
            if (!iId && !iTxt) return;
            const key = tipo === 'material' ? 'id_material' : tipo === 'maquinaria' ? 'id_maquinaria' : 'id_mano_obra';
            arr.push({ [key]: iId||null, nombre_nuevo: iId?'':iTxt, id_unidad_medida: iUni||null, cantidad: iCant, precio_unitario: iPu });
        });
    });

    const payload = {
        id_concepto: idCpt || null,
        nombre_nuevo: idCpt ? '' : txtCpt,
        id_nivel: idNivel || null,
        id_bloque: idBloque || null,
        id_area: idArea || null,
        id_unidad_medida: idUni || null,
        cantidad: cant,
        precio_unitario: totalPU,
        porcentaje_iva: pIva,
        materiales, maquinaria, mano_obra,
    };

    const targetUrl = updateRouteTemplate.replace(':id', currentEditId);
    console.log("guardarEdicionCompleta: Enviando PATCH a:", targetUrl, "Payload:", payload);

    try {
        const res  = await fetch(targetUrl, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(payload),
        });
        console.log("guardarEdicionCompleta: HTTP Status:", res.status);
        const json = await res.json();
        if (res.ok && json.success) {
            showToast('✓ Renglón guardado', 'ok');
            closeEditPanel();
            
            // Recargar la página para reflejar todos los cambios de bloque, nivel, área y totales
            setTimeout(() => { window.location.reload(); }, 600);
        } else {
            console.error("guardarEdicionCompleta: Error devuelto por servidor:", json);
            showToast('Error: ' + (json.message || 'No se pudo guardar'), 'err');
        }
    } catch(e) {
        console.error("guardarEdicionCompleta: Error de conexión:", e);
        showToast('Error de conexión', 'err');
    }
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Guardar Cambios';
}

/* ── MODAL ELIMINAR ── */
let currentDelId = null;
function confirmDel(id, nombre) {
    currentDelId = id;
    document.getElementById('del_nombre').textContent = nombre;
    document.getElementById('modalEliminar').classList.add('open');
}
function closeDel() {
    document.getElementById('modalEliminar').classList.remove('open');
    currentDelId = null;
}

/* ── TOAST ── */
function showToast(msg, tipo) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className   = tipo;
    t.style.display = 'flex';
    setTimeout(() => { t.style.display = 'none'; }, 4200);
}

function esc(s) {
    return String(s ?? '').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeEditPanel(); closeDel(); }
});
</script>
@endsection

