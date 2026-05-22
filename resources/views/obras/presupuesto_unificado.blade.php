@extends('layout')
@section('title', 'Agregar al Presupuesto — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))

@section('content')
<style>
/* Estilos extra mínimos para el autocomplete y listados */
.ac-wrap { position: relative; }
.ac-list {
    position: absolute; top: 100%; left: 0; right: 0;
    background: #fff; border: 1px solid #dee2e6; max-height: 200px;
    overflow-y: auto; z-index: 1050; display: none; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    border-radius: 0.375rem;
}
.ac-item { padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #f8f9fa; font-size: 0.9rem; color: #495057; }
.ac-item:hover { background: #f1f3f5; color: #212529; }
.ac-item.nuevo { color: #0d6efd; font-weight: 600; background: #f8f9fa; }
.concepto-card { margin-bottom: 2rem; border-radius: 0.75rem; overflow: hidden; border: 1px solid #e9ecef; }
.card-header-soft { background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; color: #495057; }
.table-soft th { color: #6c757d; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; border-bottom: 2px solid #dee2e6; }
.table-soft td { vertical-align: middle; border-bottom: 1px solid #f8f9fa; }
.insumo-section { background-color: #ffffff; border: 1px solid #e9ecef; border-radius: 0.5rem; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
.insumo-header { background-color: #f8f9fa; padding: 0.75rem 1rem; border-bottom: 1px solid #e9ecef; border-radius: 0.5rem 0.5rem 0 0; display: flex; justify-content: space-between; align-items: center; }
</style>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <a href="{{ route('obras.presupuesto', $obra->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                <i class="bi bi-arrow-left"></i> Volver al Presupuesto
            </a>
            <h2 class="mb-1 text-primary fw-bold">Agregar Matrices al Presupuesto</h2>
            <p class="text-muted mb-0">Agrega conceptos y desglosa sus insumos para <strong>{{ $obra->datosDeObra?->nombre }}</strong>.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm" onclick="guardarPresupuesto()" id="btnGuardar">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar Todo
            </button>
        </div>
    </div>

    <!-- Ajustes Globales -->
    <div class="card mb-4 shadow-sm border-0 rounded-4 bg-white">
        <div class="card-body p-4">
            <h5 class="card-title text-primary mb-3 fw-bold"><i class="bi bi-gear-fill me-2"></i>Ajustes Globales</h5>
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted">Bloque por defecto</label>
                    <select id="g_bloque" class="form-select form-select-lg border-light bg-light">
                        <option value="">— Seleccionar Bloque —</option>
                        @foreach($bloques as $b)
                            <option value="{{ $b->id }}">{{ $b->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted">Área por defecto</label>
                    <select id="g_area" class="form-select form-select-lg border-light bg-light">
                        <option value="">— Seleccionar Área —</option>
                        @foreach($areas as $a)
                            <option value="{{ $a->id }}">{{ $a->abreviatura }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted">Nivel/Planta por defecto</label>
                    <select id="g_nivel" class="form-select form-select-lg border-light bg-light">
                        <option value="">— Seleccionar Planta —</option>
                        @foreach($niveles as $n)
                            <option value="{{ $n->id }}">{{ $n->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <button type="button" class="btn btn-outline-primary rounded-pill px-4" onclick="addConcepto()">
            <i class="bi bi-plus-lg me-1"></i> Agregar Nuevo Concepto
        </button>
    </div>

    <div id="conceptosContainer"></div>

</div>

<script>
@php
    $catConceptosArr = $conceptos->map(fn($c) => ['id'=>$c->id,'texto'=>$c->descripcion,'pu'=>$c->p_u,'uni'=>$c->id_unidad_medida])->values()->toJson();
    $catMaterialesArr = $materiales->map(fn($m) => ['id'=>$m->id,'texto'=>$m->nombre,'pu'=>$m->precio_x_unidad,'uni'=>$m->id_unidad_medida])->values()->toJson();
    $catMaquinariaArr = $maquinaria->map(fn($m) => ['id'=>$m->id,'texto'=>$m->nombre,'pu'=>$m->precio_x_unidad,'uni'=>$m->id_unidad_medida])->values()->toJson();
    $catManoObraArr = $mano_obra->map(fn($m) => ['id'=>$m->id,'texto'=>$m->nombre,'pu'=>$m->precio_x_unidad,'uni'=>$m->id_unidad_medida])->values()->toJson();
@endphp

const catConceptos = {!! $catConceptosArr !!};
const catMateriales = {!! $catMaterialesArr !!};
const catMaquinaria = {!! $catMaquinariaArr !!};
const catManoObra = {!! $catManoObraArr !!};

const bloques = @json($bloques);
const unidades = @json($unidades);
const niveles = @json($niveles);

let conceptIndex = 0;

function optsBloques() {
    return bloques.map(b => `<option value="${b.id}">${b.descripcion}</option>`).join('');
}

function optsUnidades() {
    return unidades.map(u => `<option value="${u.id}">${u.abreviatura}</option>`).join('');
}

function optsNiveles() {
    return niveles.map(n => `<option value="${n.id}">${n.descripcion}</option>`).join(''); // ¡Corregido a n.descripcion!
}

function addConcepto() {
    conceptIndex++;
    let ci = conceptIndex;
    let bVal = document.getElementById('g_bloque').value;
    let nVal = document.getElementById('g_nivel').value;

    let html = `
    <div class="card concepto-card shadow-sm" id="card_c_${ci}" data-ci="${ci}">
        <div class="card-header card-header-soft py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-layers text-secondary me-2"></i>Concepto #${ci}</h5>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="document.getElementById('card_c_${ci}').remove()">
                <i class="bi bi-trash3"></i> Quitar
            </button>
        </div>
        <div class="card-body p-4 bg-white">
            <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Descripción del Concepto</label>
                    <div class="ac-wrap">
                        <input type="text" class="form-control c-desc border-0 shadow-sm" id="c_txt_${ci}" placeholder="Buscar o crear..." autocomplete="off">
                        <input type="hidden" class="c-id" id="c_id_${ci}">
                        <div class="ac-list" id="c_list_${ci}"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Unidad</label>
                    <select class="form-select c-uni border-0 shadow-sm"><option value="">N/A</option>${optsUnidades()}</select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Nivel / Planta</label>
                    <select class="form-select c-nivel border-0 shadow-sm"><option value="">N/A</option>${optsNiveles()}</select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Bloque</label>
                    <select class="form-select c-bloque border-0 shadow-sm"><option value="">N/A</option>${optsBloques()}</select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small fw-semibold text-muted">Cant.</label>
                    <input type="number" class="form-control c-cant border-0 shadow-sm" value="1" min="0.01" step="0.01">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">P.U. Manual</label>
                    <input type="number" class="form-control c-pu border-0 shadow-sm" value="0" min="0" step="0.01" placeholder="$0.00">
                </div>
            </div>

            <!-- INSUMOS HACIA ABAJO -->
            <div class="d-flex flex-column gap-3">
                
                <!-- Materiales -->
                <div class="insumo-section">
                    <div class="insumo-header">
                        <span class="fw-bold text-primary"><i class="bi bi-box-seam me-2"></i>Materiales</span>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="addInsumo(${ci}, 'material')">
                            <i class="bi bi-plus"></i> Agregar Material
                        </button>
                    </div>
                    <div class="p-2">
                        <table class="table table-soft table-borderless m-0 w-100" id="tb_mat_${ci}">
                            <thead>
                                <tr>
                                    <th style="width: 50%;">Insumo (Buscar o Crear)</th>
                                    <th style="width: 15%;">Unidad</th>
                                    <th style="width: 15%;">Cantidad</th>
                                    <th style="width: 15%;">Precio Unitario</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Mano de Obra -->
                <div class="insumo-section">
                    <div class="insumo-header">
                        <span class="fw-bold text-success"><i class="bi bi-person-lines-fill me-2"></i>Mano de Obra</span>
                        <button type="button" class="btn btn-sm btn-success rounded-pill px-3" onclick="addInsumo(${ci}, 'mano_obra')">
                            <i class="bi bi-plus"></i> Agregar Mano de Obra
                        </button>
                    </div>
                    <div class="p-2">
                        <table class="table table-soft table-borderless m-0 w-100" id="tb_mo_${ci}">
                            <thead>
                                <tr>
                                    <th style="width: 50%;">Insumo (Buscar o Crear)</th>
                                    <th style="width: 15%;">Unidad</th>
                                    <th style="width: 15%;">Cantidad/Rendimiento</th>
                                    <th style="width: 15%;">Precio Unitario</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Maquinaria -->
                <div class="insumo-section">
                    <div class="insumo-header">
                        <span class="fw-bold text-info text-darken"><i class="bi bi-truck me-2"></i>Maquinaria</span>
                        <button type="button" class="btn btn-sm btn-info rounded-pill px-3 text-white" style="background-color: #0dcaf0; border-color: #0dcaf0;" onclick="addInsumo(${ci}, 'maquinaria')">
                            <i class="bi bi-plus"></i> Agregar Maquinaria
                        </button>
                    </div>
                    <div class="p-2">
                        <table class="table table-soft table-borderless m-0 w-100" id="tb_maq_${ci}">
                            <thead>
                                <tr>
                                    <th style="width: 50%;">Insumo (Buscar o Crear)</th>
                                    <th style="width: 15%;">Unidad</th>
                                    <th style="width: 15%;">Cantidad/Rendimiento</th>
                                    <th style="width: 15%;">Precio Unitario</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>`;
    
    document.getElementById('conceptosContainer').insertAdjacentHTML('beforeend', html);
    let card = document.getElementById(`card_c_${ci}`);
    if(bVal) card.querySelector('.c-bloque').value = bVal;
    if(nVal) card.querySelector('.c-nivel').value = nVal;

    setupAC(document.getElementById(`c_txt_${ci}`), document.getElementById(`c_id_${ci}`), document.getElementById(`c_list_${ci}`), catConceptos, true, card.querySelector('.c-pu'), card.querySelector('.c-uni'));
}

function addInsumo(ci, tipo) {
    let tbody = document.getElementById(`tb_${tipo==='material'?'mat':tipo==='maquinaria'?'maq':'mo'}_${ci}`).querySelector('tbody');
    let ii = Date.now() + Math.floor(Math.random()*1000);
    
    let html = `
    <tr data-tipo="${tipo}">
        <td>
            <div class="ac-wrap">
                <input type="text" class="form-control form-control-sm i-txt bg-light border-0" id="i_txt_${ii}" placeholder="Escribe para buscar..." autocomplete="off">
                <input type="hidden" class="i-id" id="i_id_${ii}">
                <div class="ac-list" id="i_list_${ii}"></div>
            </div>
        </td>
        <td>
            <select class="form-select form-select-sm i-uni bg-light border-0"><option value="">(N/A)</option>${optsUnidades()}</select>
        </td>
        <td><input type="number" class="form-control form-control-sm i-cant bg-light border-0" placeholder="0.00" value="1" min="0.01" step="0.01"></td>
        <td><input type="number" class="form-control form-control-sm i-pu bg-light border-0" placeholder="$0.00" value="0" min="0" step="0.01"></td>
        <td class="text-end">
            <button type="button" class="btn btn-sm btn-light text-danger" onclick="this.closest('tr').remove()"><i class="bi bi-x-circle-fill"></i></button>
        </td>
    </tr>`;
    
    tbody.insertAdjacentHTML('beforeend', html);

    let cat = tipo==='material' ? catMateriales : tipo==='maquinaria' ? catMaquinaria : catManoObra;
    setupAC(document.getElementById(`i_txt_${ii}`), document.getElementById(`i_id_${ii}`), document.getElementById(`i_list_${ii}`), cat, false, document.getElementById(`i_txt_${ii}`).closest('tr').querySelector('.i-pu'), document.getElementById(`i_txt_${ii}`).closest('tr').querySelector('.i-uni'));
}

function setupAC(inp, idFld, list, catArray, isConcept, puFld, uniFld) {
    inp.addEventListener('input', function() {
        let q = this.value.toLowerCase();
        let filtered = catArray.filter(c => c.texto.toLowerCase().includes(q)).slice(0,10);
        list.innerHTML = '';
        
        filtered.forEach(c => {
            let div = document.createElement('div');
            div.className = 'ac-item';
            div.textContent = c.texto;
            div.onclick = () => {
                inp.value = c.texto;
                idFld.value = c.id;
                if(puFld && c.pu) puFld.value = c.pu;
                if(uniFld && c.uni) uniFld.value = c.uni;
                list.style.display = 'none';
            };
            list.appendChild(div);
        });

        let divNuevo = document.createElement('div');
        divNuevo.className = 'ac-item nuevo';
        divNuevo.innerHTML = '<i class="bi bi-plus-circle me-1"></i> Usar como registro nuevo';
        divNuevo.onclick = () => {
            idFld.value = '';
            list.style.display = 'none';
        };
        list.appendChild(divNuevo);

        list.style.display = 'block';
    });

    document.addEventListener('click', e => { if(e.target !== inp) list.style.display='none'; });
}

async function guardarPresupuesto() {
    let payload = { conceptos: [] };
    let areaGlobal = document.getElementById('g_area').value;
    
    document.querySelectorAll('.concepto-card').forEach(card => {
        let cId = card.querySelector('.c-id').value;
        let cDesc = card.querySelector('.c-desc').value;
        if(!cId && !cDesc) return;

        let conceptoData = {
            id_concepto: cId,
            nombre_nuevo: cId ? '' : cDesc,
            id_unidad_medida: card.querySelector('.c-uni').value,
            id_bloque: card.querySelector('.c-bloque').value,
            id_nivel: card.querySelector('.c-nivel').value,
            id_area: areaGlobal,
            cantidad: card.querySelector('.c-cant').value,
            precio_unitario: card.querySelector('.c-pu').value,
            materiales: [], maquinaria: [], mano_obra: []
        };

        ['material','maquinaria','mano_obra'].forEach(tipo => {
            let tbodyId = tipo==='material'?'mat':tipo==='maquinaria'?'maq':'mo';
            card.querySelectorAll(`#tb_${tbodyId}_${card.dataset.ci} tbody tr`).forEach(tr => {
                let iId = tr.querySelector('.i-id').value;
                let iTxt = tr.querySelector('.i-txt').value;
                if(!iId && !iTxt) return;
                
                conceptoData[tipo].push({
                    [`id_${tipo}`]: iId,
                    nombre_nuevo: iId ? '' : iTxt,
                    id_unidad_medida: tr.querySelector('.i-uni').value,
                    cantidad: tr.querySelector('.i-cant').value,
                    precio_unitario: tr.querySelector('.i-pu').value
                });
            });
        });

        payload.conceptos.push(conceptoData);
    });

    if(payload.conceptos.length === 0) return alert('Agrega al menos un concepto');
    
    let btn = document.getElementById('btnGuardar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';

    try {
        let res = await fetch(`{{ route('obras.presupuesto.unificado.store', $obra->id) }}`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify(payload)
        });
        
        let json = await res.json();
        
        if(res.ok && json.success) {
            window.location.href = json.redirect;
        } else {
            alert('Error: ' + (json.message || 'Error al guardar los datos'));
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar Todo';
        }
    } catch (e) {
        console.error(e);
        alert('Error de red o comunicación con el servidor.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar Todo';
    }
}

// Agregar un concepto inicial vacío para facilitar
document.addEventListener('DOMContentLoaded', () => {
    addConcepto();
});
</script>
@endsection
