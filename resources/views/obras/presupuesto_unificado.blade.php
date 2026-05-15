@extends('layout')
@section('title', 'Agregar Renglones — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))
@section('content')

@php
$catConceptos  = $conceptos->map(fn($c) => ['id'=>$c->id,'texto'=>$c->descripcion,'pu'=>(float)$c->p_u,'um'=>$c->unidadMedida?->abreviatura??'']);
$catMateriales = $materiales->map(fn($m) => ['id'=>$m->id,'texto'=>$m->nombre,'pu'=>(float)$m->precio_x_unidad,'um'=>$m->unidadMedida?->abreviatura??'']);
$catMaquinaria = $maquinaria->map(fn($q) => ['id'=>$q->id,'texto'=>$q->nombre,'pu'=>(float)$q->precio_x_unidad,'um'=>$q->unidadMedida?->abreviatura??'']);
$catUnidades   = \App\Models\UnidadMedida::orderBy('abreviatura')->get()->map(fn($u)=>['id'=>$u->id,'texto'=>$u->nombre.' ('.$u->abreviatura.')']);
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
/* Panel nuevo registro */
.nuevo-panel{background:#fafafa;border:1.5px dashed #d1d5db;border-radius:8px;padding:10px;margin-top:5px;display:none;}
.nuevo-panel label{font-size:.72rem;font-weight:700;color:#6b7280;display:block;margin-bottom:2px;}
.nuevo-panel .ng{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:6px;}
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

    <div class="pu-header">
        <div>
            <h1 class="pu-title"><i class="bi bi-layers me-2"></i>Agregar Renglones</h1>
            <p class="pu-sub">Obra: <strong>{{ $obra->datosDeObra?->nombre ?? "Obra #$obra->id" }}</strong> — Mezcla conceptos, materiales y maquinaria en un solo lote.</p>
        </div>
    </div>

    <form action="{{ route('obras.presupuesto.unificado.store', $obra->id) }}" method="POST" id="formUnificado">
        @csrf

        {{-- Config global --}}
        <div class="cfg-card">
            <div class="cfg-title"><i class="bi bi-sliders me-1"></i> Configuración del Lote</div>
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
            <button type="button" class="btn-tipo concepto act" onclick="crearFila('concepto')"><i class="bi bi-card-list me-1"></i> + Concepto</button>
            <button type="button" class="btn-tipo material"    onclick="crearFila('material')"><i class="bi bi-box-seam me-1"></i> + Material</button>
            <button type="button" class="btn-tipo maquinaria"  onclick="crearFila('maquinaria')"><i class="bi bi-truck me-1"></i> + Maquinaria</button>
        </div>

        {{-- Tabla --}}
        <div class="ren-wrap">
            <table class="ren-tabla">
                <thead><tr>
                    <th style="width:4%">Tipo</th>
                    <th style="width:32%">Artículo</th>
                    <th style="width:9%">Bloque</th>
                    <th style="width:9%">Área</th>
                    <th style="width:8%">Nivel</th>
                    <th style="width:9%">P.U. ($)</th>
                    <th style="width:7%">Cant.</th>
                    <th style="width:6%">IVA%</th>
                    <th style="width:10%">Total</th>
                    <th style="width:6%"></th>
                </tr></thead>
                <tbody id="tbodyFilas"></tbody>
            </table>
            <div id="emptyMsg" style="text-align:center;padding:20px;color:#9ca3af;font-size:.85rem;">
                <i class="bi bi-plus-circle me-1"></i> Usa los botones para agregar renglones
            </div>
        </div>

        <div class="totales-caja">
            <div class="tc-item"><div class="tc-lbl">Subtotal sin IVA</div><div class="tc-val">$<span id="spanSub">0.00</span></div></div>
            <div class="tc-item"><div class="tc-lbl">IVA</div><div class="tc-val">$<span id="spanIva">0.00</span></div></div>
            <div class="tc-item"><div class="tc-lbl">Total Final</div><div class="tc-val accent">$<span id="spanTot">0.00</span></div></div>
        </div>

        <div class="form-actions">
            <a href="{{ route('obras.presupuesto', $obra->id) }}" class="btn-cancelar">Cancelar</a>
            <button type="submit" class="btn-guardar"><i class="bi bi-check-lg me-1"></i> Guardar Renglones</button>
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

function crearFila(tipo) {
    idx++;
    const ri = idx;
    document.getElementById('emptyMsg').style.display='none';

    const bVal = document.getElementById('bloque_global').value;
    const aVal = document.getElementById('area_global').value;
    const nVal = document.getElementById('nivel_global').value;
    const iVal = document.getElementById('iva_global').value;

    let cat = tipo==='concepto' ? catConceptos : tipo==='material' ? catMateriales : catMaquinaria;
    let selName = tipo==='concepto' ? `filas[${ri}][id_concepto]` : tipo==='material' ? `filas[${ri}][id_material]` : `filas[${ri}][id_maquinaria]`;
    let badge   = tipo==='concepto' ? 'CON' : tipo==='material' ? 'MAT' : 'MAQ';

    // Panel de nuevo registro (campos adicionales según tipo)
    let extraFields = '';
    if(tipo==='concepto'){
        extraFields = `
          <div class="ng">
            <div><label>Área del concepto</label><select name="filas[${ri}][nuevo_id_area]" class="inp-s"><option value="">—</option>${opts(catAreas2)}</select></div>
            <div><label>P.U. base ($)</label><input type="number" step="0.01" min="0" name="filas[${ri}][nuevo_pu]" class="inp-s" value="0"></div>
          </div>
          <div class="ng">
            <div><label>Unidad de medida</label><select name="filas[${ri}][nuevo_id_um]" class="inp-s"><option value="">—</option>${opts(catUnidades)}</select></div>
          </div>`;
    } else {
        extraFields = `
          <div class="ng">
            <div><label>Unidad de medida</label><select name="filas[${ri}][nuevo_id_um]" class="inp-s"><option value="">—</option>${opts(catUnidades)}</select></div>
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
        <select name="${selName}" class="inp-s sel-art" onchange="alCambiarArt(this,${ri})">
          <option value="">— Seleccionar —</option>
          ${cat.map(c=>`<option value="${c.id}" data-pu="${c.pu}" data-um="${c.um}">${c.texto}</option>`).join('')}
          <option value="_nuevo">✏ Registrar nuevo...</option>
        </select>
        <div class="nuevo-panel" id="nuevoPan_${ri}">
          <label style="font-size:.75rem;font-weight:800;color:#374151;margin-bottom:6px;display:block;">📋 Registrar nuevo ${tipo}</label>
          <div><label>Nombre / Descripción *</label><input type="text" name="filas[${ri}][nombre_nuevo]" class="inp-s" placeholder="${tipo==='concepto'?'Descripción del concepto':'Nombre del '+tipo}"></div>
          ${extraFields}
        </div>
      </td>
      <td>
        <select name="filas[${ri}][id_bloque]" class="inp-s sel-bloque"><option value="">—</option>${opts(bloques)}</select>
      </td>
      <td>
        <select name="filas[${ri}][id_area]" class="inp-s sel-area"><option value="">—</option>${opts(catAreas2)}</select>
      </td>
      <td>
        <select name="filas[${ri}][id_nivel]" class="inp-s sel-nivel"><option value="">—</option>${opts(niveles)}</select>
      </td>
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
}

function alCambiarArt(sel, ri) {
    const val = sel.value;
    const tr  = sel.closest('tr');
    const pan = document.getElementById('nuevoPan_'+ri);
    if(val==='_nuevo'){
        sel.value='';
        pan.style.display='block';
        return;
    }
    pan.style.display='none';
    const opt = sel.options[sel.selectedIndex];
    tr.querySelector('.inp-pu').value = parseFloat(opt.dataset.pu||0).toFixed(2);
    recalcFila(ri);
}

function quitarFila(btn){
    btn.closest('tr').remove();
    recalcTodo();
    if(!document.querySelector('#tbodyFilas tr'))
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
