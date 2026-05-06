@extends('layout')
@section('title','Nuevo Análisis P.U.')
@section('content')
<style>
    .pu-form-wrap{ padding:20px; font-family:"Arial",sans-serif; color:#111; }
    .pu-panel{ background:#fff; border-radius:14px; box-shadow:0 4px 16px rgba(0,0,0,.07); padding:36px; }
    .pu-header{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:28px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; }
    .pu-header h1{ font-size:2rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .section-title{ font-size:.75rem; letter-spacing:2.5px; text-transform:uppercase; color:#888; font-weight:700; margin:28px 0 14px; border-top:1px solid #f0f0f0; padding-top:20px; }
    .form-group{ margin-bottom:18px; }
    .form-group label{ display:block; margin-bottom:6px; font-weight:600; font-size:.88rem; color:#444; }
    .form-control, .form-select{ width:100%; padding:9px 13px; border:1px solid #d0d0d0; border-radius:7px; font-size:.92rem; background:#fff; }
    .form-control:focus, .form-select:focus{ border-color:#111; outline:none; box-shadow:0 0 0 3px rgba(0,0,0,.06); }
    textarea.form-control{ resize:vertical; min-height:70px; }

    /* Tabla de insumos */
    .insumos-table{ width:100%; border-collapse:separate; border-spacing:0 6px; }
    .insumos-table thead th{ font-size:.72rem; letter-spacing:1.5px; text-transform:uppercase; color:#888; padding:6px 10px; font-weight:700; }
    .insumos-table tbody tr td{ padding:5px 8px; vertical-align:middle; }
    .insumos-table .form-select, .insumos-table .form-control{ padding:7px 10px; font-size:.88rem; }
    .btn-add-row{ background:#f0f0f0; border:1px dashed #bbb; color:#555; padding:7px 16px; border-radius:6px; font-size:.82rem; cursor:pointer; transition:all .2s; }
    .btn-add-row:hover{ background:#e4e4e4; color:#111; }
    .btn-remove-row{ background:none; border:none; color:#ccc; font-size:1.1rem; cursor:pointer; padding:4px 8px; }
    .btn-remove-row:hover{ color:#b91c1c; }
    .importe-cell{ font-weight:700; color:#111; font-size:.9rem; min-width:90px; text-align:right; }

    /* Totales */
    .totals-box{ background:#fafafa; border:1px solid #e8e8e8; border-radius:10px; padding:18px 22px; margin-top:24px; display:flex; flex-direction:column; align-items:flex-end; gap:8px; }
    .total-row{ display:flex; gap:40px; font-size:.9rem; }
    .total-row span:first-child{ color:#666; }
    .total-row span:last-child{ font-weight:700; min-width:110px; text-align:right; }
    .total-final{ font-size:1.05rem; color:#111; border-top:1px solid #e0e0e0; padding-top:10px; margin-top:6px; }

    /* Acciones */
    .form-actions{ margin-top:28px; display:flex; gap:12px; justify-content:flex-end; }
    .btn-submit{ background:#111; color:#fff; border:none; padding:11px 28px; border-radius:7px; font-size:.88rem; font-weight:600; cursor:pointer; letter-spacing:.5px; transition:background .2s; }
    .btn-submit:hover{ background:#333; }
    .btn-back{ display:inline-flex; align-items:center; gap:6px; color:#666; text-decoration:none; font-size:.9rem; margin-bottom:18px; }
    .btn-back:hover{ color:#111; }
    .text-danger{ color:#dc3545; font-size:.82rem; margin-top:4px; display:block; }
    .alert-error{ background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:12px 16px; color:#b91c1c; font-size:.88rem; margin-bottom:20px; }
</style>

<div class="pu-form-wrap">
    <a href="{{ route('analisis_pu.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver al listado</a>

    @if($errors->any())
        <div class="alert-error"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="pu-panel">
        <div class="pu-header">
            <h1>Nuevo Análisis P.U.</h1>
        </div>

        <form action="{{ route('analisis_pu.store') }}" method="POST" id="formApu">
            @csrf

            {{-- CONCEPTO --}}
            <div class="form-group">
                <label for="concepto_id">Concepto *</label>
                <select id="concepto_id" name="concepto_id" class="form-select" required>
                    <option value="">— Seleccione un concepto —</option>
                    @foreach($conceptos as $c)
                        <option value="{{ $c->id }}"
                            data-unidad="{{ $c->unidadMedida->abreviatura ?? '' }}"
                            {{ old('concepto_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->clave }} — {{ Str::limit($c->descripcion, 70) }}
                        </option>
                    @endforeach
                </select>
                @error('concepto_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones" class="form-control">{{ old('observaciones') }}</textarea>
            </div>

            {{-- ======================== MATERIALES ======================== --}}
            <div class="section-title"><i class="bi bi-box-seam me-1"></i> Materiales</div>
            <table class="insumos-table" id="tablaMateriales">
                <thead><tr>
                    <th style="width:40%">Material</th>
                    <th style="width:18%">Cantidad</th>
                    <th style="width:20%">Costo unitario</th>
                    <th style="width:16%">Importe</th>
                    <th style="width:6%"></th>
                </tr></thead>
                <tbody id="bodyMateriales">
                    <tr class="fila-material">
                        <td><select name="materiales[0][material_id]" class="form-select sel-material">
                            <option value="">— Material —</option>
                            @foreach($materiales as $m)<option value="{{ $m->id }}" data-precio="{{ $m->precio_unitario }}">{{ $m->clave }} — {{ $m->nombre }}</option>@endforeach
                        </select></td>
                        <td><input type="number" step="0.0001" min="0" name="materiales[0][cantidad]" class="form-control inp-cantidad" placeholder="0.0000"></td>
                        <td><input type="number" step="0.0001" min="0" name="materiales[0][costo_unitario]" class="form-control inp-costo" placeholder="0.0000"></td>
                        <td class="importe-cell">$<span class="lbl-importe">0.00</span></td>
                        <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn-add-row mt-2" onclick="agregarFila('materiales','bodyMateriales')"><i class="bi bi-plus"></i> Agregar material</button>

            {{-- ======================== MANO DE OBRA ======================== --}}
            <div class="section-title"><i class="bi bi-person-hard-hat me-1"></i> Mano de Obra</div>
            <table class="insumos-table" id="tablaManoObra">
                <thead><tr>
                    <th style="width:40%">Categoría</th>
                    <th style="width:18%">Cantidad (jor)</th>
                    <th style="width:20%">Salario unitario</th>
                    <th style="width:16%">Importe</th>
                    <th style="width:6%"></th>
                </tr></thead>
                <tbody id="bodyManoObra">
                    <tr class="fila-mano_obra">
                        <td><select name="mano_obra[0][mano_obra_id]" class="form-select sel-manoobra">
                            <option value="">— Categoría —</option>
                            @foreach($manoObra as $mo)<option value="{{ $mo->id }}" data-precio="{{ $mo->salario_unitario }}">{{ $mo->clave }} — {{ $mo->categoria }}</option>@endforeach
                        </select></td>
                        <td><input type="number" step="0.0001" min="0" name="mano_obra[0][cantidad]" class="form-control inp-cantidad" placeholder="0.0000"></td>
                        <td><input type="number" step="0.0001" min="0" name="mano_obra[0][costo_unitario]" class="form-control inp-costo" placeholder="0.0000"></td>
                        <td class="importe-cell">$<span class="lbl-importe">0.00</span></td>
                        <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn-add-row mt-2" onclick="agregarFila('mano_obra','bodyManoObra')"><i class="bi bi-plus"></i> Agregar categoría</button>

            {{-- ======================== MAQUINARIA ======================== --}}
            <div class="section-title"><i class="bi bi-truck me-1"></i> Maquinaria y Equipo</div>
            <table class="insumos-table">
                <thead><tr>
                    <th style="width:40%">Equipo</th>
                    <th style="width:18%">Cantidad (h)</th>
                    <th style="width:20%">Costo por hora</th>
                    <th style="width:16%">Importe</th>
                    <th style="width:6%"></th>
                </tr></thead>
                <tbody id="bodyMaquinaria">
                    <tr class="fila-maquinaria">
                        <td><select name="maquinaria[0][maquinaria_equipo_id]" class="form-select sel-maquinaria">
                            <option value="">— Equipo —</option>
                            @foreach($maquinaria as $mq)<option value="{{ $mq->id }}" data-precio="{{ $mq->costo_por_hora }}">{{ $mq->clave }} — {{ $mq->equipo }}</option>@endforeach
                        </select></td>
                        <td><input type="number" step="0.0001" min="0" name="maquinaria[0][cantidad]" class="form-control inp-cantidad" placeholder="0.0000"></td>
                        <td><input type="number" step="0.0001" min="0" name="maquinaria[0][costo_unitario]" class="form-control inp-costo" placeholder="0.0000"></td>
                        <td class="importe-cell">$<span class="lbl-importe">0.00</span></td>
                        <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn-add-row mt-2" onclick="agregarFila('maquinaria','bodyMaquinaria')"><i class="bi bi-plus"></i> Agregar equipo</button>

            {{-- ======================== INDIRECTOS ======================== --}}
            <div class="section-title"><i class="bi bi-percent me-1"></i> Indirectos</div>
            <table class="insumos-table">
                <thead><tr>
                    <th style="width:55%">Concepto de indirecto</th>
                    <th style="width:30%">% Aplicado</th>
                    <th style="width:15%"></th>
                </tr></thead>
                <tbody id="bodyIndirectos">
                    <tr class="fila-indirectos">
                        <td><select name="indirectos[0][indirecto_id]" class="form-select sel-indirecto">
                            <option value="">— Indirecto —</option>
                            @foreach($indirectos as $ind)<option value="{{ $ind->id }}" data-porcentaje="{{ $ind->porcentaje }}">{{ $ind->clave }} — {{ $ind->concepto }} ({{ $ind->porcentaje }}%)</option>@endforeach
                        </select></td>
                        <td><input type="number" step="0.0001" min="0" max="100" name="indirectos[0][porcentaje_aplicado]" class="form-control inp-porcentaje" placeholder="0.00"></td>
                        <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn-add-row mt-2" onclick="agregarFila('indirectos','bodyIndirectos')"><i class="bi bi-plus"></i> Agregar indirecto</button>

            {{-- TOTALES --}}
            <div class="totals-box">
                <div class="total-row"><span>Materiales:</span><span>$<span id="totalMateriales">0.00</span></span></div>
                <div class="total-row"><span>Mano de Obra:</span><span>$<span id="totalManoObra">0.00</span></span></div>
                <div class="total-row"><span>Maquinaria:</span><span>$<span id="totalMaquinaria">0.00</span></span></div>
                <div class="total-row"><span>Costo Directo:</span><span>$<span id="totalDirecto">0.00</span></span></div>
                <div class="total-row total-final"><span>Indirectos aplicados:</span><span>$<span id="totalIndirectos">0.00</span></span></div>
                <div class="total-row total-final" style="font-size:1.15rem"><span><strong>Total P.U.:</strong></span><span><strong>$<span id="totalPU">0.00</span></strong></span></div>
            </div>

            <div class="form-actions">
                <a href="{{ route('analisis_pu.index') }}" class="btn-submit" style="background:#888;text-decoration:none;">Cancelar</a>
                <button type="submit" class="btn-submit">Guardar Análisis</button>
            </div>
        </form>
    </div>
</div>

<script>
// ─── Catálogos para clonar filas ───────────────────────────────────────────
const catMateriales = @json($materiales->map(fn($m) => ['id'=>$m->id,'label'=>$m->clave.' — '.$m->nombre,'precio'=>$m->precio_unitario]));
const catManoObra   = @json($manoObra->map(fn($m) => ['id'=>$m->id,'label'=>$m->clave.' — '.$m->categoria,'precio'=>$m->salario_unitario]));
const catMaquinaria = @json($maquinaria->map(fn($m) => ['id'=>$m->id,'label'=>$m->clave.' — '.$m->equipo,'precio'=>$m->costo_por_hora]));
const catIndirectos = @json($indirectos->map(fn($i) => ['id'=>$i->id,'label'=>$i->clave.' — '.$i->concepto.' ('.$i->porcentaje.'%)','porcentaje'=>$i->porcentaje]));

const contadores = { materiales:1, mano_obra:1, maquinaria:1, indirectos:1 };

function buildOptions(cat, valKey) {
    return cat.map(item => `<option value="${item.id}" data-precio="${item[valKey] ?? item.porcentaje ?? 0}">${item.label}</option>`).join('');
}

function agregarFila(tipo, bodyId) {
    const tbody = document.getElementById(bodyId);
    const idx   = contadores[tipo]++;
    let html = '';

    if (tipo === 'materiales') {
        html = `<tr class="fila-${tipo}">
            <td><select name="${tipo}[${idx}][material_id]" class="form-select sel-material"><option value="">— Material —</option>${buildOptions(catMateriales,'precio')}</select></td>
            <td><input type="number" step="0.0001" min="0" name="${tipo}[${idx}][cantidad]" class="form-control inp-cantidad" placeholder="0.0000"></td>
            <td><input type="number" step="0.0001" min="0" name="${tipo}[${idx}][costo_unitario]" class="form-control inp-costo" placeholder="0.0000"></td>
            <td class="importe-cell">$<span class="lbl-importe">0.00</span></td>
            <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
        </tr>`;
    } else if (tipo === 'mano_obra') {
        html = `<tr class="fila-${tipo}">
            <td><select name="${tipo}[${idx}][mano_obra_id]" class="form-select sel-manoobra"><option value="">— Categoría —</option>${buildOptions(catManoObra,'precio')}</select></td>
            <td><input type="number" step="0.0001" min="0" name="${tipo}[${idx}][cantidad]" class="form-control inp-cantidad" placeholder="0.0000"></td>
            <td><input type="number" step="0.0001" min="0" name="${tipo}[${idx}][costo_unitario]" class="form-control inp-costo" placeholder="0.0000"></td>
            <td class="importe-cell">$<span class="lbl-importe">0.00</span></td>
            <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
        </tr>`;
    } else if (tipo === 'maquinaria') {
        html = `<tr class="fila-${tipo}">
            <td><select name="${tipo}[${idx}][maquinaria_equipo_id]" class="form-select sel-maquinaria"><option value="">— Equipo —</option>${buildOptions(catMaquinaria,'precio')}</select></td>
            <td><input type="number" step="0.0001" min="0" name="${tipo}[${idx}][cantidad]" class="form-control inp-cantidad" placeholder="0.0000"></td>
            <td><input type="number" step="0.0001" min="0" name="${tipo}[${idx}][costo_unitario]" class="form-control inp-costo" placeholder="0.0000"></td>
            <td class="importe-cell">$<span class="lbl-importe">0.00</span></td>
            <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
        </tr>`;
    } else if (tipo === 'indirectos') {
        html = `<tr class="fila-${tipo}">
            <td><select name="${tipo}[${idx}][indirecto_id]" class="form-select sel-indirecto"><option value="">— Indirecto —</option>${buildOptions(catIndirectos,'porcentaje')}</select></td>
            <td><input type="number" step="0.0001" min="0" max="100" name="${tipo}[${idx}][porcentaje_aplicado]" class="form-control inp-porcentaje" placeholder="0.00"></td>
            <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
        </tr>`;
    }
    tbody.insertAdjacentHTML('beforeend', html);
    bindRowEvents(tbody.lastElementChild);
    recalcularTotales();
}

function eliminarFila(btn) {
    btn.closest('tr').remove();
    recalcularTotales();
}

// Autocompletar precio al seleccionar insumo
function bindRowEvents(row) {
    const sel = row.querySelector('select');
    const inpCosto = row.querySelector('.inp-costo');
    const inpPorc  = row.querySelector('.inp-porcentaje');

    if (sel && inpCosto) {
        sel.addEventListener('change', () => {
            const opt = sel.options[sel.selectedIndex];
            const precio = parseFloat(opt.dataset.precio || 0);
            if (inpCosto && !inpCosto.value) inpCosto.value = precio.toFixed(4);
            actualizarImporteFila(row);
            recalcularTotales();
        });
    }
    if (sel && inpPorc) {
        sel.addEventListener('change', () => {
            const opt = sel.options[sel.selectedIndex];
            if (inpPorc && !inpPorc.value) inpPorc.value = parseFloat(opt.dataset.porcentaje || opt.dataset.precio || 0).toFixed(4);
            recalcularTotales();
        });
        inpPorc.addEventListener('input', recalcularTotales);
    }
    row.querySelectorAll('.inp-cantidad, .inp-costo').forEach(inp => {
        inp.addEventListener('input', () => { actualizarImporteFila(row); recalcularTotales(); });
    });
}

function actualizarImporteFila(row) {
    const cant  = parseFloat(row.querySelector('.inp-cantidad')?.value || 0);
    const costo = parseFloat(row.querySelector('.inp-costo')?.value || 0);
    const lbl   = row.querySelector('.lbl-importe');
    if (lbl) lbl.textContent = (cant * costo).toFixed(2);
}

function recalcularTotales() {
    const sumaGrupo = (selector) => {
        let t = 0;
        document.querySelectorAll(selector).forEach(tr => {
            const cant  = parseFloat(tr.querySelector('.inp-cantidad')?.value || 0);
            const costo = parseFloat(tr.querySelector('.inp-costo')?.value || 0);
            t += cant * costo;
        });
        return t;
    };

    const tMat = sumaGrupo('#bodyMateriales tr');
    const tMO  = sumaGrupo('#bodyManoObra tr');
    const tMaq = sumaGrupo('#bodyMaquinaria tr');
    const directo = tMat + tMO + tMaq;

    // Sumar porcentajes de indirectos
    let totalPorc = 0;
    document.querySelectorAll('#bodyIndirectos tr .inp-porcentaje').forEach(inp => {
        totalPorc += parseFloat(inp.value || 0);
    });
    const tInd = directo * (totalPorc / 100);
    const total = directo + tInd;

    document.getElementById('totalMateriales').textContent = tMat.toFixed(2);
    document.getElementById('totalManoObra').textContent   = tMO.toFixed(2);
    document.getElementById('totalMaquinaria').textContent = tMaq.toFixed(2);
    document.getElementById('totalDirecto').textContent    = directo.toFixed(2);
    document.getElementById('totalIndirectos').textContent = tInd.toFixed(2);
    document.getElementById('totalPU').textContent         = total.toFixed(2);
}

// Bindear la fila inicial que viene renderizada en el HTML
document.querySelectorAll('#bodyMateriales tr, #bodyManoObra tr, #bodyMaquinaria tr, #bodyIndirectos tr').forEach(bindRowEvents);
</script>
@endsection
