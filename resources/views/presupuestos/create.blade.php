@extends('layout')
@section('title', 'Agregar Conceptos — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))
@section('content')
<style>
.pf-wrap { font-family:"Arial",sans-serif; }
.pf-title { font-size:1.6rem; font-weight:800; color:#111; margin:0 0 4px; }
.pf-sub   { color:#6b7280; font-size:.9rem; margin:0 0 20px; }
.btn-back-sm { color:#6b7280; text-decoration:none; font-size:.85rem; display:inline-flex; align-items:center; gap:4px; margin-bottom:16px; }
.btn-back-sm:hover { color:#111; }

.pf-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:24px; margin-bottom:18px; box-shadow:0 2px 8px rgba(0,0,0,.04); }
.pf-card-title { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:2px; color:#9ca3af; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid #f3f4f6; }
.pf-lbl { font-size:.8rem; font-weight:700; color:#374151; display:block; margin-bottom:5px; }
.pf-ctrl { width:100%; padding:.5rem .8rem; border:1.5px solid #e5e7eb; border-radius:8px; font-size:.85rem; }
.pf-ctrl:focus { border-color:#2563eb; outline:none; }
.pf-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px; }
.pf-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; margin-bottom:14px; }

/* Tabla de renglones */
.ren-tabla { width:100%; border-collapse:collapse; font-size:.8rem; }
.ren-tabla th { background:#f9fafb; color:#6b7280; font-size:.68rem; text-transform:uppercase; letter-spacing:.5px; padding:7px 10px; border-bottom:1px solid #e5e7eb; font-weight:700; text-align:left; }
.ren-tabla td { padding:5px 7px; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
.ren-tabla tr:last-child td { border-bottom:none; }
.inp-s { padding:.35rem .6rem; font-size:.82rem; border:1.5px solid #e5e7eb; border-radius:7px; width:100%; background:#fff; }
.inp-s:focus { border-color:#2563eb; outline:none; }
.importe-cel { font-weight:700; text-align:right; font-variant-numeric:tabular-nums; color:#111; font-size:.82rem; }
.btn-rm { background:none; border:none; color:#d1d5db; cursor:pointer; font-size:.95rem; }
.btn-rm:hover { color:#dc2626; }
.btn-add-row { background:none; border:1px dashed #d1d5db; color:#6b7280; padding:5px 12px; border-radius:7px; font-size:.78rem; cursor:pointer; margin:8px 0; }
.btn-add-row:hover { border-color:#2563eb; color:#2563eb; }

/* Totales */
.totales-caja { background:#111827; color:#fff; border-radius:12px; padding:18px 24px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-top:18px; }
.tc-item { text-align:center; }
.tc-lbl  { font-size:.65rem; text-transform:uppercase; letter-spacing:1px; color:#9ca3af; }
.tc-val  { font-size:1.2rem; font-weight:900; margin-top:4px; font-variant-numeric:tabular-nums; }
.tc-val.accent { color:#fbbf24; }

.form-actions { display:flex; gap:12px; justify-content:flex-end; margin-top:20px; }
.btn-guardar { background:#111827; color:#fff; border:none; border-radius:10px; padding:.75rem 1.8rem; font-size:.9rem; font-weight:700; cursor:pointer; }
.btn-guardar:hover { background:#374151; }
.btn-cancelar { background:transparent; color:#6b7280; border:1.5px solid #e5e7eb; border-radius:10px; padding:.75rem 1.4rem; font-size:.9rem; font-weight:600; text-decoration:none; }
.btn-cancelar:hover { border-color:#111; color:#111; }
.alert-err { background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:12px 16px; color:#b91c1c; font-size:.85rem; margin-bottom:14px; }
</style>

<div class="pf-wrap">
    <a href="{{ route('obras.presupuesto.show', $obra->id) }}" class="btn-back-sm">
        <i class="bi bi-arrow-left"></i> Volver al presupuesto
    </a>

    @if($errors->any())
    <div class="alert-err">
        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <h1 class="pf-title">Agregar Conceptos</h1>
    <p class="pf-sub">Obra: <strong>{{ $obra->datosDeObra?->nombre ?? "Obra #$obra->id" }}</strong></p>

    <form action="{{ route('obras.presupuesto.store', $obra->id) }}" method="POST" id="formConceptos">
        @csrf

        <!-- Config del lote -->
        <div class="pf-card">
            <div class="pf-card-title"><i class="bi bi-sliders me-1"></i> Configuración del Bloque</div>
            <div class="pf-grid-2">
                <div>
                    <label class="pf-lbl" for="bloque_global">Bloque *</label>
                    <select id="bloque_global" class="pf-ctrl" onchange="aplicarBloque()">
                        <option value="">— Seleccione bloque —</option>
                        @foreach($bloques as $b)
                            <option value="{{ $b->id }}">{{ $b->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="pf-lbl" for="area_global">Área *</label>
                    <select id="area_global" class="pf-ctrl" onchange="aplicarArea()">
                        <option value="">— Seleccione área —</option>
                        @foreach($areas as $a)
                            <option value="{{ $a->id }}">{{ $a->abreviatura }} — {{ $a->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="pf-grid-2">
                <div>
                    <label class="pf-lbl" for="nivel_global">Nivel (opcional)</label>
                    <select id="nivel_global" class="pf-ctrl" onchange="aplicarNivel()">
                        <option value="">— Sin nivel específico —</option>
                        @foreach($obra->niveles as $n)
                            <option value="{{ $n->id }}">{{ $n->descripcion }}{{ $n->m2 ? ' · '.$n->m2.' m²' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="pf-lbl" for="iva_global">% IVA global</label>
                    <input type="number" id="iva_global" class="pf-ctrl" value="16" min="0" max="100" step="1"
                           onchange="aplicarIva()">
                </div>
            </div>
        </div>

        <!-- Renglones -->
        <div class="pf-card">
            <div class="pf-card-title"><i class="bi bi-list-columns me-1"></i> Renglones de Conceptos</div>

            <table class="ren-tabla">
                <thead>
                    <tr>
                        <th style="width:38%">Concepto</th>
                        <th style="width:8%">Bloque</th>
                        <th style="width:8%">Área</th>
                        <th style="width:7%">Nivel</th>
                        <th style="width:9%">P.U. ($)</th>
                        <th style="width:8%">Cantidad</th>
                        <th style="width:8%">IVA %</th>
                        <th style="width:11%">Total</th>
                        <th style="width:3%"></th>
                    </tr>
                </thead>
                <tbody id="tbodyFilas"></tbody>
            </table>

            <button type="button" class="btn-add-row" id="btnAgregar">
                <i class="bi bi-plus"></i> Agregar renglón
            </button>
        </div>

        <!-- Totales -->
        <div class="totales-caja">
            <div class="tc-item">
                <div class="tc-lbl">Subtotal sin IVA</div>
                <div class="tc-val">$<span id="spanSub">0.00</span></div>
            </div>
            <div class="tc-item">
                <div class="tc-lbl">IVA</div>
                <div class="tc-val">$<span id="spanIva">0.00</span></div>
            </div>
            <div class="tc-item">
                <div class="tc-lbl">Total Final</div>
                <div class="tc-val accent">$<span id="spanTot">0.00</span></div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('obras.presupuesto.show', $obra->id) }}" class="btn-cancelar">Cancelar</a>
            <button type="submit" class="btn-guardar" id="btn-guardar-conceptos">
                <i class="bi bi-check-lg me-1"></i> Guardar Renglones
            </button>
        </div>
    </form>
</div>

<script>
// Catálogo de conceptos desde PHP → JS
@php
    $catConceptosMapped = $conceptos->map(function($c) {
        return [
            'id'      => $c->id,
            'texto'   => $c->descripcion,
            'pu'      => (float) $c->p_u,
            'area_id' => $c->id_area,
        ];
    });
@endphp
const catConceptos = @json($catConceptosMapped);

let filaIdx = 0;

function opcionesConceptos() {
    return catConceptos.map(c =>
        `<option value="${c.id}" data-pu="${c.pu}" data-area="${c.area_id}">${c.texto}</option>`
    ).join('');
}

// Valores globales del formulario
function getBloqueId()  { return document.getElementById('bloque_global').value; }
function getAreaId()    { return document.getElementById('area_global').value; }
function getNivelId()   { return document.getElementById('nivel_global').value; }
function getIvaGlobal() { return parseFloat(document.getElementById('iva_global').value) || 16; }

function crearFila() {
    const ri   = filaIdx++;
    const tr   = document.createElement('tr');
    tr.dataset.ri = ri;
    tr.innerHTML = `
        <td>
            <div style="display:flex;gap:4px;align-items:center;">
                <select name="filas[${ri}][id_concepto]" class="inp-s sel-concepto" onchange="alCambiarConcepto(this,${ri})" style="flex:1;">
                    <option value="">-- Nuevo --</option>
                    ${opcionesConceptos()}
                </select>
            </div>
            <input type="text" name="filas[${ri}][descripcion_nueva]" class="inp-s inp-nuevo-concepto" placeholder="O escribe nuevo concepto aquí…" style="margin-top:4px;display:none;width:100%">
            <label style="font-size:.7rem;color:#6b7280;cursor:pointer;user-select:none;">
                <input type="checkbox" class="chk-nuevo" onchange="toggleNuevoConcepto(this,${ri})" style="margin-right:3px;">Nuevo concepto
            </label>
        </td>
        <td>
            <select name="filas[${ri}][id_bloque]" class="inp-s sel-bloque" style="min-width:90px;" required>
                <option value="">—</option>
                @foreach($bloques as $b)<option value="{{ $b->id }}">{{ $b->descripcion }}</option>@endforeach
            </select>
        </td>
        <td>
            <select name="filas[${ri}][id_area]" class="inp-s sel-area" style="min-width:90px;" required>
                <option value="">—</option>
                @foreach($areas as $a)<option value="{{ $a->id }}">{{ $a->abreviatura }}</option>@endforeach
            </select>
        </td>
        <td>
            <select name="filas[${ri}][id_nivel]" class="inp-s sel-nivel">
                <option value="">—</option>
                @foreach($obra->niveles as $n)<option value="{{ $n->id }}">{{ $n->descripcion }}</option>@endforeach
            </select>
        </td>
        <td><input type="number" step="0.01" min="0" name="filas[${ri}][precio_unitario]" class="inp-s inp-pu" value="0" oninput="recalcFila(${ri})" required></td>
        <td><input type="number" step="0.01" min="0" name="filas[${ri}][cantidad]" class="inp-s inp-cant" value="1" oninput="recalcFila(${ri})" required></td>
        <td><input type="number" step="1" min="0" max="100" name="filas[${ri}][porcentaje_iva]" class="inp-s inp-iva" value="${getIvaGlobal()}" style="width:55px;" oninput="recalcFila(${ri})" required></td>
        <td class="importe-cel"><span class="lbl-tot">$0.00</span>
            <input type="hidden" name="filas[${ri}][subtotal]" class="hid-sub" value="0">
        </td>
        <td><button type="button" class="btn-rm" onclick="this.closest('tr').remove();recalcTodo()"><i class="bi bi-trash3"></i></button></td>
    `;
    document.getElementById('tbodyFilas').appendChild(tr);
    // Aplicar valores globales actuales
    aplicarGlobalesAFila(tr);
}

function aplicarGlobalesAFila(tr) {
    const bId = getBloqueId(), aId = getAreaId(), nId = getNivelId();
    if (bId) tr.querySelector('.sel-bloque').value = bId;
    if (aId) tr.querySelector('.sel-area').value   = aId;
    if (nId) tr.querySelector('.sel-nivel').value  = nId;
}

function aplicarBloque() {
    document.querySelectorAll('.sel-bloque').forEach(s => { const v = getBloqueId(); if(v) s.value = v; });
}
function aplicarArea() {
    document.querySelectorAll('.sel-area').forEach(s => { const v = getAreaId(); if(v) s.value = v; });
}
function aplicarNivel() {
    document.querySelectorAll('.sel-nivel').forEach(s => { const v = getNivelId(); s.value = v; });
}
function aplicarIva() {
    document.querySelectorAll('.inp-iva').forEach(inp => { inp.value = getIvaGlobal(); });
    recalcTodo();
}

function alCambiarConcepto(sel, ri) {
    const opt = sel.options[sel.selectedIndex];
    const tr  = sel.closest('tr');
    const pu  = parseFloat(opt.dataset.pu || 0);
    tr.querySelector('.inp-pu').value = pu.toFixed(2);
    recalcFila(ri);
}

function recalcFila(ri) {
    const tr   = document.querySelector(`tr[data-ri="${ri}"]`);
    if (!tr) return;
    const pu   = parseFloat(tr.querySelector('.inp-pu').value   || 0);
    const cant = parseFloat(tr.querySelector('.inp-cant').value  || 0);
    const iva  = parseFloat(tr.querySelector('.inp-iva').value   || 16);
    const sub  = pu * cant;
    const ivaMonto = sub * (iva / 100);
    const tot  = sub + ivaMonto;
    tr.querySelector('.lbl-tot').textContent  = '$' + tot.toFixed(2);
    tr.querySelector('.hid-sub').value         = sub.toFixed(4);
    recalcTodo();
}

// Add validation to make sure block and area are selected when selecting a concept
document.getElementById('formConceptos').addEventListener('submit', function(e) {
    const selectBlocks = document.querySelectorAll('.sel-bloque');
    const selectAreas = document.querySelectorAll('.sel-area');
    let valid = true;
    selectBlocks.forEach(select => {
        if (!select.value) {
            alert('Por favor selecciona un Bloque para cada fila.');
            valid = false;
            e.preventDefault();
        }
    });
    if (!valid) return;
    selectAreas.forEach(select => {
        if (!select.value) {
            alert('Por favor selecciona un Área para cada fila.');
            valid = false;
            e.preventDefault();
        }
    });
});

function recalcTodo() {
    let totalSub = 0, totalIva = 0, totalFin = 0;
    document.querySelectorAll('.hid-sub').forEach(h => totalSub += parseFloat(h.value || 0));
    document.querySelectorAll('.lbl-tot').forEach(l => totalFin += parseFloat(l.textContent.replace('$','') || 0));
    totalIva = totalFin - totalSub;
    document.getElementById('spanSub').textContent = totalSub.toFixed(2);
    document.getElementById('spanIva').textContent = totalIva.toFixed(2);
    document.getElementById('spanTot').textContent = totalFin.toFixed(2);
}

function toggleNuevoConcepto(chk, ri) {
    const tr  = document.querySelector(`tr[data-ri="${ri}"]`);
    const sel = tr.querySelector('.sel-concepto');
    const inp = tr.querySelector('.inp-nuevo-concepto');
    if (chk.checked) {
        sel.style.display = 'none';
        sel.value = '';
        inp.style.display = 'block';
        inp.required = true;
    } else {
        sel.style.display = 'block';
        inp.style.display = 'none';
        inp.required = false;
        inp.value = '';
    }
}

document.getElementById('btnAgregar').addEventListener('click', crearFila);

// Iniciar con 3 renglones vacíos
crearFila(); crearFila(); crearFila();
</script>
@endsection