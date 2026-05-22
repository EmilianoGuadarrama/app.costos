@extends('layout')
@section('title', 'Agregar al Presupuesto — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root {
    --dark:#111827; --mid:#374151; --soft:#6b7280; --line:#e5e7eb;
    --bg:#f3f4f6;   --white:#fff;
    --blue:#2563eb; --blue-l:#eff6ff; --blue-b:#bfdbfe;
    --green:#059669; --green-l:#f0fdf4; --green-b:#bbf7d0;
    --amber:#d97706; --amber-l:#fffbeb; --amber-b:#fde68a;
    --red:#dc2626;
}
body{background:var(--bg);font-family:'Inter','Segoe UI',sans-serif;}

/* ── HEADER ── */
.pu-hdr{
    display:flex;justify-content:space-between;align-items:center;
    background:var(--dark);color:#fff;padding:16px 26px;
    position:sticky;top:0;z-index:300;
    border-bottom:3px solid var(--blue);
    box-shadow:0 4px 24px rgba(0,0,0,.5);
}
.pu-hdr-left h1{font-family:'Garamond','Baskerville',serif;font-size:1.35rem;margin:0;}
.pu-hdr-left p{margin:2px 0 0;font-size:.8rem;color:#9ca3af;}
.btn-back{background:rgba(255,255,255,.08);color:#d1d5db;border:1px solid rgba(255,255,255,.15);
    border-radius:7px;padding:5px 13px;font-size:.8rem;text-decoration:none;
    transition:.2s;display:inline-flex;align-items:center;gap:5px;margin-bottom:5px;}
.btn-back:hover{background:rgba(255,255,255,.18);color:#fff;}
.btn-save{
    background:var(--blue);color:#fff;border:none;border-radius:10px;
    padding:11px 26px;font-weight:700;font-size:.9rem;cursor:pointer;
    display:inline-flex;align-items:center;gap:8px;transition:.2s;
    box-shadow:0 4px 14px rgba(37,99,235,.45);
}
.btn-save:hover{background:#1d4ed8;transform:translateY(-1px);box-shadow:0 6px 20px rgba(37,99,235,.55);}
.btn-save:disabled{opacity:.6;cursor:not-allowed;transform:none;}

/* ── GLOBALS BAR ── */
.pu-globals{
    background:#fff;border-bottom:1px solid var(--line);
    padding:13px 26px;display:flex;gap:18px;flex-wrap:wrap;align-items:flex-end;
}
.gl-group{display:flex;flex-direction:column;gap:3px;flex:1;min-width:150px;}
.gl-group label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--soft);}
.gl-group select{border:1.5px solid var(--line);border-radius:8px;padding:8px 10px;font-size:.85rem;color:var(--dark);background:var(--bg);}
.gl-group select:focus{border-color:var(--blue);outline:none;}

/* ── BODY ── */
.pu-body{padding:20px 26px;}
.btn-add-cpt{
    background:var(--dark);color:#fff;border:none;border-radius:10px;
    padding:10px 20px;font-weight:700;font-size:.85rem;cursor:pointer;
    display:inline-flex;align-items:center;gap:7px;transition:.2s;margin-bottom:16px;
}
.btn-add-cpt:hover{background:var(--mid);transform:translateY(-1px);}

/* ── TARJETA CONCEPTO ── */
.cpt-card{background:#fff;border-radius:14px;border:1px solid var(--line);
    margin-bottom:18px;box-shadow:0 2px 8px rgba(0,0,0,.04);overflow:hidden;}
.cpt-hdr{
    background:var(--dark);color:#fff;padding:12px 18px;
    display:flex;justify-content:space-between;align-items:center;
}
.cpt-hdr h3{margin:0;font-size:.95rem;font-weight:700;display:flex;align-items:center;gap:7px;}
.btn-quitar{background:rgba(220,38,38,.15);color:#fca5a5;border:1px solid rgba(220,38,38,.25);
    border-radius:7px;padding:4px 12px;font-size:.78rem;cursor:pointer;transition:.2s;
    display:inline-flex;align-items:center;gap:4px;}
.btn-quitar:hover{background:var(--red);color:#fff;border-color:var(--red);}

/* ── CAMPOS CONCEPTO ── */
.cpt-flds{
    padding:14px 18px 10px;background:#f8faff;border-bottom:1px solid var(--line);
    display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;
}
.fld{display:flex;flex-direction:column;gap:3px;}
.fld label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--soft);}
.fld input,.fld select{
    width:100%; box-sizing:border-box;
    border:1.5px solid var(--line);border-radius:7px;padding:7px 9px;
    font-size:.85rem;color:var(--dark);background:#fff;transition:.15s;
}
.fld input:focus,.fld select:focus{border-color:var(--blue);outline:none;}
.fld.f-desc{flex:3;min-width:200px;}
.fld.f-sm{flex:1;min-width:110px;}
.fld.f-xs{flex:0 0 75px;}
.fld.f-pu{flex:0 0 110px;}
.pu-display{
    background:var(--dark);color:#fff;border-radius:7px;padding:7px 10px;
    font-size:.85rem;font-weight:700;text-align:center;min-width:90px;
    display:inline-block;border:none;
}

