@extends('layout')
@section('title','Editar Concepto')
@section('content')
@php
$catMateriales = $materiales->map(fn($m) => ['id'=>$m->id,'texto'=>$m->nombre,'um'=>$m->unidadMedida?->abreviatura??'']);
$catMaquinaria = $maquinaria->map(fn($q) => ['id'=>$q->id,'texto'=>$q->nombre,'um'=>$q->unidadMedida?->abreviatura??'']);
$catManoObra   = $manoObra->map(fn($o)  => ['id'=>$o->id,'texto'=>$o->categoria,'um'=>$o->unidadMedida?->abreviatura??'']);
$compActual    = $concepto->composicion;
@endphp

<style>
.cc-wrap{max-width:860px;margin:0 auto;}
.cc-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:24px;}
.cc-title{font-size:1.7rem;font-weight:800;color:#111;margin:0;}
.cc-sub{color:#6b7280;font-size:.9rem;margin:4px 0 0;}
.btn-back-sm{color:#6b7280;text-decoration:none;font-size:.85rem;display:inline-flex;align-items:center;gap:4px;}
.btn-back-sm:hover{color:#111;}
.alert-err{background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;color:#b91c1c;font-size:.85rem;margin-bottom:18px;}
.card-sec{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:22px 26px;margin-bottom:18px;box-shadow:0 2px 6px rgba(0,0,0,.04);}
.sec-title{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#9ca3af;margin-bottom:18px;padding-bottom:8px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:8px;}
.sec-title i{color:#2563eb;}
.fg-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.fg-1{display:grid;grid-template-columns:1fr;gap:14px;}
.fl{font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:5px;}
.fl span{color:#dc2626;margin-left:2px;}
.fc{width:100%;padding:.52rem .85rem;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.86rem;background:#fff;color:#111;transition:border-color .2s;}
.fc:focus{border-color:#2563eb;outline:none;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.ac-wrap{position:relative;}
.ac-input{padding-right:2rem;}
.ac-list{position:absolute;top:100%;left:0;right:0;z-index:200;background:#fff;border:1.5px solid #d1d5db;border-top:none;border-radius:0 0 8px 8px;max-height:200px;overflow-y:auto;display:none;}
.ac-item{padding:7px 12px;font-size:.83rem;cursor:pointer;border-bottom:1px solid #f3f4f6;}
.ac-item:hover{background:#f0f9ff;}
.ac-item.crear{color:#2563eb;font-weight:700;}
.comp-table{width:100%;border-collapse:collapse;font-size:.82rem;margin-top:10px;}
.comp-table th{background:#f9fafb;color:#6b7280;font-size:.67rem;text-transform:uppercase;letter-spacing:.5px;padding:7px 8px;border-bottom:1px solid #e5e7eb;font-weight:700;text-align:left;}
.comp-table td{padding:5px 6px;border-bottom:1px solid #f3f4f6;vertical-align:top;}
.inp-s{padding:.32rem .55rem;font-size:.8rem;border:1.5px solid #e5e7eb;border-radius:7px;width:100%;background:#fff;}
.inp-s:focus{border-color:#2563eb;outline:none;}
.badge-tipo-mat{background:#dbeafe;color:#1d4ed8;padding:2px 7px;border-radius:8px;font-size:.65rem;font-weight:700;}
.badge-tipo-maq{background:#fef3c7;color:#92400e;padding:2px 7px;border-radius:8px;font-size:.65rem;font-weight:700;}
.badge-tipo-mo{background:#d1fae5;color:#065f46;padding:2px 7px;border-radius:8px;font-size:.65rem;font-weight:700;}
.btn-comp{padding:6px 14px;border-radius:8px;font-size:.75rem;font-weight:700;border:1.5px dashed #d1d5db;background:#fff;color:#6b7280;cursor:pointer;margin-right:6px;margin-bottom:6px;}
.btn-comp:hover{border-color:#2563eb;color:#2563eb;}
.btn-rm-c{background:none;border:none;color:#d1d5db;cursor:pointer;padding:3px;}
.btn-rm-c:hover{color:#dc2626;}
.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:20px;}
.btn-save{background:#111827;color:#fff;border:none;border-radius:10px;padding:.75rem 1.8rem;font-size:.9rem;font-weight:700;cursor:pointer;}
.btn-save:hover{background:#374151;}
.btn-cancel{background:transparent;color:#6b7280;border:1.5px solid #e5e7eb;border-radius:10px;padding:.75rem 1.4rem;font-size:.9rem;font-weight:600;text-decoration:none;}
.btn-cancel:hover{border-color:#111;color:#111;}
.um-panel{background:#fffbeb;border:1.5px dashed #f59e0b;border-radius:8px;padding:10px;margin-top:6px;display:none;}
.um-row{display:grid;grid-template-columns:1fr 1fr auto;gap:6px;align-items:end;}
.btn-um-save{padding:.4rem .8rem;border-radius:7px;background:#f59e0b;color:#fff;border:none;font-size:.78rem;font-weight:700;cursor:pointer;white-space:nowrap;}
</style>

<div class="cc-wrap">
    <a href="{{ route('conceptos.index') }}" class="btn-back-sm"><i class="bi bi-arrow-left"></i> Volver a conceptos</a>

    @if($errors->any())
    <div class="alert-err mt-2"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="cc-header mt-3">
        <div>
            <h1 class="cc-title"><i class="bi bi-pencil-square me-2"></i>Editar Concepto</h1>
            <p class="cc-sub">ID: CON-{{ $concepto->id }} — Modifica los datos y composición del concepto.</p>
        </div>
    </div>

    <form action="{{ route('conceptos.update', $concepto->id) }}" method="POST" id="formConcepto">
        @csrf
        @method('PUT')

        <div class="card-sec">
            <div class="sec-title"><i class="bi bi-info-circle"></i>Datos Generales</div>

            <div class="fg-1" style="margin-bottom:14px;">
                <div>
                    <label class="fl">Descripción del concepto <span>*</span></label>
                    <textarea name="descripcion" class="fc" rows="2" required maxlength="255">{{ old('descripcion', $concepto->descripcion) }}</textarea>
                </div>
            </div>

            <div class="fg-2" style="margin-bottom:14px;">
                <div>
                    <label class="fl">Área <span>*</span></label>
                    <select name="id_area" class="fc" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($areas as $a)
                            <option value="{{ $a->id }}" {{ old('id_area', $concepto->id_area)==$a->id?'selected':'' }}>{{ $a->abreviatura }} — {{ $a->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="fl">Precio Unitario ($) <span>*</span></label>
                    <input type="number" step="0.01" min="0" name="p_u" class="fc" value="{{ old('p_u', $concepto->p_u) }}" required>
                </div>
            </div>

            <div class="fg-2">
                <div>
                    <label class="fl">Unidad de Medida</label>
                    <div class="ac-wrap">
                        <input type="text" id="um_texto" class="fc ac-input" placeholder="Escribir o buscar UM…" autocomplete="off"
                               value="{{ $concepto->unidadMedida?->abreviatura ?? '' }}">
                        <div class="ac-list" id="um_list"></div>
                    </div>
                    <input type="hidden" name="id_unidad_medida" id="um_id" value="{{ old('id_unidad_medida', $concepto->id_unidad_medida) }}">
                    <div class="um-panel" id="umNuevoPanel">
                        <label style="font-size:.72rem;font-weight:700;color:#92400e;display:block;margin-bottom:4px;">Registrar nueva unidad de medida:</label>
                        <div class="um-row">
                            <div><label style="font-size:.7rem;">Abreviatura</label><input type="text" id="umNuevoAbr" class="inp-s" placeholder="Ej. m²"></div>
                            <div><label style="font-size:.7rem;">Nombre completo</label><input type="text" id="umNuevoNom" class="inp-s" placeholder="Ej. Metro cuadrado"></div>
                            <button type="button" class="btn-um-save" onclick="guardarUMNueva()">Guardar</button>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="fl">Duración estimada (días)</label>
                    <input type="number" step="1" min="0" name="duracion_en_dias" class="fc" value="{{ old('duracion_en_dias', $concepto->duracion_en_dias) }}" placeholder="0">
                </div>
            </div>
        </div>

        <div class="card-sec">
            <div class="sec-title"><i class="bi bi-diagram-3"></i>Composición del Concepto</div>

            <div style="margin-bottom:12px;">
                <button type="button" class="btn-comp" onclick="addComp('material')"><i class="bi bi-box-seam me-1"></i>+ Material</button>
                <button type="button" class="btn-comp" onclick="addComp('maquinaria')"><i class="bi bi-truck me-1"></i>+ Maquinaria</button>
                <button type="button" class="btn-comp" onclick="addComp('mano_obra')"><i class="bi bi-person-arms-up me-1"></i>+ Mano de Obra</button>
            </div>

            <table class="comp-table" id="compTable">
                <thead>
                    <tr>
                        <th style="width:12%">Tipo</th>
                        <th>Descripción / Insumo</th>
                        <th style="width:12%">Cantidad</th>
                        <th style="width:10%"></th>
                    </tr>
                </thead>
                <tbody id="compBody">
                    <tr id="compEmpty"><td colspan="4" style="text-align:center;color:#9ca3af;padding:16px;font-size:.82rem;">Usa los botones para agregar insumos</td></tr>
                </tbody>
            </table>
        </div>

        <div class="form-actions">
            <a href="{{ route('conceptos.index') }}" class="btn-cancel">Cancelar</a>
            <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i>Guardar Cambios</button>
        </div>
    </form>
</div>

<script>
const catMat = @json($catMateriales);
const catMaq = @json($catMaquinaria);
const catMO  = @json($catManoObra);
const catUM  = @json($unidades->map(fn($u)=>['id'=>$u->id,'texto'=>$u->abreviatura.' — '.$u->nombre,'abr'=>$u->abreviatura]));
const compActual = @json($compActual->map(fn($c)=>['tipo'=>$c->tipo,'referencia_id'=>$c->referencia_id,'descripcion'=>$c->descripcion_referencia,'cantidad'=>$c->cantidad,'unidad'=>$c->unidad??'']));

// ── Autocompletado UM ─────────────────────────────────────
const umInput = document.getElementById('um_texto');
const umList  = document.getElementById('um_list');
const umId    = document.getElementById('um_id');

umInput.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    umId.value = '';
    if (!q) { umList.style.display='none'; return; }
    const filtered = catUM.filter(u => u.texto.toLowerCase().includes(q) || u.abr.toLowerCase().includes(q));
    umList.innerHTML = '';
    filtered.slice(0,12).forEach(u => {
        const div = document.createElement('div');
        div.className = 'ac-item';
        div.textContent = u.texto;
        div.onclick = () => { umInput.value = u.abr; umId.value = u.id; umList.style.display='none'; document.getElementById('umNuevoPanel').style.display='none'; };
        umList.appendChild(div);
    });
    const crear = document.createElement('div');
    crear.className = 'ac-item crear';
    crear.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Registrar nueva UM: "' + q + '"';
    crear.onclick = () => { document.getElementById('umNuevoPanel').style.display='block'; document.getElementById('umNuevoAbr').value = q.toUpperCase(); umList.style.display='none'; };
    umList.appendChild(crear);
    umList.style.display = 'block';
});
document.addEventListener('click', e => { if (!e.target.closest('.ac-wrap')) umList.style.display='none'; });

async function guardarUMNueva() {
    const abr = document.getElementById('umNuevoAbr').value.trim();
    const nom = document.getElementById('umNuevoNom').value.trim();
    if (!abr) { alert('Escribe la abreviatura'); return; }
    const resp = await fetch('{{ route("api.unidades.storeRapida") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({abreviatura: abr, nombre: nom})
    });
    const data = await resp.json();
    umInput.value = data.abreviatura;
    umId.value = data.id;
    document.getElementById('umNuevoPanel').style.display='none';
    catUM.push({id: data.id, texto: data.texto, abr: data.abreviatura});
}

// ── Composición ─────────────────────────────────────────
let compIdx = 0;

function addComp(tipo, refId='', desc='', cantidad=1) {
    document.getElementById('compEmpty').style.display='none';
    const cat = tipo==='material' ? catMat : tipo==='maquinaria' ? catMaq : catMO;
    const badge = tipo==='material' ? '<span class="badge-tipo-mat">Material</span>'
                : tipo==='maquinaria' ? '<span class="badge-tipo-maq">Maquinaria</span>'
                : '<span class="badge-tipo-mo">Mano de Obra</span>';
    const ri = ++compIdx;
    const tr = document.createElement('tr');
    tr.id = 'comp_'+ri;
    tr.innerHTML = `
        <td>${badge}
            <input type="hidden" name="composicion[${ri}][tipo]" value="${tipo}">
            <input type="hidden" name="composicion[${ri}][referencia_id]" id="comp_ref_${ri}" value="${refId}">
        </td>
        <td>
            <div class="ac-wrap">
                <input type="text" class="inp-s" id="comp_txt_${ri}" placeholder="Buscar..." value="${desc}" autocomplete="off">
                <div class="ac-list" id="comp_list_${ri}"></div>
            </div>
        </td>
        <td><input type="number" step="0.01" min="0" name="composicion[${ri}][cantidad]" class="inp-s" value="${cantidad}" style="width:80px;"></td>
        <td><button type="button" class="btn-rm-c" onclick="document.getElementById('comp_'+${ri}).remove(); checkEmpty()"><i class="bi bi-trash3"></i></button></td>
    `;
    document.getElementById('compBody').appendChild(tr);
    setupCompAC(ri, cat);
}

function setupCompAC(ri, cat) {
    const inp  = document.getElementById('comp_txt_'+ri);
    const list = document.getElementById('comp_list_'+ri);
    const ref  = document.getElementById('comp_ref_'+ri);
    inp.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        const filtered = cat.filter(c => c.texto.toLowerCase().includes(q));
        list.innerHTML = '';
        filtered.slice(0,12).forEach(c => {
            const div = document.createElement('div');
            div.className = 'ac-item';
            div.textContent = c.texto + (c.um ? ' ['+c.um+']' : '');
            div.onclick = () => { inp.value = c.texto; ref.value = c.id; list.style.display='none'; };
            list.appendChild(div);
        });
        list.style.display = filtered.length ? 'block' : 'none';
    });
    document.addEventListener('click', e => { if (!e.target.closest('#comp_list_'+ri)) list.style.display='none'; });
}

function checkEmpty() {
    const rows = document.querySelectorAll('#compBody tr:not(#compEmpty)');
    document.getElementById('compEmpty').style.display = rows.length ? 'none' : '';
}

// Cargar composición existente al iniciar
window.addEventListener('DOMContentLoaded', () => {
    compActual.forEach(c => addComp(c.tipo, c.referencia_id, c.descripcion, c.cantidad));
});
</script>
@endsection
