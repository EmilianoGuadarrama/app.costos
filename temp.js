
/* ─────────── DATOS DEL SERVIDOR ─────────── */


const catConceptos  = [];
const catMateriales = [];
const catMaquinaria = [];
const catManoObra   = [];
const catAreas      = [];
const catBloques    = [];

const bloques   = [];
const unidades  = [];
const niveles   = [];
const storeUrl  = '1';
const csrfToken = '1';

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

        1
        <div class="cpt-hdr">
            <h3><i class="bi bi-layers-fill" style="color:#60a5fa;"></i> Concepto #${ci}</h3>
            <button class="btn-quitar" onclick="document.getElementById('card_c_${ci}').remove(); updateGlobalTotals();">
                <i class="bi bi-trash3"></i> Quitar
            </button>
        </div>

        1
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

        1
        <div class="ins-wrap">

            1
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

            1
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

            1
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

            1
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
                id_unidad_medida: cUni,
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
