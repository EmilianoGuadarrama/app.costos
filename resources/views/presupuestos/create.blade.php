@extends('layout')
@section('title','Nuevo Presupuesto')
@section('content')
<style>
    .pu-form-wrap{ padding:20px; font-family:"Arial",sans-serif; color:#111; }
    .pu-panel{ background:#fff; border-radius:14px; box-shadow:0 4px 16px rgba(0,0,0,.07); padding:36px; }
    .pu-header{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:28px; }
    .pu-header h1{ font-size:2rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .section-title{ font-size:.72rem; letter-spacing:2.5px; text-transform:uppercase; color:#888; font-weight:700; margin:28px 0 14px; border-top:1px solid #f0f0f0; padding-top:18px; }
    .form-group{ margin-bottom:18px; }
    .form-group label{ display:block; margin-bottom:6px; font-weight:600; font-size:.88rem; color:#444; }
    .form-control, .form-select{ width:100%; padding:9px 13px; border:1px solid #d0d0d0; border-radius:7px; font-size:.92rem; }
    .form-control:focus, .form-select:focus{ border-color:#111; outline:none; box-shadow:0 0 0 3px rgba(0,0,0,.06); }
    textarea.form-control{ resize:vertical; min-height:70px; }
    .row-2{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }

    .insumos-table{ width:100%; border-collapse:separate; border-spacing:0 6px; }
    .insumos-table thead th{ font-size:.72rem; letter-spacing:1.5px; text-transform:uppercase; color:#888; padding:6px 10px; font-weight:700; }
    .insumos-table tbody tr td{ padding:5px 8px; vertical-align:middle; }
    .insumos-table .form-select, .insumos-table .form-control{ padding:7px 10px; font-size:.88rem; }
    .btn-add-row{ background:#f0f0f0; border:1px dashed #bbb; color:#555; padding:7px 16px; border-radius:6px; font-size:.82rem; cursor:pointer; transition:all .2s; }
    .btn-add-row:hover{ background:#e4e4e4; color:#111; }
    .btn-remove-row{ background:none; border:none; color:#ccc; font-size:1.1rem; cursor:pointer; padding:4px 8px; }
    .btn-remove-row:hover{ color:#b91c1c; }
    .importe-cell{ font-weight:700; text-align:right; min-width:90px; }

    .totals-box{ background:#fafafa; border:1px solid #e8e8e8; border-radius:10px; padding:18px 24px; margin-top:22px; display:flex; flex-direction:column; align-items:flex-end; gap:10px; }
    .t-row{ display:flex; gap:60px; font-size:.9rem; min-width:280px; justify-content:space-between; }
    .t-row span:last-child{ font-weight:700; }
    .t-total{ font-size:1.15rem; border-top:1px solid #ddd; padding-top:10px; }

    .form-actions{ margin-top:28px; display:flex; gap:12px; justify-content:flex-end; }
    .btn-submit{ background:#111; color:#fff; border:none; padding:11px 28px; border-radius:7px; font-size:.88rem; font-weight:600; cursor:pointer; }
    .btn-submit:hover{ background:#333; }
    .btn-back{ display:inline-flex; align-items:center; gap:6px; color:#666; text-decoration:none; font-size:.9rem; margin-bottom:18px; }
    .btn-back:hover{ color:#111; }
    .text-danger{ color:#dc3545; font-size:.82rem; margin-top:4px; display:block; }
    .alert-error{ background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:12px 16px; color:#b91c1c; font-size:.88rem; margin-bottom:20px; }
    .pu-hint{ font-size:.78rem; color:#aaa; margin-top:3px; }
</style>

<div class="pu-form-wrap">
    <a href="{{ route('presupuestos.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver al listado</a>

    @if($errors->any())
        <div class="alert-error"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="pu-panel">
        <div class="pu-header"><h1>Nuevo Presupuesto</h1></div>

        <form action="{{ route('presupuestos.store') }}" method="POST" id="formPresupuesto">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label for="proyecto_id">Proyecto *</label>
                    <select id="proyecto_id" name="proyecto_id" class="form-select" required>
                        <option value="">— Seleccione —</option>
                        @foreach($proyectos as $p)
                            <option value="{{ $p->id }}" {{ old('proyecto_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                    @error('proyecto_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha del Presupuesto *</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" value="{{ old('fecha', now()->format('Y-m-d')) }}" required>
                    @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre del Presupuesto *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required maxlength="150" placeholder="Ej. Presupuesto Base Fase 1">
                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones" class="form-control">{{ old('observaciones') }}</textarea>
            </div>

            {{-- RENGLONES --}}
            <div class="section-title"><i class="bi bi-list-columns me-1"></i> Renglones del Presupuesto</div>
            <p class="pu-hint mb-3">Solo se muestran conceptos que ya tienen un Análisis P.U. definido.</p>

            <table class="insumos-table">
                <thead><tr>
                    <th style="width:42%">Concepto</th>
                    <th style="width:16%">Unidad</th>
                    <th style="width:16%">Cantidad</th>
                    <th style="width:18%">P.U. (snap)</th>
                    <th style="width:14%">Importe</th>
                    <th style="width:4%"></th>
                </tr></thead>
                <tbody id="bodyDetalles">
                    <tr class="fila-detalle">
                        <td><select name="detalles[0][concepto_id]" class="form-select sel-concepto">
                            <option value="">— Concepto —</option>
                            @foreach($conceptos as $c)
                                <option value="{{ $c->id }}"
                                    data-unidad="{{ $c->unidadMedida->abreviatura ?? '' }}"
                                    data-pu="{{ $c->analisisPu->costo_total ?? 0 }}">
                                    {{ $c->clave }} — {{ Str::limit($c->descripcion, 55) }}
                                </option>
                            @endforeach
                        </select></td>
                        <td><input type="text" class="form-control lbl-unidad" readonly placeholder="—" tabindex="-1"></td>
                        <td><input type="number" step="0.0001" min="0" name="detalles[0][cantidad]" class="form-control inp-cantidad" placeholder="0.0000"></td>
                        <td><input type="number" step="0.0001" min="0" name="detalles[0][pu_unitario_snapshot]" class="form-control inp-pu" placeholder="0.0000"></td>
                        <td class="importe-cell">$<span class="lbl-importe">0.00</span></td>
                        <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn-add-row mt-2" id="btnAgregarRenglon"><i class="bi bi-plus"></i> Agregar renglón</button>

            <div class="totals-box">
                <div class="t-row t-total"><span><strong>Total Presupuesto:</strong></span><span><strong>$<span id="totalPresupuesto">0.00</span></strong></span></div>
            </div>

            <div class="form-actions">
                <a href="{{ route('presupuestos.index') }}" class="btn-submit" style="background:#888;text-decoration:none;">Cancelar</a>
                <button type="submit" class="btn-submit">Guardar Presupuesto</button>
            </div>
        </form>
    </div>
</div>

<script>
const catConceptos = @json($conceptos->map(fn($c) => [
    'id'     => $c->id,
    'label'  => $c->clave.' — '.Str::limit($c->descripcion, 55),
    'unidad' => $c->unidadMedida->abreviatura ?? '',
    'pu'     => $c->analisisPu->costo_total ?? 0,
]));

let rowIdx = 1;

function buildConceptoOptions() {
    return catConceptos.map(c => `<option value="${c.id}" data-unidad="${c.unidad}" data-pu="${c.pu}">${c.label}</option>`).join('');
}

document.getElementById('btnAgregarRenglon').addEventListener('click', () => {
    const tbody = document.getElementById('bodyDetalles');
    const html = `<tr class="fila-detalle">
        <td><select name="detalles[${rowIdx}][concepto_id]" class="form-select sel-concepto"><option value="">— Concepto —</option>${buildConceptoOptions()}</select></td>
        <td><input type="text" class="form-control lbl-unidad" readonly placeholder="—" tabindex="-1"></td>
        <td><input type="number" step="0.0001" min="0" name="detalles[${rowIdx}][cantidad]" class="form-control inp-cantidad" placeholder="0.0000"></td>
        <td><input type="number" step="0.0001" min="0" name="detalles[${rowIdx}][pu_unitario_snapshot]" class="form-control inp-pu" placeholder="0.0000"></td>
        <td class="importe-cell">$<span class="lbl-importe">0.00</span></td>
        <td><button type="button" class="btn-remove-row" onclick="eliminarFila(this)"><i class="bi bi-trash3"></i></button></td>
    </tr>`;
    tbody.insertAdjacentHTML('beforeend', html);
    bindFila(tbody.lastElementChild);
    rowIdx++;
});

function eliminarFila(btn) {
    btn.closest('tr').remove();
    recalcularTotal();
}

function bindFila(row) {
    const sel    = row.querySelector('.sel-concepto');
    const inpPu  = row.querySelector('.inp-pu');
    const inpCant= row.querySelector('.inp-cantidad');
    const lblUni = row.querySelector('.lbl-unidad');

    sel.addEventListener('change', () => {
        const opt = sel.options[sel.selectedIndex];
        lblUni.value = opt.dataset.unidad || '';
        if (inpPu && !inpPu.value) inpPu.value = parseFloat(opt.dataset.pu || 0).toFixed(4);
        actualizarFila(row); recalcularTotal();
    });
    [inpCant, inpPu].forEach(inp => inp?.addEventListener('input', () => { actualizarFila(row); recalcularTotal(); }));
}

function actualizarFila(row) {
    const cant = parseFloat(row.querySelector('.inp-cantidad')?.value || 0);
    const pu   = parseFloat(row.querySelector('.inp-pu')?.value || 0);
    const lbl  = row.querySelector('.lbl-importe');
    if (lbl) lbl.textContent = (cant * pu).toFixed(2);
}

function recalcularTotal() {
    let total = 0;
    document.querySelectorAll('#bodyDetalles .lbl-importe').forEach(lbl => { total += parseFloat(lbl.textContent || 0); });
    document.getElementById('totalPresupuesto').textContent = total.toFixed(2);
}

// Bindear fila inicial
document.querySelectorAll('#bodyDetalles .fila-detalle').forEach(bindFila);
</script>
@endsection