/* ── AUTOCOMPLETE ── */
.ac-wrap{position:relative;}
.ac-list{
    position:absolute;top:100%;left:0;right:0;background:#fff;
    border:1.5px solid var(--line);border-top:none;
    max-height:220px;overflow-y:auto;z-index:9999;
    display:none;border-radius:0 0 10px 10px;
    box-shadow:0 10px 25px rgba(0,0,0,.12);
}
.ac-item{padding:9px 13px;cursor:pointer;font-size:.85rem;color:var(--mid);border-bottom:1px solid #f3f4f6;}
.ac-item:hover{background:#eff6ff;color:var(--blue);}
.ac-item.nuevo{color:var(--blue);font-weight:700;background:#f0f9ff;}

/* ── INSUMOS INLINE ── */
.ins-wrap{padding:14px 18px;}
.ins-row-header{
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:6px;padding:7px 12px;border-radius:8px;font-size:.8rem;font-weight:700;
}
.ins-row-header.mat{background:var(--blue-l);color:var(--blue);border:1px solid var(--blue-b);}
.ins-row-header.mo {background:var(--green-l);color:var(--green);border:1px solid var(--green-b);}
.ins-row-header.maq{background:var(--amber-l);color:var(--amber);border:1px solid var(--amber-b);}
.btn-ai{border:none;border-radius:7px;padding:4px 12px;font-size:.76rem;font-weight:700;
    cursor:pointer;display:inline-flex;align-items:center;gap:4px;transition:.2s;}
.btn-ai.mat{background:var(--blue);color:#fff;}
.btn-ai.mo {background:var(--green);color:#fff;}
.btn-ai.maq{background:var(--amber);color:#fff;}
.btn-ai:hover{opacity:.85;transform:translateY(-1px);}
.ins-table{width:100%;border-collapse:collapse;margin-bottom:8px;}
.ins-table th{
    padding:5px 8px;font-size:.66rem;font-weight:700;text-transform:uppercase;
    letter-spacing:.4px;color:var(--soft);border-bottom:1px solid var(--line);background:#fafafa;
}
.ins-table td{padding:5px 7px;border-bottom:1px solid #f8f9fa;vertical-align:middle;}
.ins-table input,.ins-table select{
    width:100%;border:1.5px solid var(--line);border-radius:6px;
    padding:5px 7px;font-size:.82rem;background:#fff;color:var(--dark);
}
.ins-table input:focus,.ins-table select:focus{border-color:var(--blue);outline:none;}
.btn-del{background:#fef2f2;color:var(--red);border:1px solid #fecaca;
    border-radius:5px;padding:3px 7px;cursor:pointer;font-size:.78rem;transition:.15s;}
.btn-del:hover{background:var(--red);color:#fff;}

/* ── P.U. resumen ── */
.pu-calc{
    display:flex;align-items:center;gap:8px;padding:8px 14px;
    background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;margin-top:8px;
    font-size:.82rem;color:var(--green);font-weight:700;
}
.pu-calc span{color:var(--dark);font-weight:400;}

/* ── Toast ── */
#toast{
    position:fixed;bottom:22px;right:22px;z-index:9999;
    padding:12px 20px;border-radius:10px;font-weight:600;font-size:.88rem;
    display:none;align-items:center;gap:9px;box-shadow:0 10px 25px rgba(0,0,0,.2);
}
#toast.ok {background:#059669;color:#fff;}
#toast.err{background:#dc2626;color:#fff;}
@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}
</style>

{{-- HEADER --}}
<div class="pu-hdr">
    <div class="pu-hdr-left">
        <a href="{{ route('obras.presupuesto', $obra->id) }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Volver al Presupuesto
        </a>
        <h1><i class="bi bi-layers me-2" style="color:#60a5fa;"></i>Agregar Renglones al Presupuesto</h1>
        <p>Obra: <strong style="color:#fff;">{{ $obra->datosDeObra?->nombre }}</strong></p>
    </div>
    <button class="btn-save" onclick="guardarPresupuesto()" id="btnGuardar">
        <i class="bi bi-cloud-arrow-up-fill"></i> Agregar Conceptos
    </button>
</div>

{{-- AJUSTES GLOBALES --}}
<div class="pu-globals">
    <div class="gl-group">
        <label>Bloque por defecto</label>
        <div class="ac-wrap" style="width:100%;">
            <input type="text" id="g_bloque_txt" placeholder="Buscar o crear..." autocomplete="off" style="width:100%; border:1.5px solid var(--line); border-radius:8px; padding:8px 10px; font-size:.85rem; background:var(--bg);">
            <input type="hidden" id="g_bloque">
            <div class="ac-list" id="g_bloque_list"></div>
        </div>
    </div>
    <div class="gl-group">
        <label>Área por defecto</label>
        <div class="ac-wrap" style="width:100%;">
            <input type="text" id="g_area_txt" placeholder="Buscar o crear..." autocomplete="off" style="width:100%; border:1.5px solid var(--line); border-radius:8px; padding:8px 10px; font-size:.85rem; background:var(--bg);">
            <input type="hidden" id="g_area">
            <div class="ac-list" id="g_area_list"></div>
        </div>
    </div>
    <div class="gl-group">
        <label>Nivel / Planta por defecto</label>
        <select id="g_nivel">
            <option value="">— Nivel —</option>
            @foreach($niveles as $n)
                <option value="{{ $n->id }}" @if($niveles->count() === 1) selected @endif>
                    {{ $n->descripcion }}
                </option>
            @endforeach
        </select>
    </div>
</div>

{{-- BODY --}}
<div class="pu-body">
    <button type="button" class="btn-add-cpt" onclick="addConcepto()">
        <i class="bi bi-plus-circle-fill"></i> Agregar Nuevo Concepto
    </button>
    <div id="conceptosContainer"></div>
    
    {{-- RESUMEN TOTAL --}}
    <div class="pu-footer-total" style="background:#fff; border:1px solid var(--line); border-radius:12px; padding:20px; margin-top:20px; box-shadow:0 4px 15px rgba(0,0,0,0.05); display:flex; justify-content:flex-end; gap:30px;">
        <div style="text-align:right;">
            <p style="margin:0; font-size:0.85rem; color:var(--soft); font-weight:700; text-transform:uppercase;">Subtotal</p>
            <h4 id="tot_subtotal" style="margin:0; font-size:1.2rem; color:var(--mid);">$0.00</h4>
        </div>
        <div style="text-align:right;">
            <p style="margin:0; font-size:0.85rem; color:var(--soft); font-weight:700; text-transform:uppercase;">I.V.A.</p>
            <h4 id="tot_iva" style="margin:0; font-size:1.2rem; color:var(--mid);">$0.00</h4>
        </div>
        <div style="text-align:right;">
            <p style="margin:0; font-size:0.85rem; color:var(--soft); font-weight:700; text-transform:uppercase;">Total a Agregar</p>
            <h4 id="tot_final" style="margin:0; font-size:1.5rem; color:var(--blue); font-weight:800;">$0.00</h4>
        </div>
    </div>
</div>

<div id="toast"></div>

<script>
/* ─────────── DATOS DEL SERVIDOR ─────────── */
@php
    // Incluir composición en los conceptos para auto-cargar insumos
    $catConceptosArr = $conceptos->map(function($c) {
        return [
            'id'          => $c->id,
            'texto'       => $c->descripcion,
            'pu'          => $c->p_u,
            'uni'         => $c->id_unidad_medida,
            'composicion' => $c->composicion->map(fn($comp) => [
                'tipo'        => $comp->tipo,
                'ref_id'      => $comp->referencia_id,
                'descripcion' => $comp->descripcion_referencia,
                'cantidad'    => $comp->cantidad,
                'unidad'      => $comp->unidad,
            ])->values()->toArray(),
        ];
    })->values()->toJson();

    $catMaterialesArr = $materiales->map(fn($m) => [
        'id'    => $m->id,
        'texto' => $m->nombre,
        'pu'    => $m->precio_x_unidad,
        'uni'   => $m->id_unidad_medida,
        'uniTxt'=> $m->unidadMedida?->abreviatura ?? '',
    ])->values()->toJson();

    $catMaquinariaArr = $maquinaria->map(fn($m) => [
        'id'    => $m->id,
        'texto' => $m->nombre,
        'pu'    => $m->precio_x_unidad,
        'uni'   => $m->id_unidad_medida,
        'uniTxt'=> $m->unidadMedida?->abreviatura ?? '',
    ])->values()->toJson();

    $catManoObraArr = $mano_obra->map(fn($m) => [
        'id'    => $m->id,
        'texto' => $m->nombre,
        'pu'    => $m->precio_x_unidad,
        'uni'   => $m->id_unidad_medida,
        'uniTxt'=> $m->unidadMedida?->abreviatura ?? '',
    ])->values()->toJson();

    $catAreasArr = $areas->map(fn($a) => [
        'id'    => $a->id,
        'texto' => $a->abreviatura . ($a->descripcion ? ' - '.$a->descripcion : ''),
    ])->values()->toJson();

    $catBloquesArr = $bloques->map(fn($b) => [
        'id'    => $b->id,
        'texto' => $b->descripcion,
    ])->values()->toJson();
@endphp

const catConceptos  = {!! $catConceptosArr !!};
const catMateriales = {!! $catMaterialesArr !!};
const catMaquinaria = {!! $catMaquinariaArr !!};
const catManoObra   = {!! $catManoObraArr !!};
const catAreas      = {!! $catAreasArr !!};
const catBloques    = {!! $catBloquesArr !!};

const bloques   = @json($bloques);
const unidades  = @json($unidades);
const niveles   = @json($niveles);
const storeUrl  = '{{ route("obras.presupuesto.unificado.store", $obra->id) }}';
const csrfToken = '{{ csrf_token() }}';

let conceptIndex = 0;

/* ─────────── HELPERS DE OPCIONES ─────────── */
function optsUnidades(selId = '') {
    return `<option value="">N/A</option>` +
        unidades.map(u => `<option value="${u.id}" ${u.id == selId ? 'selected' : ''}>${u.abreviatura}</option>`).join('');
}
function optsBloques() {
    return `<option value="">N/A</option>` +
        bloques.map(b => `<option value="${b.id}">${b.descripcion}</option>`).join('');
}
function optsNiveles() {
    const autoId = niveles.length === 1 ? niveles[0].id : '';
    return `<option value="">N/A</option>` +
        niveles.map(n => `<option value="${n.id}" ${n.id == autoId ? 'selected' : ''}>${n.descripcion}</option>`).join('');
}

/* ─────────── RECALCULAR P.U. AUTOMÁTICO ─────────── */
function recalcPU(ci) {
    let total = 0;
    ['mat','mo','maq'].forEach(prefix => {
        const tbl = document.querySelector(`#tb_${prefix}_${ci} tbody`);
        if (!tbl) return;
        tbl.querySelectorAll('tr').forEach(tr => {
            const cant = parseFloat(tr.querySelector('.i-cant')?.value) || 0;
            const pu   = parseFloat(tr.querySelector('.i-pu')?.value)   || 0;
            total += cant * pu;
        });
    });

    const display = document.getElementById(`pu_display_${ci}`);
    const hidden  = document.getElementById(`pu_hidden_${ci}`);
    if (display) display.textContent = '$' + total.toFixed(2);
    if (hidden)  hidden.value = total.toFixed(4);
    
    updateGlobalTotals();
}

/* ─────────── AGREGAR CONCEPTO ─────────── */
function addConcepto() {
    conceptIndex++;
    const ci   = conceptIndex;
    const nVal = document.getElementById('g_nivel').value || (niveles.length === 1 ? niveles[0].id : '');

    const html = `
    <div class="cpt-card" id="card_c_${ci}" data-ci="${ci}">

        {{-- Cabecera --}}
        <div class="cpt-hdr">
            <h3><i class="bi bi-layers-fill" style="color:#60a5fa;"></i> Concepto #${ci}</h3>
            <button class="btn-quitar" onclick="document.getElementById('card_c_${ci}').remove(); updateGlobalTotals();">
                <i class="bi bi-trash3"></i> Quitar
            </button>
        </div>

        {{-- Campos del concepto --}}
        <div class="cpt-flds">
            <div class="fld f-desc">
                <label>Descripción del Concepto</label>
                <div class="ac-wrap">
                    <input type="text" class="c-desc" id="c_txt_${ci}" placeholder="Buscar o escribir nuevo…" autocomplete="off">
                    <input type="hidden" class="c-id" id="c_id_${ci}">
                    <div class="ac-list" id="c_list_${ci}"></div>
                </div>
            </div>
            <div class="fld f-sm">
                <label>Unidad</label>
                <select class="c-uni">${optsUnidades()}</select>
            </div>
            <div class="fld f-sm">
                <label>Nivel / Planta</label>
                <select class="c-nivel">${optsNiveles()}</select>
            </div>
            <div class="fld f-sm">
                <label>Bloque</label>
                <div class="ac-wrap">
                    <input type="text" class="c-bloque-txt" id="b_txt_${ci}" placeholder="Buscar o crear..." autocomplete="off">
                    <input type="hidden" class="c-bloque" id="b_id_${ci}">
                    <div class="ac-list" id="b_list_${ci}"></div>
                </div>
            </div>
            <div class="fld f-sm">
                <label>Área</label>
                <div class="ac-wrap">
                    <input type="text" class="c-area-txt" id="a_txt_${ci}" placeholder="Buscar o crear..." autocomplete="off">
                    <input type="hidden" class="c-area" id="a_id_${ci}">
                    <div class="ac-list" id="a_list_${ci}"></div>
                </div>
            </div>
            <div class="fld f-xs">
                <label>Cantidad</label>
                <input type="number" class="c-cant" value="1" min="0.01" step="0.01">
            </div>
            <div class="fld f-pu">
                <label>P.U. Calculado</label>
                <span class="pu-display" id="pu_display_${ci}">$0.00</span>
                <input type="hidden" class="c-pu" id="pu_hidden_${ci}" value="0">
            </div>
        </div>

        {{-- Secciones de Insumos --}}
        <div class="ins-wrap">

            {{-- MATERIALES --}}
            <div class="ins-row-header mat">
                <span><i class="bi bi-box-seam me-1"></i>Materiales</span>
                <button class="btn-ai mat" onclick="addInsumo(${ci},'material')"><i class="bi bi-plus"></i> Agregar Material</button>
            </div>
            <table class="ins-table" id="tb_mat_${ci}">
                <thead><tr>
                    <th style="width:42%">Insumo</th>
                    <th style="width:14%">Unidad</th>
                    <th style="width:12%">Cantidad</th>
                    <th style="width:16%">Precio Unit.</th>
                    <th style="width:10%">Subtotal</th>
                    <th style="width:6%"></th>
                </tr></thead>
                <tbody></tbody>
            </table>

            {{-- MANO DE OBRA --}}
            <div class="ins-row-header mo" style="margin-top:10px;">
                <span><i class="bi bi-person-lines-fill me-1"></i>Mano de Obra</span>
                <button class="btn-ai mo" onclick="addInsumo(${ci},'mano_obra')"><i class="bi bi-plus"></i> Agregar Mano de Obra</button>
            </div>
            <table class="ins-table" id="tb_mo_${ci}">
                <thead><tr>
                    <th style="width:42%">Insumo</th>
                    <th style="width:14%">Unidad</th>
                    <th style="width:12%">Rendimiento</th>
                    <th style="width:16%">Precio Unit.</th>
                    <th style="width:10%">Subtotal</th>
                    <th style="width:6%"></th>
                </tr></thead>
                <tbody></tbody>
            </table>

            {{-- MAQUINARIA --}}
            <div class="ins-row-header maq" style="margin-top:10px;">
                <span><i class="bi bi-truck me-1"></i>Maquinaria</span>
                <button class="btn-ai maq" onclick="addInsumo(${ci},'maquinaria')"><i class="bi bi-plus"></i> Agregar Maquinaria</button>
            </div>
            <table class="ins-table" id="tb_maq_${ci}">
                <thead><tr>
                    <th style="width:42%">Insumo</th>
                    <th style="width:14%">Unidad</th>
                    <th style="width:12%">Rendimiento</th>
                    <th style="width:16%">Precio Unit.</th>
                    <th style="width:10%">Subtotal</th>
                    <th style="width:6%"></th>
                </tr></thead>
                <tbody></tbody>
            </table>

            {{-- TOTAL P.U. --}}
            <div class="pu-calc">
                <i class="bi bi-calculator-fill"></i>
                <span>P.U. Total del Concepto:</span>
                <strong id="pu_label_${ci}">$0.00</strong>
            </div>
        </div>
    </div>`;

    document.getElementById('conceptosContainer').insertAdjacentHTML('beforeend', html);
    const card = document.getElementById(`card_c_${ci}`);

    const bVal = document.getElementById('g_bloque').value;
    const bTxt = document.getElementById('g_bloque_txt').value;
    if (bVal) {
        document.getElementById(`b_txt_${ci}`).value = bTxt;
        document.getElementById(`b_id_${ci}`).value = bVal;
    } else if (bTxt) {
        document.getElementById(`b_txt_${ci}`).value = bTxt;
        document.getElementById(`b_id_${ci}`).value = '';
    }

    const aVal = document.getElementById('g_area').value;
    const aTxt = document.getElementById('g_area_txt').value;
    if (aVal) {
        document.getElementById(`a_txt_${ci}`).value = aTxt;
        document.getElementById(`a_id_${ci}`).value = aVal;
    } else if (aTxt) {
        document.getElementById(`a_txt_${ci}`).value = aTxt;
        document.getElementById(`a_id_${ci}`).value = '';
    }
    if (nVal) card.querySelector('.c-nivel').value  = nVal;

    setupAC(document.getElementById(`c_txt_${ci}`), document.getElementById(`c_id_${ci}`), document.getElementById(`c_list_${ci}`), catConceptos, true, null, card.querySelector('.c-uni'), ci);
    setupAC(document.getElementById(`b_txt_${ci}`), document.getElementById(`b_id_${ci}`), document.getElementById(`b_list_${ci}`), catBloques, false, null, null, null);
    setupAC(document.getElementById(`a_txt_${ci}`), document.getElementById(`a_id_${ci}`), document.getElementById(`a_list_${ci}`), catAreas, false, null, null, null);

    // Update global totals when quantities change
    card.querySelector('.c-cant').addEventListener('input', updateGlobalTotals);
}

/* ─────────── AGREGAR INSUMO ─────────── */
function addInsumo(ci, tipo, prefill = null) {
    const prefix = tipo === 'material' ? 'mat' : tipo === 'maquinaria' ? 'maq' : 'mo';
    const tbody  = document.querySelector(`#tb_${prefix}_${ci} tbody`);
    const ii     = Date.now() + Math.floor(Math.random() * 9999);

    const nombre = prefill?.descripcion ?? '';
    const refId  = prefill?.ref_id      ?? '';
    const cant   = prefill?.cantidad    ?? 1;
    const uniSel = prefill?.unidad      ?? '';

    const cat    = tipo === 'material' ? catMateriales : tipo === 'maquinaria' ? catMaquinaria : catManoObra;
    const found  = refId ? cat.find(c => c.id == refId) : null;
    const puVal  = found?.pu ?? 0;
    const uniId  = found?.uni ?? '';

    const row = `
    <tr id="row_${ii}" data-tipo="${tipo}">
        <td>
            <div class="ac-wrap">
                <input type="text" class="i-txt" id="i_txt_${ii}" value="${escHtml(nombre)}" placeholder="Buscar o escribir…" autocomplete="off">
                <input type="hidden" class="i-id" id="i_id_${ii}" value="${refId}">
                <div class="ac-list" id="i_list_${ii}"></div>
            </div>
        </td>
        <td><select class="i-uni" id="i_uni_${ii}">${optsUnidades(uniId)}</select></td>
        <td><input type="number" class="i-cant" value="${cant}" min="0.001" step="0.001" oninput="updateSubtotal('${ii}',${ci})"></td>
        <td><input type="number" class="i-pu" value="${puVal}" min="0" step="0.01" oninput="updateSubtotal('${ii}',${ci})"></td>
        <td><span class="i-sub" style="font-size:.82rem;font-weight:700;color:var(--mid);">$${(cant*puVal).toFixed(2)}</span></td>
        <td><button type="button" class="btn-del" onclick="this.closest('tr').remove();recalcPU(${ci});"><i class="bi bi-x-lg"></i></button></td>
    </tr>`;

    tbody.insertAdjacentHTML('beforeend', row);

    setupAC(
        document.getElementById(`i_txt_${ii}`),
        document.getElementById(`i_id_${ii}`),
        document.getElementById(`i_list_${ii}`),
        cat, false,
        document.getElementById(`row_${ii}`).querySelector('.i-pu'),
        document.getElementById(`i_uni_${ii}`),
        null, ii, ci
    );
}

function updateSubtotal(ii, ci) {
    const row  = document.getElementById(`row_${ii}`);
    const cant = parseFloat(row.querySelector('.i-cant').value) || 0;
    const pu   = parseFloat(row.querySelector('.i-pu').value)   || 0;
    const sub  = cant * pu;
    const span = row.querySelector('.i-sub');
    if (span) span.textContent = '$' + sub.toFixed(2);

    // Actualizar P.U. total del concepto
    let total = 0;
    ['mat','mo','maq'].forEach(prefix => {
        const tbl = document.querySelector(`#tb_${prefix}_${ci} tbody`);
        if (!tbl) return;
        tbl.querySelectorAll('tr').forEach(tr => {
            const c2 = parseFloat(tr.querySelector('.i-cant')?.value) || 0;
            const p2 = parseFloat(tr.querySelector('.i-pu')?.value)   || 0;
            total += c2 * p2;
        });
    });

    const display = document.getElementById(`pu_display_${ci}`);
    const hidden  = document.getElementById(`pu_hidden_${ci}`);
    const label   = document.getElementById(`pu_label_${ci}`);
    if (display) display.textContent = '$' + total.toFixed(2);
    if (hidden)  hidden.value = total.toFixed(4);
    if (label)   label.textContent  = '$' + total.toFixed(2);
    
    updateGlobalTotals();
}

function escHtml(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ─────────── AUTOCOMPLETE ─────────── */
function setupAC(inp, idFld, list, catArray, isConcept, puFld, uniFld, ci, ii, cardCi) {
    inp.addEventListener('input', function() {
        const q        = this.value.toLowerCase().trim();
        const filtered = catArray.filter(c => c.texto.toLowerCase().includes(q)).slice(0, 12);
        list.innerHTML = '';

        filtered.forEach(c => {
            const div = document.createElement('div');
            div.className   = 'ac-item';
            div.textContent = c.texto;
            div.onclick = () => {
                inp.value   = c.texto;
                idFld.value = c.id;
                if (puFld)  puFld.value  = c.pu ?? 0;
                if (uniFld) uniFld.value = c.uni ?? '';
                list.style.display = 'none';

                // Si es un concepto, cargar su composición automáticamente
                if (isConcept && ci != null) {
                    cargarComposicion(ci, c);
                }

                // Si es insumo, actualizar subtotal
                if (ii != null && cardCi != null) {
                    updateSubtotal(ii, cardCi);
                }
            };
            list.appendChild(div);
        });

        const divNuevo = document.createElement('div');
        divNuevo.className   = 'ac-item nuevo';
        divNuevo.innerHTML   = '<i class="bi bi-plus-circle me-1"></i> Registrar como nuevo';
        divNuevo.onclick = () => { idFld.value = ''; list.style.display = 'none'; };
        list.appendChild(divNuevo);

        list.style.display = q.length > 0 ? 'block' : 'none';
    });

    document.addEventListener('click', e => {
        if (e.target !== inp && !list.contains(e.target)) list.style.display = 'none';
    });
}

/* ─────────── CARGAR COMPOSICIÓN DEL CONCEPTO ─────────── */
function cargarComposicion(ci, conceptoData) {
    if (!conceptoData.composicion || conceptoData.composicion.length === 0) return;

    // Limpiar tablas existentes
    ['mat','mo','maq'].forEach(prefix => {
        const tbody = document.querySelector(`#tb_${prefix}_${ci} tbody`);
        if (tbody) tbody.innerHTML = '';
    });

    conceptoData.composicion.forEach(comp => {
        addInsumo(ci, comp.tipo, comp);
    });

    // Recalcular PU después de cargar
    setTimeout(() => updateSubtotalAll(ci), 100);
}

function updateSubtotalAll(ci) {
    let total = 0;
    ['mat','mo','maq'].forEach(prefix => {
        const tbl = document.querySelector(`#tb_${prefix}_${ci} tbody`);
        if (!tbl) return;
        tbl.querySelectorAll('tr').forEach(tr => {
            const cant = parseFloat(tr.querySelector('.i-cant')?.value) || 0;
            const pu   = parseFloat(tr.querySelector('.i-pu')?.value)   || 0;
            const sub  = cant * pu;
            const span = tr.querySelector('.i-sub');
            if (span) span.textContent = '$' + sub.toFixed(2);
            total += sub;
        });
    });
    const display = document.getElementById(`pu_display_${ci}`);
    const hidden  = document.getElementById(`pu_hidden_${ci}`);
    const label   = document.getElementById(`pu_label_${ci}`);
    if (display) display.textContent = '$' + total.toFixed(2);
    if (hidden)  hidden.value = total.toFixed(4);
    if (label)   label.textContent  = '$' + total.toFixed(2);
    
    updateGlobalTotals();
}

function updateGlobalTotals() {
    let subtotalGeneral = 0;
    document.querySelectorAll('.cpt-card').forEach(card => {
        const cant = parseFloat(card.querySelector('.c-cant').value) || 0;
        const pu = parseFloat(card.querySelector('.c-pu').value) || 0;
        subtotalGeneral += (cant * pu);
    });

    const ivaGeneral = subtotalGeneral * 0.16; // Asumiendo IVA de 16% global para previsualizar
    const totalFinal = subtotalGeneral + ivaGeneral;

    document.getElementById('tot_subtotal').textContent = '$' + subtotalGeneral.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('tot_iva').textContent = '$' + ivaGeneral.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('tot_final').textContent = '$' + totalFinal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

/* ─────────── GUARDAR ─────────── */
async function guardarPresupuesto() {
    const payload = { conceptos: [] };
    const areaGlobal = document.getElementById('g_area').value;

    let hayFilasSinDesc = false;

    document.querySelectorAll('.cpt-card').forEach(card => {
        const cId   = card.querySelector('.c-id').value;
        const cDesc = card.querySelector('.c-desc').value.trim();
        const cDescInput = card.querySelector('.c-desc');
        
        if (!cId && !cDesc) {
            cDescInput.style.borderColor = 'var(--red)';
            hayFilasSinDesc = true;
            return; // Lo omitimos o lo marcamos
        } else {
            cDescInput.style.borderColor = 'var(--line)';
        }

        const cAreaTxt = card.querySelector('.c-area-txt').value.trim();
        const cBloqueTxt = card.querySelector('.c-bloque-txt').value.trim();

        const conceptoData = {
            id_concepto:      cId   || null,
            nombre_nuevo:     cId   ? '' : cDesc,
            id_unidad_medida: card.querySelector('.c-uni').value     || null,
            id_bloque:        card.querySelector('.c-bloque').value  || null,
            bloque_nuevo:     card.querySelector('.c-bloque').value ? '' : cBloqueTxt,
            id_nivel:         card.querySelector('.c-nivel').value   || null,
            id_area:          card.querySelector('.c-area').value    || null,
            area_nuevo:       card.querySelector('.c-area').value ? '' : cAreaTxt,
            cantidad:         parseFloat(card.querySelector('.c-cant').value) || 1,
            precio_unitario:  parseFloat(card.querySelector('.c-pu').value)   || 0,
            materiales:  [],
            maquinaria:  [],
            mano_obra:   [],
        };

        const ci = card.dataset.ci;
        [['material','mat'], ['maquinaria','maq'], ['mano_obra','mo']].forEach(([tipo, prefix]) => {
            document.querySelectorAll(`#tb_${prefix}_${ci} tbody tr`).forEach(tr => {
                const iId  = tr.querySelector('.i-id').value;
                const iTxt = tr.querySelector('.i-txt').value.trim();
                if (!iId && !iTxt) return;
                conceptoData[tipo].push({
                    [`id_${tipo}`]:   iId || null,
                    nombre_nuevo:     iId ? '' : iTxt,
                    id_unidad_medida: tr.querySelector('.i-uni').value || null,
                    cantidad:         parseFloat(tr.querySelector('.i-cant').value) || 1,
                    precio_unitario:  parseFloat(tr.querySelector('.i-pu').value)   || 0,
                });
            });
        });

        payload.conceptos.push(conceptoData);
    });

    if (payload.conceptos.length === 0) {
        if (hayFilasSinDesc) {
            showToast('⚠ Hay renglones sin Descripción del Concepto. ¡Escribe una descripción o bórralos!', 'err');
        } else {
            showToast('⚠ Agrega al menos un concepto antes de guardar.', 'err');
        }
        return;
    }

    const btn = document.getElementById('btnGuardar');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite;"></i> Guardando…';

    try {
        const res  = await fetch(storeUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body:   JSON.stringify(payload),
        });
        const json = await res.json();

        if (res.ok && json.success) {
            showToast('✓ Renglones guardados correctamente', 'ok');
            setTimeout(() => { window.location.href = json.redirect; }, 1200);
        } else {
            showToast('Error: ' + (json.message || 'No se pudo guardar'), 'err');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-cloud-arrow-up-fill"></i> Agregar Conceptos';
        }
    } catch (e) {
        console.error(e);
        showToast('Error de conexión con el servidor.', 'err');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-cloud-arrow-up-fill"></i> Agregar Conceptos';
    }
}

function showToast(msg, tipo) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className   = tipo;
    t.style.display = 'flex';
    setTimeout(() => { t.style.display = 'none'; }, 4500);
}

document.addEventListener('DOMContentLoaded', () => { 
    setupAC(document.getElementById('g_bloque_txt'), document.getElementById('g_bloque'), document.getElementById('g_bloque_list'), catBloques, false, null, null, null);
    setupAC(document.getElementById('g_area_txt'), document.getElementById('g_area'), document.getElementById('g_area_list'), catAreas, false, null, null, null);
    
    addConcepto(); 
});
</script>
@endsection
