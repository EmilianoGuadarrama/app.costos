@extends('layout')
@section('title', 'Agregar al Presupuesto — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root {
    --dark:#111111; --mid:#374151; --soft:#6b7280; --line:#e5e7eb;
    --bg:#f3f4f6;   --white:#fff;
    --blue:#374151; --blue-l:#f3f4f6; --blue-b:#e5e7eb;
    --green:#059669; --green-l:#f0fdf4; --green-b:#bbf7d0;
    --amber:#d97706; --amber-l:#fffbeb; --amber-b:#fde68a;
    --red:#b91c1c;
}
body{background:var(--bg);font-family:'Inter','Segoe UI',sans-serif;}

/* ── HEADER ── */
.pu-hdr{
    display:flex;justify-content:space-between;align-items:center;
    background:#111111;color:#fff;padding:16px 26px;
    position:sticky;top:0;z-index:300;
    border-bottom:1px solid #222;
    box-shadow:0 2px 12px rgba(0,0,0,.2);
}
.pu-hdr-left h1 {
    font-family:'Garamond','Baskerville',serif;
    font-size:1.45rem;
    margin:0;
    font-weight:700;
    letter-spacing:.3px;
}
.pu-hdr-left p { margin:3px 0 0; font-size:.78rem; color:#6b7280; letter-spacing:.2px; }
.btn-back{background:rgba(255,255,255,.08);color:#d1d5db;border:1px solid rgba(255,255,255,.15);
    border-radius:7px;padding:5px 13px;font-size:.8rem;text-decoration:none;
    transition:.2s;display:inline-flex;align-items:center;gap:5px;margin-bottom:5px;}
.btn-back:hover{background:rgba(255,255,255,.18);color:#fff;}
.btn-save{
    background:#fff;color:#111;border:1px solid rgba(255,255,255,.3);border-radius:9px;
    padding:10px 24px;font-weight:700;font-size:.82rem;cursor:pointer;
    display:inline-flex;align-items:center;gap:8px;transition:.2s;
    letter-spacing:.3px;text-transform:uppercase;
}
.btn-save:hover{background:#f3f4f6;color:#111;}
.btn-save:disabled{opacity:.6;cursor:not-allowed;}

/* ── GLOBALS BAR ── */
.pu-globals{
    background:#fff;border-bottom:1px solid var(--line);
    padding:13px 26px;display:flex;gap:18px;flex-wrap:wrap;align-items:flex-end;
}
.gl-group{display:flex;flex-direction:column;gap:3px;flex:1;min-width:150px;}
.gl-group label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--soft);}
.gl-group select{border:1.5px solid var(--line);border-radius:8px;padding:8px 10px;font-size:.85rem;color:var(--dark);background:var(--bg);}
.gl-group select:focus{border-color:#374151;outline:none;}

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
.fld input:focus,.fld select:focus{border-color:#374151;outline:none;box-shadow:0 0 0 3px rgba(55,65,81,.06);}
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
.ac-item:hover{background:#f3f4f6;color:#111;}
.ac-item.nuevo{color:#374151;font-weight:700;background:#f9fafb;}

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
.btn-ai.mat{background:#374151;color:#fff;}
.btn-ai.mo {background:var(--green);color:#fff;}
.btn-ai.maq{background:var(--amber);color:#fff;}
.btn-ai:hover{opacity:.85;}
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
.ins-table input:focus,.ins-table select:focus{border-color:#374151;outline:none;}
.ins-table td .ac-wrap { position:relative; }
.ins-table td .uni-ac-list, .ins-table td .ac-list { min-width:200px; font-size:.8rem; }
.btn-del{background:#fef2f2;color:var(--red);border:1px solid #fecaca;
    border-radius:5px;padding:3px 7px;cursor:pointer;font-size:.78rem;transition:.15s;}
.btn-del:hover{background:#b91c1c;color:#fff;}
/* Input de unidad en campos de concepto */
.c-uni-txt { width:100%; box-sizing:border-box; border:1.5px solid var(--line); border-radius:7px; padding:7px 9px; font-size:.85rem; color:var(--dark); background:#fff; transition:.15s; }
.c-uni-txt:focus { border-color:#374151; outline:none; box-shadow:0 0 0 3px rgba(55,65,81,.06); }
.i-uni-txt { width:100%; box-sizing:border-box; border:1.5px solid var(--line); border-radius:6px; padding:5px 7px; font-size:.82rem; background:#fff; color:var(--dark); }
.i-uni-txt:focus { border-color:#374151; outline:none; }

/* ── P.U. resumen ── */
.pu-calc{
    display:flex;align-items:center;gap:8px;padding:8px 14px;
    background:#f3f4f6;border:1px solid #e5e7eb;border-radius:8px;margin-top:8px;
    font-size:.82rem;color:#111;font-weight:700;
}
.pu-calc span{color:var(--soft);font-weight:400;}

/* ── Toast ── */
#toast{
    position:fixed;bottom:22px;right:22px;z-index:9999;
    padding:12px 20px;border-radius:10px;font-weight:600;font-size:.88rem;
    display:none;align-items:center;gap:9px;box-shadow:0 10px 25px rgba(0,0,0,.2);
}
#toast.ok {background:#059669;color:#fff;}
#toast.err{background:#dc2626;color:#fff;}
@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}

/* ── MODAL REGISTRO RÁPIDO ── */
#modalOverlay {
    position:fixed;inset:0;z-index:10000;
    background:rgba(0,0,0,.55);backdrop-filter:blur(4px);
    display:none;align-items:center;justify-content:center;
}
#modalOverlay.active{display:flex;}
#modalBox {
    background:#fff;border-radius:18px;width:100%;max-width:540px;
    padding:28px 32px;box-shadow:0 25px 60px rgba(0,0,0,.3);
    max-height:90vh;overflow-y:auto;
    animation:modalIn .2s ease;
}
@keyframes modalIn{from{transform:scale(.92);opacity:0}to{transform:scale(1);opacity:1}}
.modal-title{font-size:1.1rem;font-weight:800;color:#111;margin:0 0 4px;display:flex;align-items:center;gap:8px;}
.modal-sub{font-size:.82rem;color:#6b7280;margin:0 0 20px;}
.modal-close{float:right;background:none;border:none;font-size:1.3rem;cursor:pointer;color:#9ca3af;line-height:1;}
.modal-close:hover{color:#111;}
.m-field{margin-bottom:14px;}
.m-label{font-size:.78rem;font-weight:700;color:#374151;display:block;margin-bottom:5px;}
.m-label span{color:#dc2626;}
.m-ctrl{width:100%;padding:.55rem .85rem;box-sizing:border-box;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.86rem;background:#fff;color:#111;transition:.2s;}
.m-ctrl:focus{border-color:#374151;outline:none;box-shadow:0 0 0 3px rgba(55,65,81,.08);}
.m-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.m-hint{font-size:.72rem;color:#9ca3af;margin-top:3px;}
.modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:22px;padding-top:16px;border-top:1px solid #f3f4f6;}
.btn-modal-save{background:#111827;color:#fff;border:none;border-radius:9px;padding:.65rem 1.6rem;font-size:.88rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:.2s;}
.btn-modal-save:hover{background:#374151;}
.btn-modal-save:disabled{opacity:.6;cursor:not-allowed;}
.btn-modal-cancel{background:transparent;color:#6b7280;border:1.5px solid #e5e7eb;border-radius:9px;padding:.65rem 1.2rem;font-size:.88rem;font-weight:600;cursor:pointer;transition:.2s;}
.btn-modal-cancel:hover{border-color:#111;color:#111;}
.ac-item.nuevo-full{color:#059669;font-weight:700;background:#f0fdf4;border-top:1px solid #bbf7d0;}
.ac-item.nuevo-full:hover{background:#dcfce7;}
</style>

{{-- HEADER --}}
<div class="pu-hdr">
    <div class="pu-hdr-left">
        <a href="{{ route('obras.presupuesto', $obra->id) }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Volver al Presupuesto
        </a>
        <h1><i class="bi bi-layers me-2" style="color:#9ca3af;"></i>Agregar Renglones al Presupuesto</h1>
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
            <h4 id="tot_final" style="margin:0; font-size:1.5rem; color:#111; font-weight:800;">$0.00</h4>
        </div>
    </div>
</div>

{{-- MODAL REGISTRO RÁPIDO ──────────────────────────────────────────── --}}
<div id="modalOverlay" onclick="if(event.target===this)cerrarModal()">
    <div id="modalBox">
        <p class="modal-title">
            <i id="modalIcon" class="bi bi-plus-circle"></i>
            <span id="modalTitulo">Registrar</span>
            <button class="modal-close ms-auto" onclick="cerrarModal()">&times;</button>
        </p>
        <p class="modal-sub" id="modalSub"></p>
        <div id="modalBody"></div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="cerrarModal()"><i class="bi bi-x-lg me-1"></i>Cancelar</button>
            <button class="btn-modal-save" id="btnModalGuardar" onclick="guardarModal()">
                <i class="bi bi-check-lg me-1"></i>Guardar y usar
            </button>
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
window._csrfToken = csrfToken;
window._apiUrls = {
    unidad:    '{{ route("api.unidades.storeRapida") }}',
    area:      '{{ route("api.areas.storeRapida") }}',
    bloque:    '{{ route("api.bloques.storeRapida") }}',
    material:  '{{ route("api.materiales.storeRapida") }}',
    mano_obra: '{{ route("api.mano_obra.storeRapida") }}',
    maquinaria:'{{ route("api.maquinaria.storeRapida") }}'
};

let conceptIndex = 0;

/* ─────────── HELPERS DE OPCIONES ─────────── */
function optsUnidades(selId = '') {
    return `<option value="">N/A</option>` +
        unidades.map(u => `<option value="${u.id}" ${u.id == selId ? 'selected' : ''}>${u.abreviatura}</option>`).join('');
}

/* ─────────── AUTOCOMPLETE UNIDAD DE MEDIDA ─────────── */
// catUnidades: array local en memoria, se puede agregar al vuelo
let catUnidades = unidades.map(u => ({ id: u.id, texto: u.abreviatura, nombre: u.nombre ?? u.abreviatura }));


// Configurar autocomplete para campo de Unidad de Medida
function setupUniAC(inp, idFld, onSelect) {
    let acList = inp.parentElement.querySelector('.uni-ac-list');
    if (!acList) {
        acList = document.createElement('div');
        acList.className = 'ac-list uni-ac-list';
        inp.parentElement.appendChild(acList);
    }

    inp.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        const filtered = catUnidades.filter(u =>
            u.texto.toLowerCase().includes(q) || u.nombre.toLowerCase().includes(q)
        ).slice(0, 15);

        acList.innerHTML = '';

        filtered.forEach(u => {
            const div = document.createElement('div');
            div.className = 'ac-item';
            div.textContent = u.texto + (u.nombre !== u.texto ? ' — ' + u.nombre : '');
            div.onclick = () => {
                inp.value = u.texto;
                idFld.value = u.id;
                acList.style.display = 'none';
                if (onSelect) onSelect(u);
            };
            acList.appendChild(div);
        });

        // Opción para registrar nueva unidad
        if (q.length >= 1) {
            const divNew = document.createElement('div');
            divNew.className = 'ac-item nuevo-full';
            divNew.innerHTML = `<i class="bi bi-plus-circle-fill me-1"></i>Registrar "<strong>${escHtml(this.value)}</strong>" como nueva unidad de medida`;
            const valCapturado = this.value.trim();
            divNew.onclick = () => {
                acList.style.display = 'none';
                if (typeof abrirModal === 'function') {
                    abrirModal('unidad', valCapturado, (u) => {
                        inp.value = u.texto;
                        idFld.value = u.id;
                        if (onSelect) onSelect(u);
                    });
                }
            };
            acList.appendChild(divNew);
        }

        acList.style.display = (q.length > 0 || filtered.length > 0) ? 'block' : 'none';
    });

    inp.addEventListener('focus', function() {
        // Mostrar todas las unidades al enfocar si el campo está vacío
        if (!this.value.trim()) {
            inp.dispatchEvent(new Event('input'));
        }
    });

    document.addEventListener('click', e => {
        if (!inp.contains(e.target) && !acList.contains(e.target)) acList.style.display = 'none';
    });
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
    sincronizarGlobales();

    conceptIndex++;
    const ci   = conceptIndex;
    const nVal = document.getElementById('g_nivel')?.value || '';
    const bId = document.getElementById('g_bloque')?.value || '';
    const bTxt = document.getElementById('g_bloque_txt')?.value || '';
    const aId = document.getElementById('g_area')?.value || '';
    const aTxt = document.getElementById('g_area_txt')?.value || '';

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
                <div class="ac-wrap" style="position:relative;">
                    <input type="text" class="c-uni-txt" id="c_uni_txt_${ci}" placeholder="m², pza, kg…" autocomplete="off">
                    <input type="hidden" class="c-uni" id="c_uni_id_${ci}">
                </div>
            </div>
            <div class="fld f-sm">
                <label>Nivel / Planta</label>
                <select class="c-nivel">${optsNiveles()}</select>
            </div>
            <div class="fld f-sm">
                <label>Bloque</label>
                <div class="ac-wrap">
                    <input type="text" class="c-bloque-txt" id="b_txt_${ci}" value="${escHtml(bTxt)}" placeholder="Buscar o crear..." autocomplete="off">
                    <input type="hidden" class="c-bloque" id="b_id_${ci}" value="${bId}">
                    <div class="ac-list" id="b_list_${ci}"></div>
                </div>
            </div>
            <div class="fld f-sm">
                <label>Área</label>
                <div class="ac-wrap">
                    <input type="text" class="c-area-txt" id="a_txt_${ci}" value="${escHtml(aTxt)}" placeholder="Buscar o crear..." autocomplete="off">
                    <input type="hidden" class="c-area" id="a_id_${ci}" value="${aId}">
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
    if (nVal) card.querySelector('.c-nivel').value  = nVal;

    setupAC(document.getElementById(`c_txt_${ci}`), document.getElementById(`c_id_${ci}`), document.getElementById(`c_list_${ci}`), catConceptos, true, null, null, ci);
    setupUniAC(document.getElementById(`c_uni_txt_${ci}`), document.getElementById(`c_uni_id_${ci}`));
    setupAC(document.getElementById(`b_txt_${ci}`), document.getElementById(`b_id_${ci}`), document.getElementById(`b_list_${ci}`), catBloques, false, null, null, null, null, null, 'bloque');
    setupAC(document.getElementById(`a_txt_${ci}`), document.getElementById(`a_id_${ci}`), document.getElementById(`a_list_${ci}`), catAreas, false, null, null, null, null, null, 'area');

    // Cuando se selecciona un concepto del catálogo, auto-llenar la unidad
    const cTxtInp = document.getElementById(`c_txt_${ci}`);
    const cIdFld  = document.getElementById(`c_id_${ci}`);
    const cUniTxt = document.getElementById(`c_uni_txt_${ci}`);
    const cUniId  = document.getElementById(`c_uni_id_${ci}`);
    // La función setupAC original llena el uniFld, pero ahora usamos texto+hidden
    // Sobreescribir onclick dentro de setupAC no es viable; en su lugar escuchamos
    // el input oculto c_id para detectar cuando cambia el concepto
    const origSetup = cTxtInp._acSetup;
    cTxtInp.addEventListener('_conceptoSeleccionado', (e) => {
        const c = e.detail;
        if (c.uni) {
            const u = catUnidades.find(u => u.id == c.uni);
            if (u) { cUniTxt.value = u.texto; cUniId.value = u.id; }
        }
    });

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
        <td>
            <div class="ac-wrap" style="position:relative;">
                <input type="text" class="i-uni-txt" id="i_uni_txt_${ii}" placeholder="Unidad…" autocomplete="off" style="width:100%;">
                <input type="hidden" class="i-uni" id="i_uni_hid_${ii}" value="${uniId}">
            </div>
        </td>
        <td><input type="number" class="i-cant" value="${cant}" min="0.001" step="0.001" oninput="updateSubtotal('${ii}',${ci})"></td>
        <td><input type="number" class="i-pu" value="${puVal}" min="0" step="0.01" oninput="updateSubtotal('${ii}',${ci})"></td>
        <td><span class="i-sub" style="font-size:.82rem;font-weight:700;color:var(--mid);">$${(cant*puVal).toFixed(2)}</span></td>
        <td><button type="button" class="btn-del" onclick="this.closest('tr').remove();recalcPU(${ci});"><i class="bi bi-x-lg"></i></button></td>
    </tr>`;

    tbody.insertAdjacentHTML('beforeend', row);

    // Si hay unidad preseleccionada, mostrar el texto
    const iUniTxt = document.getElementById(`i_uni_txt_${ii}`);
    const iUniHid = document.getElementById(`i_uni_hid_${ii}`);
    if (iUniTxt && uniId) {
        const u = catUnidades.find(u => u.id == uniId);
        if (u) iUniTxt.value = u.texto;
    }
    setupUniAC(iUniTxt, iUniHid);

    // Detectar tipo de entidad para el modal
    const tipoInsumo = tipo;
    setupAC(
        document.getElementById(`i_txt_${ii}`),
        document.getElementById(`i_id_${ii}`),
        document.getElementById(`i_list_${ii}`),
        cat, false,
        document.getElementById(`row_${ii}`).querySelector('.i-pu'),
        null,
        null, ii, ci, tipoInsumo
    );

    // Al seleccionar un insumo del catálogo, auto-llenar la unidad del insumo
    const iTxtInp = document.getElementById(`i_txt_${ii}`);
    const iIdFld  = document.getElementById(`i_id_${ii}`);
    iTxtInp.addEventListener('input', function() {
        // La función setupAC llena i-pu; aquí también llenamos la unidad
        const q = this.value.toLowerCase().trim();
        const found = cat.find(c => c.texto.toLowerCase() === q);
        if (found && found.uni) {
            const u = catUnidades.find(u => u.id == found.uni);
            if (u && iUniTxt) { iUniTxt.value = u.texto; iUniHid.value = u.id; }
        }
    });
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

/* ─────────── AUTOCOMPLETE GENÉRICO ─────────── */
function setupAC(inp, idFld, list, catArray, isConcept, puFld, uniFld, ci, ii, cardCi, tipoEntidad) {
    inp.addEventListener('input', function() {
        const q        = String(this.value).toLowerCase().trim();
        const filtered = catArray.filter(c => String(c.texto).toLowerCase().includes(q)).slice(0, 12);
        list.innerHTML = '';

        filtered.forEach(c => {
            const div = document.createElement('div');
            div.className   = 'ac-item';
            div.textContent = c.texto;
            div.onclick = () => {
                inp.value   = c.texto;
                idFld.value = c.id;
                if (puFld)  puFld.value  = c.pu ?? 0;
                // uniFld ya no es select, se maneja con setupUniAC + evento
                list.style.display = 'none';

                // Si es un concepto, cargar su composición automáticamente
                if (isConcept && ci != null) {
                    cargarComposicion(ci, c);
                    // Propagar evento para auto-llenar unidad
                    const evt = new CustomEvent('_conceptoSeleccionado', { detail: c });
                    inp.dispatchEvent(evt);
                    // Llenar directamente si el campo de unidad existe
                    if (c.uni) {
                        const uTxt = document.getElementById(`c_uni_txt_${ci}`);
                        const uId  = document.getElementById(`c_uni_id_${ci}`);
                        const uObj = catUnidades.find(u => u.id == c.uni);
                        if (uTxt && uObj) { uTxt.value = uObj.texto; uId.value = uObj.id; }
                    }
                }

                // Si es insumo, actualizar subtotal y unidad
                if (ii != null && cardCi != null) {
                    updateSubtotal(ii, cardCi);
                    // Auto-llenar unidad del insumo
                    if (c.uni) {
                        const uTxt = document.getElementById(`i_uni_txt_${ii}`);
                        const uHid = document.getElementById(`i_uni_hid_${ii}`);
                        const uObj = catUnidades.find(u => u.id == c.uni);
                        if (uTxt && uObj) { uTxt.value = uObj.texto; uHid.value = uObj.id; }
                    }
                }
            };
            list.appendChild(div);
        });

        // Opción "Registrar como nuevo" solo si no hay coincidencia exacta
                // Opcion "Registrar como nuevo" - abre modal completo si hay tipo de entidad
        if (q.length > 0) {
            const divNuevo = document.createElement('div');
            if (tipoEntidad && typeof abrirModal === 'function') {
                divNuevo.className = 'ac-item nuevo-full';
                const _labelTipo = { unidad:'unidad de medida', area:'area', bloque:'bloque', material:'material', mano_obra:'mano de obra', maquinaria:'maquinaria/equipo' }[tipoEntidad] || tipoEntidad;
                divNuevo.innerHTML = '<i class="bi bi-plus-circle-fill me-1"></i>Registrar "<strong>' + escHtml(q) + '</strong>" como nuevo ' + _labelTipo;
                const _valCapturado = q;
                divNuevo.onclick = () => {
                    list.style.display = 'none';
                    abrirModal(tipoEntidad, _valCapturado, (resultado) => {
                        inp.value   = resultado.texto;
                        idFld.value = resultado.id;
                        if (puFld && resultado.pu != null) puFld.value = resultado.pu;
                        if (ii != null) {
                            const _uTxt = document.getElementById('i_uni_txt_' + ii);
                            const _uHid = document.getElementById('i_uni_hid_' + ii);
                            if (_uTxt && resultado.uniTxt) { _uTxt.value = resultado.uniTxt; _uHid.value = resultado.uni; }
                            if (cardCi != null) updateSubtotal(ii, cardCi);
                        }
                        if (ci != null) {
                            const _cUTxt = document.getElementById('c_uni_txt_' + ci);
                            const _cUId  = document.getElementById('c_uni_id_' + ci);
                            if (_cUTxt && resultado.uniTxt) { _cUTxt.value = resultado.uniTxt; _cUId.value = resultado.uni; }
                        }
                    });
                };
            } else {
                divNuevo.className = 'ac-item nuevo';
                divNuevo.innerHTML = '<i class="bi bi-pencil-square me-1"></i> Usar "' + escHtml(q) + '" como concepto nuevo';
                divNuevo.onclick = () => { idFld.value = ''; list.style.display = 'none'; };
            }
            list.appendChild(divNuevo);
        }

        list.style.display = q.length > 0 ? 'block' : 'none';
    });

    inp.addEventListener('focus', function() {
        if (!this.value.trim()) inp.dispatchEvent(new Event('input'));
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
    const btn = document.getElementById('btnGuardar');

    try {
        sincronizarGlobales();

        const payload = {
            conceptos: []
        };

        let hayFilasSinDesc = false;

        document.querySelectorAll('.cpt-card').forEach(card => {
            const cId      = card.querySelector('.c-id')?.value || null;
            const cTxt     = card.querySelector('.c-desc')?.value.trim() || '';
            const cUni     = card.querySelector('.c-uni')?.value || null;
            const cNivel   = card.querySelector('.c-nivel')?.value || null;
            const cBloque  = card.querySelector('.c-bloque')?.value || null;
            const cBloqueTxt = card.querySelector('.c-bloque-txt')?.value.trim() || '';
            const cArea    = card.querySelector('.c-area')?.value || null;
            const cAreaTxt = card.querySelector('.c-area-txt')?.value.trim() || '';

            if (!cTxt) {
                hayFilasSinDesc = true;
                return;
            }

            const conceptoData = {
                id_concepto: cId,
                descripcion_nueva: cId ? '' : cTxt,
                descripcion: cTxt,
                id_unidad_medida: card.querySelector('.c-uni')?.value || null,
                id_nivel: cNivel,
                id_bloque: cBloque,
                bloque_nuevo: cBloque ? '' : cBloqueTxt,
                id_area: cArea,
                area_nueva: cArea ? '' : cAreaTxt,
                cantidad: parseFloat(card.querySelector('.c-cant')?.value) || 1,
                precio_unitario: parseFloat(card.querySelector('.c-pu')?.value) || 0,
                materiales: [],
                maquinaria: [],
                mano_obra: []
            };

            const ci = card.dataset.ci;

            [
                ['materiales', 'mat', 'id_material'],
                ['maquinaria', 'maq', 'id_maquinaria'],
                ['mano_obra', 'mo', 'id_mano_obra']
            ].forEach(([nombreArray, prefix, idCampo]) => {
                document.querySelectorAll(`#tb_${prefix}_${ci} tbody tr`).forEach(tr => {
                    const iId  = tr.querySelector('.i-id')?.value || null;
                    const iTxt = tr.querySelector('.i-txt')?.value.trim() || '';

                    if (!iId && !iTxt) return;

                    conceptoData[nombreArray].push({
                        [idCampo]: iId,
                        nombre_nuevo: iId ? '' : iTxt,
                        id_unidad_medida: tr.querySelector('.i-uni')?.value || null,
                        cantidad: parseFloat(tr.querySelector('.i-cant')?.value) || 1,
                        precio_unitario: parseFloat(tr.querySelector('.i-pu')?.value) || 0
                    });
                });
            });

            payload.conceptos.push(conceptoData);
        });

        if (payload.conceptos.length === 0) {
            if (hayFilasSinDesc) {
                alert('⚠ Hay conceptos sin descripción. Escribe una descripción o elimina el renglón.');
            } else {
                alert('⚠ Agrega al menos un concepto antes de guardar.');
            }
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite;"></i> Guardando…';

        const res = await fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        });

        const text = await res.text();

        let json = {};
        try {
            json = JSON.parse(text);
        } catch (e) {
            console.error('Respuesta del servidor:', text);
            throw new Error('El servidor no regresó JSON. Revisa la consola o la ruta del controlador.');
        }

        if (!res.ok || !json.success) {
            throw new Error(json.message || 'No se pudo guardar el presupuesto.');
        }

        alert('✓ Renglones guardados correctamente');

        if (json.redirect) {
            window.location.href = json.redirect;
        } else {
            window.location.reload();
        }

    } catch (error) {
        console.error(error);
        alert('Error al guardar: ' + error.message);

        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-cloud-arrow-up-fill"></i> Agregar Conceptos';
        }
    }
}

function showToast(msg, tipo) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className   = tipo;
    t.style.display = 'flex';
    setTimeout(() => { t.style.display = 'none'; }, 4500);
}

/* =====================================================
   CORRECCIÓN: APLICAR BLOQUE / ÁREA / NIVEL POR DEFECTO
   A TODOS LOS CONCEPTOS
===================================================== */

function normalizarTexto(txt) {
    return (txt || '')
        .toString()
        .trim()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
}

function buscarIdPorTexto(catalogo, texto) {
    const t = normalizarTexto(texto);

    if (!t) return '';

    const encontrado = catalogo.find(item => {
        return normalizarTexto(item.texto) === t;
    });

    return encontrado ? encontrado.id : '';
}

function sincronizarGlobales() {
    const bloqueTxt = document.getElementById('g_bloque_txt');
    const bloqueId  = document.getElementById('g_bloque');

    const areaTxt = document.getElementById('g_area_txt');
    const areaId  = document.getElementById('g_area');

    if (bloqueTxt && bloqueId && !bloqueId.value) {
        bloqueId.value = buscarIdPorTexto(catBloques, bloqueTxt.value);
    }

    if (areaTxt && areaId && !areaId.value) {
        areaId.value = buscarIdPorTexto(catAreas, areaTxt.value);
    }
}

function aplicarGlobalesAConceptos() {
    sincronizarGlobales();

    const gNivel     = document.getElementById('g_nivel')?.value || '';
    const gBloqueTxt = document.getElementById('g_bloque_txt')?.value || '';
    const gBloqueId  = document.getElementById('g_bloque')?.value || '';
    const gAreaTxt   = document.getElementById('g_area_txt')?.value || '';
    const gAreaId    = document.getElementById('g_area')?.value || '';

    document.querySelectorAll('.cpt-card').forEach(card => {
        const nivel = card.querySelector('.c-nivel');
        const bloqueTxt = card.querySelector('.c-bloque-txt');
        const bloqueId = card.querySelector('.c-bloque');
        const areaTxt = card.querySelector('.c-area-txt');
        const areaId = card.querySelector('.c-area');

        if (gNivel && nivel) {
            nivel.value = gNivel;
        }

        if (gBloqueTxt && bloqueTxt) {
            bloqueTxt.value = gBloqueTxt;
        }

        if (gBloqueId && bloqueId) {
            bloqueId.value = gBloqueId;
        }

        if (gAreaTxt && areaTxt) {
            areaTxt.value = gAreaTxt;
        }

        if (gAreaId && areaId) {
            areaId.value = gAreaId;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => { 
    setupAC(document.getElementById('g_bloque_txt'), document.getElementById('g_bloque'), document.getElementById('g_bloque_list'), catBloques, false, null, null, null);
    setupAC(document.getElementById('g_area_txt'), document.getElementById('g_area'), document.getElementById('g_area_list'), catAreas, false, null, null, null);
    
    addConcepto(); 

    const gNivel = document.getElementById('g_nivel');
    const gBloqueTxt = document.getElementById('g_bloque_txt');
    const gAreaTxt = document.getElementById('g_area_txt');

    if (gNivel) {
        gNivel.addEventListener('change', aplicarGlobalesAConceptos);
    }

    if (gBloqueTxt) {
        gBloqueTxt.addEventListener('input', () => {
            document.getElementById('g_bloque').value = '';
            setTimeout(aplicarGlobalesAConceptos, 100);
        });

        gBloqueTxt.addEventListener('blur', aplicarGlobalesAConceptos);
    }

    if (gAreaTxt) {
        gAreaTxt.addEventListener('input', () => {
            document.getElementById('g_area').value = '';
            setTimeout(aplicarGlobalesAConceptos, 100);
        });

        gAreaTxt.addEventListener('blur', aplicarGlobalesAConceptos);
    }

    document.addEventListener('click', () => {
        setTimeout(aplicarGlobalesAConceptos, 150);
    });
});

/* =====================================================
   SISTEMA DE MODAL REGISTRO RAPIDO
===================================================== */

let modalState = { tipo: null, callback: null, valorInicial: "" };

function getModalConfig() {
    return {
        unidad: {
            titulo: "Nueva Unidad de Medida",
            icono: "bi-rulers",
            sub: "Registra la unidad de medida que necesitas usar.",
            url: window._apiUrls.unidad,
            campos: function() { return `
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Abreviatura <span>*</span></label>
                        <input class="m-ctrl" id="mf_abreviatura" placeholder="Ej. m2, pza, kg" maxlength="50" required>
                        <p class="m-hint">Simbolo corto que aparecera en los campos.</p>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Nombre completo <span>*</span></label>
                        <input class="m-ctrl" id="mf_nombre" placeholder="Ej. Metro cuadrado, Pieza" maxlength="255" required>
                    </div>
                </div>
                <div class="m-field">
                    <label class="m-label">Descripcion</label>
                    <input class="m-ctrl" id="mf_descripcion" placeholder="Detalles o especificaciones adicionales" maxlength="255">
                </div>`; },
            payload: function() { return {
                abreviatura: document.getElementById("mf_abreviatura").value.trim(),
                nombre:      document.getElementById("mf_nombre").value.trim(),
                descripcion: (document.getElementById("mf_descripcion")||{}).value || ""
            }; },
            rellenar: function(d) { return { id: d.id, texto: d.abreviatura, nombre: d.texto || d.abreviatura }; }
        },
        area: {
            titulo: "Nueva Area",
            icono: "bi-grid-3x3-gap",
            sub: "Define un area o partida para clasificar los conceptos.",
            url: window._apiUrls.area,
            campos: function() { return `
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Abreviatura <span>*</span></label>
                        <input class="m-ctrl" id="mf_abreviatura" placeholder="Ej. INST, EST, ARQ" maxlength="50" required>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Descripcion <span>*</span></label>
                        <input class="m-ctrl" id="mf_descripcion" placeholder="Ej. Instalaciones electricas" maxlength="255" required>
                    </div>
                </div>`; },
            payload: function() { return {
                abreviatura: document.getElementById("mf_abreviatura").value.trim(),
                descripcion: document.getElementById("mf_descripcion").value.trim()
            }; },
            rellenar: function(d) { return { id: d.id, texto: d.abreviatura + " - " + d.descripcion }; }
        },
        bloque: {
            titulo: "Nuevo Bloque",
            icono: "bi-columns-gap",
            sub: "Los bloques agrupan los conceptos dentro del presupuesto.",
            url: window._apiUrls.bloque,
            campos: function() { return `
                <div class="m-field">
                    <label class="m-label">Descripcion del Bloque <span>*</span></label>
                    <input class="m-ctrl" id="mf_descripcion" placeholder="Ej. Preliminares, Estructura, Acabados" maxlength="255" required>
                    <p class="m-hint">Escribe el nombre del bloque tal como aparecera en el presupuesto.</p>
                </div>`; },
            payload: function() { return { descripcion: document.getElementById("mf_descripcion").value.trim() }; },
            rellenar: function(d) { return { id: d.id, texto: d.descripcion }; }
        },
        material: {
            titulo: "Nuevo Material",
            icono: "bi-box-seam",
            sub: "Registra el material con su precio unitario.",
            url: window._apiUrls.material,
            campos: function() { return `
                <div class="m-field">
                    <label class="m-label">Nombre del material <span>*</span></label>
                    <input class="m-ctrl" id="mf_nombre" placeholder="Ej. Varilla de acero 3/8" maxlength="255" required>
                </div>
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Descripcion</label>
                        <input class="m-ctrl" id="mf_descripcion" placeholder="Detalles adicionales">
                    </div>
                    <div class="m-field">
                        <label class="m-label">Marca</label>
                        <input class="m-ctrl" id="mf_marca" placeholder="Ej. AHMSA, Cemex">
                    </div>
                </div>
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Unidad de medida</label>
                        <div style="position:relative;">
                            <input class="m-ctrl" id="mf_uni_txt" placeholder="Buscar unidad..." autocomplete="off">
                            <input type="hidden" id="mf_uni_id">
                            <div class="ac-list" id="mf_uni_list" style="z-index:10001;"></div>
                        </div>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Precio por unidad ($) <span>*</span></label>
                        <input class="m-ctrl" id="mf_precio" type="number" min="0" step="0.01" placeholder="0.00" required>
                    </div>
                </div>`; },
            payload: function() { return {
                nombre:           document.getElementById("mf_nombre").value.trim(),
                descripcion:      (document.getElementById("mf_descripcion")||{}).value || "",
                marca:            (document.getElementById("mf_marca")||{}).value || "",
                id_unidad_medida: (document.getElementById("mf_uni_id")||{}).value || null,
                precio_x_unidad:  document.getElementById("mf_precio").value
            }; },
            rellenar: function(d) { return { id: d.id, texto: d.texto, pu: d.pu, uni: d.uni, uniTxt: d.uniTxt }; }
        },
        mano_obra: {
            titulo: "Nueva Mano de Obra",
            icono: "bi-person-lines-fill",
            sub: "Registra la categoria de mano de obra con su precio por unidad.",
            url: window._apiUrls.mano_obra,
            campos: function() { return `
                <div class="m-field">
                    <label class="m-label">Nombre / Categoria <span>*</span></label>
                    <input class="m-ctrl" id="mf_nombre" placeholder="Ej. Albanil, Peon, Oficial" maxlength="255" required>
                </div>
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Unidad de medida</label>
                        <div style="position:relative;">
                            <input class="m-ctrl" id="mf_uni_txt" placeholder="Buscar unidad..." autocomplete="off">
                            <input type="hidden" id="mf_uni_id">
                            <div class="ac-list" id="mf_uni_list" style="z-index:10001;"></div>
                        </div>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Precio por unidad ($) <span>*</span></label>
                        <input class="m-ctrl" id="mf_precio" type="number" min="0" step="0.01" placeholder="0.00" required>
                    </div>
                </div>`; },
            payload: function() { return {
                nombre:           document.getElementById("mf_nombre").value.trim(),
                id_unidad_medida: (document.getElementById("mf_uni_id")||{}).value || null,
                precio_x_unidad:  document.getElementById("mf_precio").value
            }; },
            rellenar: function(d) { return { id: d.id, texto: d.texto, pu: d.pu, uni: d.uni, uniTxt: d.uniTxt }; }
        },
        maquinaria: {
            titulo: "Nueva Maquinaria / Equipo",
            icono: "bi-truck",
            sub: "Registra la maquinaria o equipo con su costo por unidad.",
            url: window._apiUrls.maquinaria,
            campos: function() { return `
                <div class="m-field">
                    <label class="m-label">Nombre del equipo <span>*</span></label>
                    <input class="m-ctrl" id="mf_nombre" placeholder="Ej. Retroexcavadora, Vibrador" maxlength="255" required>
                </div>
                <div class="m-field">
                    <label class="m-label">Descripcion</label>
                    <input class="m-ctrl" id="mf_descripcion" placeholder="Modelo, capacidad u otros datos">
                </div>
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Unidad de medida</label>
                        <div style="position:relative;">
                            <input class="m-ctrl" id="mf_uni_txt" placeholder="Buscar unidad..." autocomplete="off">
                            <input type="hidden" id="mf_uni_id">
                            <div class="ac-list" id="mf_uni_list" style="z-index:10001;"></div>
                        </div>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Precio por unidad ($) <span>*</span></label>
                        <input class="m-ctrl" id="mf_precio" type="number" min="0" step="0.01" placeholder="0.00" required>
                    </div>
                </div>`; },
            payload: function() { return {
                nombre:           document.getElementById("mf_nombre").value.trim(),
                descripcion:      (document.getElementById("mf_descripcion")||{}).value || "",
                id_unidad_medida: (document.getElementById("mf_uni_id")||{}).value || null,
                precio_x_unidad:  document.getElementById("mf_precio").value
            }; },
            rellenar: function(d) { return { id: d.id, texto: d.texto, pu: d.pu, uni: d.uni, uniTxt: d.uniTxt }; }
        }
    };
}

function abrirModal(tipo, valorInicial, callback) {
    const modalConfig = getModalConfig();
    const cfg = modalConfig[tipo];
    if (!cfg) return;

    modalState = { tipo: tipo, callback: callback, valorInicial: valorInicial };

    document.getElementById("modalIcon").className     = "bi " + cfg.icono;
    document.getElementById("modalTitulo").textContent = cfg.titulo;
    document.getElementById("modalSub").textContent    = cfg.sub;
    document.getElementById("modalBody").innerHTML     = cfg.campos();
    document.getElementById("btnModalGuardar").disabled = false;
    document.getElementById("btnModalGuardar").innerHTML = "<i class=\"bi bi-check-lg me-1\"></i>Guardar y usar";

    const primerCampo = document.getElementById("mf_nombre") ||
                        document.getElementById("mf_abreviatura") ||
                        document.getElementById("mf_descripcion");
    if (primerCampo && valorInicial) primerCampo.value = valorInicial;

    const mfUniTxt = document.getElementById("mf_uni_txt");
    const mfUniId  = document.getElementById("mf_uni_id");
    if (mfUniTxt && mfUniId) {
        setupUniAC(mfUniTxt, mfUniId);
    }

    document.getElementById("modalBox").onkeydown = function(e) {
        if (e.key === "Enter" && e.target.tagName !== "TEXTAREA") {
            e.preventDefault();
            guardarModal();
        }
        if (e.key === "Escape") cerrarModal();
    };

    document.getElementById("modalOverlay").classList.add("active");
    setTimeout(function() { if (primerCampo) primerCampo.focus(); }, 150);
}

function cerrarModal() {
    document.getElementById("modalOverlay").classList.remove("active");
    modalState = { tipo: null, callback: null, valorInicial: "" };
}

async function guardarModal() {
    const modalConfig = getModalConfig();
    const cfg = modalConfig[modalState.tipo];
    if (!cfg) return;

    const btn = document.getElementById("btnModalGuardar");
    btn.disabled = true;
    btn.innerHTML = "<i class=\"bi bi-arrow-repeat\" style=\"animation:spin 1s linear infinite;\"></i> Guardando...";

    try {
        const payload = cfg.payload();
        const required = document.querySelectorAll("#modalBody .m-ctrl[required]");
        let valid = true;
        let focusEl = null;
        required.forEach(function(el) {
            if (!el.value.trim()) {
                el.style.borderColor = "#dc2626";
                if (!focusEl) focusEl = el;
                valid = false;
            } else {
                el.style.borderColor = "";
            }
        });
        if (!valid) {
            if (focusEl) focusEl.focus();
            btn.disabled = false;
            btn.innerHTML = "<i class=\"bi bi-check-lg me-1\"></i>Guardar y usar";
            return;
        }

        const resp = await fetch(cfg.url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window._csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify(payload)
        });

        const data = await resp.json();
        if (!resp.ok) {
            const msg = data.errors ? Object.values(data.errors).flat().join("\n") : (data.message || "Error al guardar");
            throw new Error(msg);
        }

        const resultado = cfg.rellenar(data);
        const tipo = modalState.tipo;

        if (tipo === "unidad" && typeof catUnidades !== "undefined") {
            if (!catUnidades.find(function(u) { return u.id == resultado.id; }))
                catUnidades.push({ id: resultado.id, texto: resultado.texto, nombre: resultado.nombre || resultado.texto });
        } else if (tipo === "area" && typeof catAreas !== "undefined") {
            if (!catAreas.find(function(a) { return a.id == resultado.id; }))
                catAreas.push({ id: resultado.id, texto: resultado.texto });
        } else if (tipo === "bloque" && typeof catBloques !== "undefined") {
            if (!catBloques.find(function(b) { return b.id == resultado.id; }))
                catBloques.push({ id: resultado.id, texto: resultado.texto });
        } else if (tipo === "material" && typeof catMateriales !== "undefined") {
            if (!catMateriales.find(function(m) { return m.id == resultado.id; }))
                catMateriales.push({ id: resultado.id, texto: resultado.texto, pu: resultado.pu, uni: resultado.uni, uniTxt: resultado.uniTxt });
        } else if (tipo === "mano_obra" && typeof catManoObra !== "undefined") {
            if (!catManoObra.find(function(m) { return m.id == resultado.id; }))
                catManoObra.push({ id: resultado.id, texto: resultado.texto, pu: resultado.pu, uni: resultado.uni, uniTxt: resultado.uniTxt });
        } else if (tipo === "maquinaria" && typeof catMaquinaria !== "undefined") {
            if (!catMaquinaria.find(function(m) { return m.id == resultado.id; }))
                catMaquinaria.push({ id: resultado.id, texto: resultado.texto, pu: resultado.pu, uni: resultado.uni, uniTxt: resultado.uniTxt });
        }

        showToast("Registrado: " + resultado.texto, "ok");
        const cb = modalState.callback;
        cerrarModal();
        if (cb) cb(resultado);

    } catch(e) {
        showToast("Error: " + e.message, "err");
        btn.disabled = false;
        btn.innerHTML = "<i class=\"bi bi-check-lg me-1\"></i>Guardar y usar";
    }
}
</script>
@endsection
