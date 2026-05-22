@extends('layout')
@section('title', 'Agregar Renglones — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))
@section('content')

@php
$catConceptos  = $conceptos->map(fn($c) => [
    'id'         => $c->id,
    'texto'      => $c->descripcion,
    'pu'         => (float)$c->p_u,
    'um'         => $c->unidadMedida?->abreviatura ?? '',
    'um_id'      => $c->id_unidad_medida,
    'composicion'=> $c->composicion->map(fn($comp) => [
        'tipo'        => $comp->tipo,
        'descripcion' => $comp->descripcion_referencia,
        'cantidad'    => $comp->cantidad,
        'unidad'      => $comp->unidad,
    ]),
]);
$catMateriales = $materiales->map(fn($m) => ['id'=>$m->id,'texto'=>$m->nombre,'pu'=>(float)$m->precio_x_unidad,'um'=>$m->unidadMedida?->abreviatura??'','um_id'=>$m->id_unidad_medida]);
$catMaquinaria = $maquinaria->map(fn($q) => ['id'=>$q->id,'texto'=>$q->nombre,'pu'=>(float)$q->precio_x_unidad,'um'=>$q->unidadMedida?->abreviatura??'','um_id'=>$q->id_unidad_medida]);
$catUnidades   = \App\Models\UnidadMedida::orderBy('abreviatura')->get()->map(fn($u)=>['id'=>$u->id,'texto'=>$u->nombre.' ('.$u->abreviatura.')','abr'=>$u->abreviatura]);
$catAreas      = $areas->map(fn($a)=>['id'=>$a->id,'texto'=>$a->abreviatura.' — '.$a->descripcion]);
@endphp

<style>
.pu-wrap{font-family:"Arial",sans-serif;}
.pu-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;}
.pu-title{font-size:1.7rem;font-weight:800;color:#111;margin:0;}
.pu-sub{color:#6b7280;font-size:.9rem;margin:4px 0 0;}
.btn-back-sm{color:#6b7280;text-decoration:none;font-size:.85rem;display:inline-flex;align-items:center;gap:4px;}
.btn-back-sm:hover{color:#111;}
.alert-err{background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;color:#b91c1c;font-size:.85rem;margin-bottom:14px;}
.cfg-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:18px 22px;margin-bottom:16px;box-shadow:0 2px 6px rgba(0,0,0,.04);}
.cfg-title{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#9ca3af;margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid #f3f4f6;}
.cfg-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;}
.pf-lbl{font-size:.78rem;font-weight:700;color:#374151;display:block;margin-bottom:4px;}
.pf-ctrl{width:100%;padding:.45rem .75rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.83rem;background:#fff;}
.pf-ctrl:focus{border-color:#2563eb;outline:none;}
.tipo-tabs{display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;}
.btn-tipo{padding:7px 16px;border-radius:20px;border:2px solid #e5e7eb;background:#fff;color:#6b7280;font-size:.78rem;font-weight:700;cursor:pointer;transition:all .2s;}
.btn-tipo.concepto.act{border-color:#2563eb;background:#eff6ff;color:#2563eb;}
.btn-tipo.material.act{border-color:#16a34a;background:#f0fdf4;color:#16a34a;}
.btn-tipo.maquinaria.act{border-color:#d97706;background:#fffbeb;color:#d97706;}
.ren-wrap{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:18px 22px;box-shadow:0 2px 6px rgba(0,0,0,.04);}
.ren-tabla{width:100%;border-collapse:collapse;font-size:.8rem;}
.ren-tabla th{background:#f9fafb;color:#6b7280;font-size:.67rem;text-transform:uppercase;letter-spacing:.5px;padding:7px 8px;border-bottom:1px solid #e5e7eb;font-weight:700;text-align:left;white-space:nowrap;}
.ren-tabla td{padding:5px 6px;border-bottom:1px solid #f3f4f6;vertical-align:top;}
.inp-s{padding:.32rem .55rem;font-size:.8rem;border:1.5px solid #e5e7eb;border-radius:7px;width:100%;background:#fff;}
.inp-s:focus{border-color:#2563eb;outline:none;}
.badge-tipo{display:inline-block;padding:2px 8px;border-radius:10px;font-size:.65rem;font-weight:700;text-transform:uppercase;}
.badge-tipo.concepto{background:#eff6ff;color:#2563eb;}
.badge-tipo.material{background:#f0fdf4;color:#16a34a;}
.badge-tipo.maquinaria{background:#fffbeb;color:#d97706;}
.importe-cel{font-weight:700;text-align:right;color:#111;font-size:.82rem;white-space:nowrap;vertical-align:middle!important;}
.btn-rm{background:none;border:none;color:#d1d5db;cursor:pointer;font-size:.95rem;padding:3px;}
.btn-rm:hover{color:#dc2626;}
/* Autocompletado */
.ac-wrap{position:relative;}
.ac-list{position:absolute;top:100%;left:0;right:0;z-index:300;background:#fff;border:1.5px solid #d1d5db;border-top:none;border-radius:0 0 8px 8px;max-height:180px;overflow-y:auto;display:none;min-width:220px;}
.ac-item{padding:7px 10px;font-size:.8rem;cursor:pointer;border-bottom:1px solid #f3f4f6;}
.ac-item:hover{background:#f0f9ff;}
.ac-item.nuevo{color:#2563eb;font-weight:700;border-top:1px solid #e5e7eb;}
/* Panel nuevo registro */
.nuevo-panel{background:#fafafa;border:1.5px dashed #d1d5db;border-radius:8px;padding:10px;margin-top:5px;display:none;}
.nuevo-panel label{font-size:.72rem;font-weight:700;color:#6b7280;display:block;margin-bottom:2px;}
.nuevo-panel .ng{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:6px;}
/* UM inline */
.um-ac-wrap{position:relative;display:inline-block;width:100%;}
.um-mini-panel{background:#fffbeb;border:1px dashed #f59e0b;border-radius:7px;padding:8px;margin-top:4px;display:none;}
.um-mini-panel label{font-size:.7rem;color:#78350f;display:block;margin-bottom:2px;}
/* Desglose composición */
.tr-desglose td{background:#f0f9ff;padding:8px 12px;font-size:.75rem;color:#374151;}
.comp-badge{display:inline-block;padding:1px 6px;border-radius:6px;font-size:.62rem;font-weight:700;margin-right:4px;}
.cb-mat{background:#dbeafe;color:#1d4ed8;}
.cb-maq{background:#fef3c7;color:#92400e;}
.cb-mo{background:#d1fae5;color:#065f46;}
.btn-desglose{background:none;border:none;color:#9ca3af;cursor:pointer;font-size:.7rem;padding:2px 5px;border-radius:4px;border:1px solid #e5e7eb;}
.btn-desglose:hover{color:#2563eb;border-color:#2563eb;}
/* Totales */
.totales-caja{background:#111827;color:#fff;border-radius:12px;padding:16px 22px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;margin-top:18px;}
.tc-item{text-align:center;}
.tc-lbl{font-size:.63rem;text-transform:uppercase;letter-spacing:1px;color:#9ca3af;}
.tc-val{font-size:1.15rem;font-weight:900;margin-top:4px;}
.tc-val.accent{color:#fbbf24;}
.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:20px;}
.btn-guardar{background:#111827;color:#fff;border:none;border-radius:10px;padding:.75rem 1.8rem;font-size:.9rem;font-weight:700;cursor:pointer;}
.btn-guardar:hover{background:#374151;}
.btn-cancelar{background:transparent;color:#6b7280;border:1.5px solid #e5e7eb;border-radius:10px;padding:.75rem 1.4rem;font-size:.9rem;font-weight:600;text-decoration:none;}
.btn-cancelar:hover{border-color:#111;color:#111;}
</style>

<div class="pu-wrap">
    <a href="{{ route('obras.presupuesto', $obra->id) }}" class="btn-back-sm"><i class="bi bi-arrow-left"></i> Volver al presupuesto</a>

    @if($errors->any())
    <div class="alert-err"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="pu-header mt-3">
        <div>
            <h1 class="pu-title"><i class="bi bi-layers me-2"></i>Agregar Renglones</h1>
            <p class="pu-sub">Obra: <strong>{{ $obra->datosDeObra?->nombre ?? "Obra #$obra->id" }}</strong> — Mezcla conceptos, materiales y maquinaria en un solo lote.</p>
        </div>
    </div>

    <form action="{{ route('obras.presupuesto.unificado.store', $obra->id) }}" method="POST" id="formUnificado">
        @csrf

        {{-- Config global --}}
        <div class="cfg-card">
            <div class="cfg-title"><i class="bi bi-sliders me-1"></i>Configuración del Lote</div>
            <div class="cfg-grid">
                <div>
                    <label class="pf-lbl">Bloque *</label>
                    <select id="bloque_global" class="pf-ctrl" onchange="aplicarGlobal('bloque')">
                        <option value="">— Seleccione —</option>
                        @foreach($bloques as $b)<option value="{{ $b->id }}">{{ $b->descripcion }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="pf-lbl">Área</label>
                    <select id="area_global" class="pf-ctrl" onchange="aplicarGlobal('area')">
                        <option value="">— Seleccione —</option>
                        @foreach($areas as $a)<option value="{{ $a->id }}">{{ $a->abreviatura }} — {{ $a->descripcion }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="pf-lbl">Nivel (opcional)</label>
                    <select id="nivel_global" class="pf-ctrl" onchange="aplicarGlobal('nivel')">
                        <option value="">— Sin nivel —</option>
                        @foreach($obra->niveles as $n)<option value="{{ $n->id }}">{{ $n->descripcion }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="pf-lbl">% IVA</label>
                    <input type="number" id="iva_global" class="pf-ctrl" value="16" min="0" max="100" onchange="aplicarGlobal('iva')">
                </div>
            </div>
        </div>

        {{-- Botones tipo --}}
        <div class="tipo-tabs">
            <button type="button" class="btn-tipo concepto act" onclick="crearFila('concepto')"><i class="bi bi-card-list me-1"></i>+ Concepto</button>
            <button type="button" class="btn-tipo material"    onclick="crearFila('material')"><i class="bi bi-box-seam me-1"></i>+ Material</button>
            <button type="button" class="btn-tipo maquinaria"  onclick="crearFila('maquinaria')"><i class="bi bi-truck me-1"></i>+ Maquinaria</button>
        </div>

        {{-- Tabla --}}
        <div class="ren-wrap">
            <table class="ren-tabla">
                <thead><tr>
                    <th style="width:4%">Tipo</th>
                    <th style="width:30%">Artículo</th>
                    <th style="width:9%">Bloque</th>
                    <th style="width:9%">Área</th>
                    <th style="width:8%">Nivel</th>
                    <th style="width:9%">P.U. ($)</th>
                    <th style="width:7%">Cant.</th>
                    <th style="width:6%">IVA%</th>
                    <th style="width:10%">Total</th>
                    <th style="width:8%"></th>
                </tr></thead>
                <tbody id="tbodyFilas"></tbody>
            </table>
            <div id="emptyMsg" style="text-align:center;padding:20px;color:#9ca3af;font-size:.85rem;">
                <i class="bi bi-plus-circle me-1"></i>Usa los botones para agregar renglones
            </div>
        </div>

        <div class="totales-caja">
            <div class="tc-item"><div class="tc-lbl">Subtotal sin IVA</div><div class="tc-val">$<span id="spanSub">0.00</span></div></div>
            <div class="tc-item"><div class="tc-lbl">IVA</div><div class="tc-val">$<span id="spanIva">0.00</span></div></div>
            <div class="tc-item"><div class="tc-lbl">Total Final</div><div class="tc-val accent">$<span id="spanTot">0.00</span></div></div>
        </div>

        <div class="form-actions">
            <a href="{{ route('obras.presupuesto', $obra->id) }}" class="btn-cancelar">Cancelar</a>
            <button type="submit" class="btn-guardar"><i class="bi bi-check-lg me-1"></i>Guardar Renglones</button>
        </div>
    </form>
</div>

<script>
const catConceptos  = @json($catConceptos);
const catMateriales = @json($catMateriales);
const catMaquinaria = @json($catMaquinaria);
const catUnidades   = @json($catUnidades);
const catAreas2     = @json($catAreas);
const bloques       = @json($bloques->map(fn($b)=>['id'=>$b->id,'texto'=>$b->descripcion]));
const niveles       = @json($obra->niveles->map(fn($n)=>['id'=>$n->id,'texto'=>$n->descripcion]));

let idx = 0;

function opts(arr){ return arr.map(r=>`<option value="${r.id}">${r.texto}</option>`).join(''); }

// ── Función principal crear fila ──────────────────────────────────────────────
function crearFila(tipo) {
    idx++;
    const ri = idx;
    document.getElementById('emptyMsg').style.display='none';

    const bVal = document.getElementById('bloque_global').value;
    const aVal = document.getElementById('area_global').value;
    const nVal = document.getElementById('nivel_global').value;
    const iVal = document.getElementById('iva_global').value;

    let cat = tipo==='concepto' ? catConceptos : tipo==='material' ? catMateriales : catMaquinaria;
    let selName = tipo==='concepto' ? `filas[${ri}][id_concepto]`
                : tipo==='material' ? `filas[${ri}][id_material]`
                : `filas[${ri}][id_maquinaria]`;
    let badge = tipo==='concepto' ? 'CON' : tipo==='material' ? 'MAT' : 'MAQ';

    // Panel nuevo registro según tipo
    let extraFields = '';
    if(tipo==='concepto'){
        extraFields = `
          <div class="ng">
            <div><label>Área del concepto</label><select name="filas[${ri}][nuevo_id_area]" class="inp-s"><option value="">—</option>${opts(catAreas2)}</select></div>
            <div><label>P.U. base ($)</label><input type="number" step="0.01" min="0" name="filas[${ri}][nuevo_pu]" class="inp-s" value="0"></div>
          </div>
          <div>
            <label>Unidad de medida</label>
            <div class="um-ac-wrap">
              <input type="text" id="umNTxt_${ri}" class="inp-s" placeholder="Buscar UM..." autocomplete="off">
              <input type="hidden" name="filas[${ri}][nuevo_id_um]" id="umNId_${ri}">
              <div class="ac-list" id="umNList_${ri}"></div>
              <div class="um-mini-panel" id="umNPanel_${ri}">
                <label>Nueva UM: <input type="text" id="umNAbr_${ri}" class="inp-s" placeholder="Abreviatura" style="width:80px;display:inline-block;"></label>
                <button type="button" onclick="crearUMRapida(${ri})" style="margin-left:4px;padding:2px 8px;border-radius:6px;background:#f59e0b;color:#fff;border:none;cursor:pointer;font-size:.75rem;">Crear</button>
              </div>
            </div>
          </div>`;
    } else {
        extraFields = `
          <div class="ng">
            <div><label>Unidad de medida</label>
              <div class="um-ac-wrap">
                <input type="text" id="umNTxt_${ri}" class="inp-s" placeholder="Buscar UM..." autocomplete="off">
                <input type="hidden" name="filas[${ri}][nuevo_id_um]" id="umNId_${ri}">
                <div class="ac-list" id="umNList_${ri}"></div>
              </div>
            </div>
            <div><label>Precio base ($)</label><input type="number" step="0.01" min="0" name="filas[${ri}][nuevo_precio]" class="inp-s" value="0"></div>
          </div>
          <div><label>Descripción (opcional)</label><input type="text" name="filas[${ri}][nuevo_desc]" class="inp-s" placeholder="Descripción…"></div>`;
    }

    const tr = document.createElement('tr');
    tr.dataset.ri   = ri;
    tr.dataset.tipo = tipo;
    tr.innerHTML = `
      <td style="vertical-align:middle;"><span class="badge-tipo ${tipo}">${badge}</span><input type="hidden" name="filas[${ri}][tipo]" value="${tipo}"></td>
      <td>
        <div class="ac-wrap">
          <input type="text" class="inp-s" id="artTxt_${ri}" placeholder="Escribir para buscar..." autocomplete="off">
          <input type="hidden" name="${selName}" id="artId_${ri}" value="">
          <div class="ac-list" id="artList_${ri}"></div>
        </div>
        <div class="nuevo-panel" id="nuevoPan_${ri}">
          <label style="font-size:.75rem;font-weight:800;color:#374151;margin-bottom:6px;display:block;">📋 Registrar nuevo ${tipo}</label>
          <div><label>Nombre / Descripción *</label><input type="text" name="filas[${ri}][nombre_nuevo]" class="inp-s" placeholder="${tipo==='concepto'?'Descripción del concepto':'Nombre del '+tipo}"></div>
          ${extraFields}
        </div>
        <div id="desgloseBtn_${ri}" style="display:none;margin-top:4px;">
          <button type="button" class="btn-desglose" onclick="toggleDesglose(${ri})"><i class="bi bi-diagram-3 me-1"></i>Ver composición</button>
        </div>
      </td>
      <td><select name="filas[${ri}][id_bloque]" class="inp-s sel-bloque"><option value="">—</option>${opts(bloques)}</select></td>
      <td><select name="filas[${ri}][id_area]" class="inp-s sel-area"><option value="">—</option>${opts(catAreas2)}</select></td>
      <td><select name="filas[${ri}][id_nivel]" class="inp-s sel-nivel"><option value="">—</option>${opts(niveles)}</select></td>
      <td><input type="number" step="0.01" min="0" name="filas[${ri}][precio_unitario]" class="inp-s inp-pu" value="0" oninput="recalcFila(${ri})"></td>
      <td><input type="number" step="0.01" min="0.01" name="filas[${ri}][cantidad]" class="inp-s inp-cant" value="1" oninput="recalcFila(${ri})"></td>
      <td><input type="number" step="1" min="0" max="100" name="filas[${ri}][porcentaje_iva]" class="inp-s inp-iva" value="${iVal}" style="width:52px;" oninput="recalcFila(${ri})"></td>
      <td class="importe-cel"><span class="lbl-tot">$0.00</span><input type="hidden" name="filas[${ri}][subtotal]" class="hid-sub" value="0"></td>
      <td style="vertical-align:middle;"><button type="button" class="btn-rm" onclick="quitarFila(this)"><i class="bi bi-trash3"></i></button></td>
    `;
    document.getElementById('tbodyFilas').appendChild(tr);
    if(bVal) tr.querySelector('.sel-bloque').value = bVal;
    if(aVal) tr.querySelector('.sel-area').value   = aVal;
    if(nVal) tr.querySelector('.sel-nivel').value  = nVal;

    // Configurar autocompletado del artículo
    setupArtAC(ri, cat, tipo);
    // Configurar autocompletado de UM nueva (si aplica)
    if(document.getElementById('umNTxt_'+ri)) setupUMAC(ri);
}

// ── Autocompletado artículo ───────────────────────────────────────────────────
function setupArtAC(ri, cat, tipo) {
    const inp  = document.getElementById('artTxt_'+ri);
    const list = document.getElementById('artList_'+ri);
    const idFld= document.getElementById('artId_'+ri);
    const pan  = document.getElementById('nuevoPan_'+ri);
    const dBtn = document.getElementById('desgloseBtn_'+ri);

    inp.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        const filtered = cat.filter(c => c.texto.toLowerCase().includes(q));
        list.innerHTML = '';

        filtered.slice(0,15).forEach(c => {
            const div = document.createElement('div');
            div.className = 'ac-item';
            div.textContent = c.texto + (c.um ? ' ['+c.um+']' : '');
            div.onclick = () => {
                inp.value = c.texto;
                idFld.value = c.id;
                list.style.display='none';
                pan.style.display='none';
                // Rellenar P.U. desde catálogo
                const tr = inp.closest('tr');
                tr.querySelector('.inp-pu').value = c.pu.toFixed(2);
                recalcFila(ri);
                // Mostrar botón de desglose si tiene composición
                if(tipo==='concepto' && c.composicion && c.composicion.length > 0) {
                    dBtn.style.display='block';
                    dBtn.dataset.comp = JSON.stringify(c.composicion);
                }
            };
            list.appendChild(div);
        });

        // Opción registrar nuevo
        const nuevo = document.createElement('div');
        nuevo.className = 'ac-item nuevo';
        nuevo.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Registrar nuevo: "' + this.value + '"';
        nuevo.onclick = () => {
            idFld.value = '';
            pan.style.display='block';
            list.style.display='none';
            pan.querySelector('input[type=text]').value = inp.value;
        };
        list.appendChild(nuevo);
        list.style.display = 'block';
    });

    document.addEventListener('click', e => {
        if(!e.target.closest('#artTxt_'+ri) && !e.target.closest('#artList_'+ri)) list.style.display='none';
    });
}

// ── Autocompletado UM en nuevos artículos ──────────────────────────────────
function setupUMAC(ri) {
    const inp  = document.getElementById('umNTxt_'+ri);
    const list = document.getElementById('umNList_'+ri);
    const idFld= document.getElementById('umNId_'+ri);
    if (!inp || !list) return;

    inp.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        const filtered = catUnidades.filter(u => u.texto.toLowerCase().includes(q) || u.abr.toLowerCase().includes(q));
        list.innerHTML = '';
        filtered.slice(0,12).forEach(u => {
            const div = document.createElement('div');
            div.className = 'ac-item';
            div.textContent = u.texto;
            div.onclick = () => { inp.value = u.abr; idFld.value = u.id; list.style.display='none'; };
            list.appendChild(div);
        });
        const panel = document.getElementById('umNPanel_'+ri);
        const crear = document.createElement('div');
        crear.className = 'ac-item nuevo';
        crear.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Nueva UM: "'+this.value+'"';
        crear.onclick = () => { if(panel) { panel.style.display='block'; document.getElementById('umNAbr_'+ri).value = inp.value.toUpperCase(); } list.style.display='none'; };
        list.appendChild(crear);
        list.style.display = 'block';
    });
    document.addEventListener('click', e => { if(!e.target.closest('#umNTxt_'+ri)) list.style.display='none'; });
}

async function crearUMRapida(ri) {
    const abr = document.getElementById('umNAbr_'+ri)?.value?.trim();
    if (!abr) return;
    const resp = await fetch('{{ route("api.unidades.storeRapida") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({abreviatura: abr})
    });
    const data = await resp.json();
    document.getElementById('umNTxt_'+ri).value = data.abreviatura;
    document.getElementById('umNId_'+ri).value  = data.id;
    const panel = document.getElementById('umNPanel_'+ri);
    if (panel) panel.style.display='none';
    catUnidades.push({id: data.id, texto: data.texto, abr: data.abreviatura});
}

// ── Desglose de composición ───────────────────────────────────────────────────
function toggleDesglose(ri) {
    const existente = document.getElementById('desglose_'+ri);
    if (existente) { existente.remove(); return; }

    const trMain = document.querySelector('tr[data-ri="'+ri+'"]');
    const btn = document.getElementById('desgloseBtn_'+ri);
    const comp = JSON.parse(btn.dataset.comp || '[]');
    if (!comp.length) return;

    const trDes = document.createElement('tr');
    trDes.id = 'desglose_'+ri;
    trDes.className = 'tr-desglose';

    const iconos = {material:'cb-mat',maquinaria:'cb-maq',mano_obra:'cb-mo'};
    const nombres = {material:'Material',maquinaria:'Maquinaria',mano_obra:'Mano de Obra'};

    let html = '<td colspan="10" class="tr-desglose"><strong style="font-size:.72rem;color:#6b7280;">Composición del concepto:</strong><br>';
    comp.forEach(c => {
        html += `<span class="comp-badge ${iconos[c.tipo]}">${nombres[c.tipo]}</span> ${c.descripcion}${c.cantidad!=1?' × '+c.cantidad:''}${c.unidad?' '+c.unidad:''}&nbsp;&nbsp;`;
    });
    html += '</td>';
    trDes.innerHTML = html;
    trMain.insertAdjacentElement('afterend', trDes);
}

// ── Controles de tabla ────────────────────────────────────────────────────────
function quitarFila(btn){
    const tr = btn.closest('tr');
    const ri = tr.dataset.ri;
    const des = document.getElementById('desglose_'+ri);
    if(des) des.remove();
    tr.remove();
    recalcTodo();
    if(!document.querySelector('#tbodyFilas tr:not([class=tr-desglose])'))
        document.getElementById('emptyMsg').style.display='block';
}

function aplicarGlobal(campo){
    const val = document.getElementById(campo+'_global').value;
    if(campo==='bloque') document.querySelectorAll('.sel-bloque').forEach(s=>{ if(val) s.value=val; });
    if(campo==='area')   document.querySelectorAll('.sel-area').forEach(s=>{ if(val) s.value=val; });
    if(campo==='nivel')  document.querySelectorAll('.sel-nivel').forEach(s=>s.value=val);
    if(campo==='iva')    { document.querySelectorAll('.inp-iva').forEach(s=>s.value=val); recalcTodo(); }
}

function recalcFila(ri){
    const tr  = document.querySelector(`tr[data-ri="${ri}"]`);
    if(!tr) return;
    const pu   = parseFloat(tr.querySelector('.inp-pu').value||0);
    const cant = parseFloat(tr.querySelector('.inp-cant').value||0);
    const iva  = parseFloat(tr.querySelector('.inp-iva').value||16);
    const sub  = pu*cant;
    const tot  = sub*(1+iva/100);
    tr.querySelector('.lbl-tot').textContent='$'+tot.toFixed(2);
    tr.querySelector('.hid-sub').value=sub.toFixed(4);
    recalcTodo();
}

function recalcTodo(){
    let totalSub=0, totalFin=0;
    document.querySelectorAll('.hid-sub').forEach(h=>totalSub+=parseFloat(h.value||0));
    document.querySelectorAll('.lbl-tot').forEach(l=>totalFin+=parseFloat(l.textContent.replace('$','')||0));
    document.getElementById('spanSub').textContent=totalSub.toFixed(2);
    document.getElementById('spanIva').textContent=(totalFin-totalSub).toFixed(2);
    document.getElementById('spanTot').textContent=totalFin.toFixed(2);
}
</script>
@endsection
