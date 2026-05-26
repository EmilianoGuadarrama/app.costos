@extends('layout')
@section('title','Nuevo Presupuesto Rápido')
@section('content')
<style>
/* ── Formulario: Nuevo Presupuesto ── */
.pf-wrap { font-family: "Arial", sans-serif; color: #111; }

.btn-back-link {
    color: #6b7280;
    text-decoration: none;
    font-size: .82rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 20px;
    font-weight: 500;
    transition: color .2s;
}
.btn-back-link:hover { color: #111; }

.pf-header { margin-bottom: 24px; }
.pf-title {
    font-size: 1.9rem;
    font-weight: 800;
    color: #111;
    margin: 0;
    font-family: "Garamond","Baskerville",serif;
}
.pf-subtitle { color: #6b7280; margin: 5px 0 0; font-size: .9rem; }

/* Tarjeta */
.pf-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 26px 28px;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.pf-card-title {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #9ca3af;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
}
.pf-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.pf-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.pf-label { font-size: .8rem; font-weight: 700; color: #374151; display: block; margin-bottom: 5px; }
.pf-control, .pf-select {
    width: 100%; padding: .55rem .85rem;
    border: 1.5px solid #e5e7eb; border-radius: 8px;
    font-size: .88rem; transition: border-color .18s;
    background: #fff; color: #111;
}
.pf-control:focus, .pf-select:focus {
    border-color: #111; outline: none;
    box-shadow: 0 0 0 3px rgba(17,24,39,.06);
}
.pf-error { color: #b91c1c; font-size: .76rem; margin-top: 3px; }

.alert-err {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: 12px 16px;
    color: #b91c1c;
    font-size: .85rem;
    margin-bottom: 18px;
}

/* Toolbar de renglones */
.renglon-toolbar {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 14px;
}
.btn-add-bloque {
    background: #111;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: .5rem 1.1rem;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    letter-spacing: .4px;
    text-transform: uppercase;
    transition: background .2s;
}
.btn-add-bloque:hover { background: #374151; }
.btn-add-concepto {
    background: #374151;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: .5rem 1.1rem;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background .2s;
}
.btn-add-concepto:hover { background: #111; }

/* Bloque en el formulario */
.bloque-form {
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 14px;
    overflow: hidden;
}
.bloque-form-header {
    background: #1c1c1c;
    color: #fff;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.bloque-form-title { flex: 1; font-weight: 700; font-size: .88rem; }
.btn-remove-bloque {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    font-size: .9rem;
    padding: 2px 8px;
    transition: color .2s;
}
.btn-remove-bloque:hover { color: #fff; }

/* Tabla de renglones */
.renglones-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
.renglones-table th {
    background: #f9fafb;
    color: #6b7280;
    font-size: .66rem;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 8px 10px;
    border-bottom: 1px solid #e5e7eb;
    font-weight: 700;
    text-align: left;
}
.renglones-table td {
    padding: 6px 8px;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}
.renglones-table tr:last-child td { border-bottom: none; }
.renglones-table .inp-sm {
    padding: .35rem .6rem;
    font-size: .82rem;
    border: 1.5px solid #e5e7eb;
    border-radius: 7px;
    width: 100%;
    background: #fff;
    color: #111;
    transition: border-color .15s;
}
.renglones-table .inp-sm:focus { border-color: #111; outline: none; }
.renglones-table .importe-val {
    font-weight: 700;
    text-align: right;
    font-variant-numeric: tabular-nums;
    color: #111;
}
.btn-rm-row {
    background: none;
    border: none;
    color: #d1d5db;
    cursor: pointer;
    font-size: 1rem;
    transition: color .2s;
}
.btn-rm-row:hover { color: #b91c1c; }
.btn-add-row {
    background: none;
    border: 1px dashed #d1d5db;
    color: #9ca3af;
    padding: 5px 14px;
    border-radius: 7px;
    font-size: .76rem;
    cursor: pointer;
    margin: 8px 10px;
    transition: all .2s;
}
.btn-add-row:hover { border-color: #374151; color: #374151; }

/* Caja de totales */
.totales-caja {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 22px 26px;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}
.tc-item { text-align: center; }
.tc-label {
    font-size: .65rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #9ca3af;
    font-weight: 700;
}
.tc-value {
    font-size: 1.35rem;
    font-weight: 900;
    margin-top: 5px;
    font-variant-numeric: tabular-nums;
    color: #111;
}
.tc-value.accent { color: #111; }
.tc-total-box {
    background: #111;
    border-radius: 10px;
    padding: 14px;
}
.tc-total-box .tc-label { color: #6b7280; }
.tc-total-box .tc-value { color: #fff; }

/* Acciones del formulario */
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #f3f4f6;
}
.btn-guardar {
    background: #111;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: .75rem 1.8rem;
    font-size: .85rem;
    font-weight: 700;
    cursor: pointer;
    letter-spacing: .4px;
    transition: background .2s;
}
.btn-guardar:hover { background: #374151; }
.btn-cancelar {
    background: transparent;
    color: #6b7280;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    padding: .75rem 1.4rem;
    font-size: .85rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all .2s;
}
.btn-cancelar:hover { border-color: #111; color: #111; }
</style>

<div class="pf-wrap">
    <a href="{{ route('presupuestos.index') }}" class="btn-back-link">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>

    @if($errors->any())
    <div class="alert-err">
        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="pf-header">
        <h1 class="pf-title">Nuevo Presupuesto</h1>
        <p class="pf-subtitle">Crea un presupuesto organizado por bloques y áreas de trabajo</p>
    </div>

    <form action="{{ route('presupuestos.store') }}" method="POST" id="formPresupuesto">
        @csrf

        {{-- Datos generales --}}
        <div class="pf-card">
            <div class="pf-card-title"><i class="bi bi-info-circle me-1"></i> Datos Generales</div>
            <div class="pf-grid-2" style="margin-bottom:16px;">
                <div>
                    <label class="pf-label" for="proyecto_id">Proyecto *</label>
                    <select id="proyecto_id" name="proyecto_id" class="pf-select" required>
                        <option value="">— Seleccione proyecto —</option>
                        @foreach($proyectos as $p)
                            <option value="{{ $p->id }}" {{ (old('proyecto_id', request('proyecto_id')) == $p->id) ? 'selected' : '' }}>
                                {{ $p->nombre }}{{ $p->cliente ? ' — ' . $p->cliente->nombre : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('proyecto_id')<span class="pf-error">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="pf-label" for="fecha">Fecha *</label>
                    <input type="date" id="fecha" name="fecha" class="pf-control"
                           value="{{ old('fecha', now()->format('Y-m-d')) }}" required>
                    @error('fecha')<span class="pf-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div style="margin-bottom:16px;">
                <label class="pf-label" for="nombre">Nombre del Presupuesto *</label>
                <input type="text" id="nombre" name="nombre" class="pf-control"
                       value="{{ old('nombre') }}" required maxlength="150"
                       placeholder="Ej. Presupuesto Base Fase 1">
                @error('nombre')<span class="pf-error">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="pf-label" for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones" class="pf-control" rows="2"
                          style="resize:vertical;">{{ old('observaciones') }}</textarea>
            </div>
        </div>

        {{-- Renglones por bloque --}}
        <div class="pf-card">
            <div class="pf-card-title"><i class="bi bi-list-columns me-1"></i> Renglones del Presupuesto</div>

            <div class="renglon-toolbar">
                <button type="button" class="btn-add-bloque" id="btnNuevoBloque">
                    <i class="bi bi-plus-square"></i> Agregar Bloque
                </button>
                <small style="color:#9ca3af;font-size:.76rem;">Organiza los conceptos en bloques (Albañilerías, Eléctrico, Plomería…)</small>
            </div>

            <div id="contenedorBloques"></div>
        </div>

        {{-- Totales --}}
        <div class="totales-caja">
            <div class="tc-item">
                <div class="tc-label">Subtotal (sin IVA)</div>
                <div class="tc-value">$<span id="spanSubtotal">0.00</span></div>
            </div>
            <div class="tc-item">
                <div class="tc-label">IVA (16%)</div>
                <div class="tc-value">$<span id="spanIva">0.00</span></div>
            </div>
            <div class="tc-item tc-total-box">
                <div class="tc-label">Total Final</div>
                <div class="tc-value accent">$<span id="spanTotal">0.00</span></div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('presupuestos.index') }}" class="btn-cancelar">Cancelar</a>
            <button type="submit" class="btn-guardar" id="btn-guardar-presupuesto">
                <i class="bi bi-check-lg me-1"></i> Guardar Presupuesto
            </button>
        </div>
    </form>
</div>

<script>
// ── Catálogos ──
const catBloques = @json($bloques ?? []);
const catConceptos = @json($conceptos->map(fn($c) => [
    'id'     => $c->id,
    'label'  => Str::limit($c->descripcion, 70),
    'clave'  => $c->clave ?? '',
    'unidad' => $c->unidadMedida->abreviatura ?? '—',
    'pu'     => $c->analisisPu->precio_unitario ?? ($c->analisisPu->costo_total ?? 0),
    'area_id'=> $c->area_id ?? null,
    'area'   => $c->area->nombre ?? '—',
]));

let bloqueIdx = 0;
let renglonIdx = 0;

function opcionesBloques() {
    let opts = '<option value="">— Sin bloque —</option>';
    catBloques.forEach(b => { opts += `<option value="${b.id}">${b.nombre}</option>`; });
    return opts;
}

function opcionesConceptos() {
    let opts = '<option value="">— Seleccione concepto —</option>';
    catConceptos.forEach(c => {
        opts += `<option value="${c.id}" data-unidad="${c.unidad}" data-pu="${c.pu}" data-area="${c.area}">
            ${c.label} [${c.unidad}]
        </option>`;
    });
    return opts;
}

function crearBloque() {
    const bi = bloqueIdx++;
    const div = document.createElement('div');
    div.className = 'bloque-form';
    div.dataset.bloqueIdx = bi;
    div.innerHTML = `
        <div class="bloque-form-header">
            <span class="bloque-form-title">
                <select name="bloques[${bi}][bloque_id]" class="inp-sm" style="background:#2c2c2c;color:#fff;border-color:#444;width:210px;" onchange="actualizarTitulo(this)">
                    ${opcionesBloques()}
                </select>
            </span>
            <button type="button" class="btn-remove-bloque" onclick="this.closest('.bloque-form').remove();recalcularTodo()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <table class="renglones-table">
            <thead>
                <tr>
                    <th style="width:38%">Concepto / Descripción</th>
                    <th style="width:8%">Área</th>
                    <th style="width:7%">U.M.</th>
                    <th style="width:9%">P.U.</th>
                    <th style="width:9%">Cantidad</th>
                    <th style="width:11%">Subtotal</th>
                    <th style="width:8%">IVA%</th>
                    <th style="width:11%">Total</th>
                    <th style="width:4%"></th>
                </tr>
            </thead>
            <tbody class="tbody-renglones" data-bloque="${bi}"></tbody>
        </table>
        <button type="button" class="btn-add-row" onclick="agregarRenglon(${bi})">
            <i class="bi bi-plus"></i> Agregar concepto
        </button>
    `;
    document.getElementById('contenedorBloques').appendChild(div);
    agregarRenglon(bi);
}

function agregarRenglon(bi) {
    const ri = renglonIdx++;
    const tbody = document.querySelector(`.tbody-renglones[data-bloque="${bi}"]`);
    const tr = document.createElement('tr');
    tr.dataset.ri = ri;
    tr.innerHTML = `
        <td>
            <select name="detalles[${ri}][concepto_id]" class="inp-sm sel-concepto" data-ri="${ri}" onchange="alCambiarConcepto(this, ${ri})">
                ${opcionesConceptos()}
            </select>
            <input type="hidden" name="detalles[${ri}][bloque_id]" class="hid-bloque" value="">
        </td>
        <td><input type="text" class="inp-sm lbl-area" readonly tabindex="-1" style="background:#f9fafb;"></td>
        <td><input type="text" class="inp-sm lbl-unidad" readonly tabindex="-1" style="background:#f9fafb;"></td>
        <td><input type="number" step="0.01" min="0" name="detalles[${ri}][pu_unitario_snapshot]" class="inp-sm inp-pu" value="0" oninput="recalcularFila(this.closest('tr'))"></td>
        <td><input type="number" step="0.0001" min="0" name="detalles[${ri}][cantidad]" class="inp-sm inp-cant" value="0" oninput="recalcularFila(this.closest('tr'))"></td>
        <td class="importe-val"><span class="lbl-sub">$0.00</span><input type="hidden" name="detalles[${ri}][subtotal]" class="hid-sub" value="0"></td>
        <td><input type="number" step="1" min="0" max="100" name="detalles[${ri}][porcentaje_iva]" class="inp-sm inp-iva" value="16" style="width:60px;" oninput="recalcularFila(this.closest('tr'))"></td>
        <td class="importe-val"><span class="lbl-tot">$0.00</span><input type="hidden" name="detalles[${ri}][total_final]" class="hid-tot" value="0"></td>
        <td><button type="button" class="btn-rm-row" onclick="this.closest('tr').remove();recalcularTodo()"><i class="bi bi-trash3"></i></button></td>
    `;
    tbody.appendChild(tr);
    sincronizarBloqueId(tr);
}

function sincronizarBloqueId(tr) {
    const tbody = tr.closest('tbody');
    const bi = tbody.dataset.bloque;
    const bloqueForm = document.querySelector(`.bloque-form[data-bloque-idx="${bi}"]`);
    const bloqueSelect = bloqueForm?.querySelector('select[name^="bloques["]');
    const val = bloqueSelect?.value ?? '';
    tr.querySelector('.hid-bloque').value = val;
}

document.getElementById('contenedorBloques').addEventListener('change', (e) => {
    if (e.target.name && e.target.name.includes('bloque_id') && !e.target.classList.contains('hid-bloque')) {
        const bloqueForm = e.target.closest('.bloque-form');
        const bi = bloqueForm.dataset.bloqueIdx;
        bloqueForm.querySelectorAll('.hid-bloque').forEach(hid => hid.value = e.target.value);
    }
});

function alCambiarConcepto(sel, ri) {
    const opt = sel.options[sel.selectedIndex];
    const tr  = sel.closest('tr');
    tr.querySelector('.lbl-unidad').value  = opt.dataset.unidad || '—';
    tr.querySelector('.lbl-area').value    = opt.dataset.area   || '—';
    const pu = parseFloat(opt.dataset.pu || 0);
    const inpPu = tr.querySelector('.inp-pu');
    if (inpPu && parseFloat(inpPu.value) === 0) inpPu.value = pu.toFixed(2);
    recalcularFila(tr);
}

function recalcularFila(tr) {
    const cant = parseFloat(tr.querySelector('.inp-cant')?.value || 0);
    const pu   = parseFloat(tr.querySelector('.inp-pu')?.value   || 0);
    const pct  = parseFloat(tr.querySelector('.inp-iva')?.value  || 16);
    const sub  = cant * pu;
    const iva  = sub * (pct / 100);
    const tot  = sub + iva;

    tr.querySelector('.lbl-sub').textContent = '$' + sub.toFixed(2);
    tr.querySelector('.hid-sub').value        = sub.toFixed(4);
    tr.querySelector('.lbl-tot').textContent  = '$' + tot.toFixed(2);
    tr.querySelector('.hid-tot').value         = tot.toFixed(4);

    recalcularTodo();
}

function recalcularTodo() {
    let totalSub = 0, totalIva = 0, totalFin = 0;
    document.querySelectorAll('.hid-sub').forEach(h => { totalSub += parseFloat(h.value || 0); });
    document.querySelectorAll('.hid-tot').forEach(h => { totalFin += parseFloat(h.value || 0); });
    totalIva = totalFin - totalSub;
    document.getElementById('spanSubtotal').textContent = totalSub.toFixed(2);
    document.getElementById('spanIva').textContent      = totalIva.toFixed(2);
    document.getElementById('spanTotal').textContent    = totalFin.toFixed(2);
}

document.getElementById('btnNuevoBloque').addEventListener('click', crearBloque);

// Iniciar con un bloque vacío
crearBloque();
</script>
@endsection