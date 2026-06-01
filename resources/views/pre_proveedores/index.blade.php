@extends('layout')
@section('title', 'Presupuestos de Proveedores')

@section('content')
<style>
    .dash-index-view { min-height: 100%; background: #f8f8f8; color: #111; padding: 20px; font-family: Arial, sans-serif; }
    .index-panel { background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,.05); }
    .header-section { border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; }
    .header-section h1 { font-size: 2.2rem; font-weight: 700; margin: 0; }
    .header-section p { margin: 6px 0 0; color: #666; font-size: .92rem; }
    .btn-add-new { background: #111; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-size: .8rem; font-weight: 700; text-transform: uppercase; text-decoration: none; cursor: pointer; }
    .btn-add-new:hover { background: #333; color: #fff; }
    
    .project-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .project-table th { background: #f1f5f9; padding: 12px 15px; text-align: left; font-size: .8rem; color: #475569; text-transform: uppercase; border-bottom: 2px solid #cbd5e1; }
    .project-table td { padding: 12px 15px; font-size: .9rem; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
    .project-table tr:hover { background: #f8fafc; }
    
    .badge-estado { padding: 4px 8px; border-radius: 4px; font-size: .75rem; font-weight: 700; text-transform: uppercase; }
    .estado-pendiente { background: #fef3c7; color: #d97706; }
    .estado-aprobado { background: #dcfce7; color: #15803d; }
    
    .btn-action { color: #fff; border: none; padding: 5px 10px; border-radius: 4px; font-size: .75rem; font-weight: bold; cursor: pointer; margin-bottom: 2px; display: inline-block;}
    .btn-aprobar { background: #059669; }
    .btn-pago { background: #2563eb; }
    .btn-extras { background: #f59e0b; }
    .btn-danger { background: #dc2626; }
    .btn-restore { background: #8b5cf6; }
    .text-right { text-align: right !important; }

    .nav-tabs .nav-link { color: #475569; font-weight: 600; }
    .nav-tabs .nav-link.active { color: #111; border-bottom: 2px solid #111; }
</style>

<div class="dash-index-view">
    <div class="index-panel">
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="header-section">
            <div>
                <h1>Presupuestos de Proveedores</h1>
                <p>Gestiona los presupuestos que te envían los proveedores, apruébalos y registra sus pagos.</p>
            </div>
            <div>
                <button class="btn-add-new" data-bs-toggle="modal" data-bs-target="#modalNuevoPresupuesto">
                    <i class="bi bi-plus-lg"></i> Nuevo Presupuesto
                </button>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" id="presupuestosTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button" role="tab">Pendientes ({{ $pendientes->count() }})</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="aprobados-tab" data-bs-toggle="tab" data-bs-target="#aprobados" type="button" role="tab">Aprobados ({{ $aprobados->count() }})</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="finalizados-tab" data-bs-toggle="tab" data-bs-target="#finalizados" type="button" role="tab">Finalizados ({{ $finalizados->count() }})</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="papelera-tab" data-bs-toggle="tab" data-bs-target="#papelera" type="button" role="tab">Papelera ({{ $papelera->count() }})</button>
            </li>
        </ul>

        <div class="tab-content" id="presupuestosTabsContent">
            
            <!-- PENDIENTES -->
            <div class="tab-pane fade show active" id="pendientes" role="tabpanel">
                <div class="table-responsive">
                    <table class="project-table">
                        <thead>
                            <tr>
                                <th>Responsable</th>
                                <th>Área / Obra</th>
                                <th class="text-right">Presupuesto</th>
                                <th class="text-right">Extras</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Pagado</th>
                                <th class="text-right">Saldo</th>
                                <th class="text-right">%</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendientes as $p)
                                @include('pre_proveedores.partials.row', ['p' => $p, 'type' => 'pendiente'])
                            @empty
                                <tr><td colspan="9" class="text-center py-4 text-muted">No hay presupuestos pendientes.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- APROBADOS -->
            <div class="tab-pane fade" id="aprobados" role="tabpanel">
                <div class="table-responsive">
                    <table class="project-table">
                        <thead>
                            <tr>
                                <th>Responsable</th>
                                <th>Área / Obra</th>
                                <th class="text-right">Presupuesto</th>
                                <th class="text-right">Extras</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Pagado</th>
                                <th class="text-right">Saldo</th>
                                <th class="text-right">%</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aprobados as $p)
                                @include('pre_proveedores.partials.row', ['p' => $p, 'type' => 'aprobado'])
                            @empty
                                <tr><td colspan="9" class="text-center py-4 text-muted">No hay presupuestos aprobados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FINALIZADOS -->
            <div class="tab-pane fade" id="finalizados" role="tabpanel">
                <div class="table-responsive">
                    <table class="project-table">
                        <thead>
                            <tr>
                                <th>Responsable</th>
                                <th>Área / Obra</th>
                                <th class="text-right">Presupuesto</th>
                                <th class="text-right">Extras</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Pagado</th>
                                <th class="text-right">Saldo</th>
                                <th class="text-right">%</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($finalizados as $p)
                                @include('pre_proveedores.partials.row', ['p' => $p, 'type' => 'finalizado'])
                            @empty
                                <tr><td colspan="9" class="text-center py-4 text-muted">No hay presupuestos finalizados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PAPELERA -->
            <div class="tab-pane fade" id="papelera" role="tabpanel">
                <div class="table-responsive">
                    <table class="project-table">
                        <thead>
                            <tr>
                                <th>Responsable</th>
                                <th>Área / Obra</th>
                                <th class="text-right">Presupuesto</th>
                                <th class="text-right">Extras</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Pagado</th>
                                <th class="text-right">Saldo</th>
                                <th class="text-right">%</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($papelera as $p)
                                @include('pre_proveedores.partials.row', ['p' => $p, 'type' => 'papelera'])
                            @empty
                                <tr><td colspan="9" class="text-center py-4 text-muted">La papelera está vacía.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Nuevo Presupuesto -->
<div class="modal fade" id="modalNuevoPresupuesto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Presupuesto de Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pre_proveedores.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Proveedor Responsable *</label>
                            <div class="input-group">
                                <select name="id_proveedor" id="id_proveedor" class="form-select" required onchange="checkNuevoSelect(this, '#modalCrearProveedor')">
                                    <option value="">Selecciona un proveedor...</option>
                                    <option value="nuevo" class="fw-bold text-primary">+ Añadir nuevo proveedor...</option>
                                    @foreach($proveedores as $prov)
                                        <option value="{{ $prov->id }}">{{ $prov->empresa }} - {{ $prov->persona->nombre ?? '' }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearProveedor"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Área *</label>
                            <div class="input-group">
                                <select name="id_area" id="id_area" class="form-select" required onchange="checkNuevoSelect(this, '#modalCrearArea')">
                                    <option value="">Selecciona un área...</option>
                                    <option value="nuevo" class="fw-bold text-primary">+ Añadir nueva área...</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->abreviatura }} - {{ $area->descripcion }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearArea"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Asignar a Obra (Opcional)</label>
                            <select name="id_obra" id="id_obra_presupuesto" class="form-select">
                                <option value="">-- EGRESOS GENERALES (Sin obra) --</option>
                                @foreach($obras as $obra)
                                    <option value="{{ $obra->id }}">{{ $obra->datosDeObra->nombre }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Si lo dejas en blanco, los pagos se reflejarán como un Egreso General. Solo se muestran obras en proceso.</small>
                        </div>
                    </div>

                    <div class="row mb-3" id="materiales_pendientes_row" style="display: none;">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Materiales Pendientes de la Obra</label>
                            <div class="p-3 border rounded" style="background: #fafafa; max-height: 200px; overflow-y: auto;" id="materiales_pendientes_container">
                                <!-- Checkboxes will be injected here via JS -->
                            </div>
                            <small class="text-muted d-block mt-1">Selecciona los materiales que están incluidos en el presupuesto de este proveedor. Sus costos se registrarán como cubiertos por el proveedor.</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Presupuesto ($) *</label>
                            <input type="number" step="0.01" name="presupuesto" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Extras ($) (Opcional)</label>
                            <input type="number" step="0.01" name="extras" class="form-control" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Guardar Presupuesto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Creación Rápida Proveedor -->
<div class="modal fade" id="modalCrearProveedor" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Proveedor Rápido</h5>
                <button type="button" class="btn-close" data-bs-target="#modalNuevoPresupuesto" data-bs-toggle="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>Empresa</label>
                    <input type="text" id="quick_prov_empresa" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Nombre Encargado (Opcional)</label>
                    <input type="text" id="quick_prov_nombre" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Email (Opcional)</label>
                    <input type="email" id="quick_prov_email" class="form-control">
                </div>
                <button type="button" class="btn btn-primary w-100 mt-2" onclick="crearProveedorRapido()">Crear Proveedor</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Creación Rápida Area -->
<div class="modal fade" id="modalCrearArea" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Área Rápida</h5>
                <button type="button" class="btn-close" data-bs-target="#modalNuevoPresupuesto" data-bs-toggle="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>Abreviatura</label>
                    <input type="text" id="quick_area_abrev" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Descripción</label>
                    <input type="text" id="quick_area_desc" class="form-control">
                </div>
                <button type="button" class="btn btn-primary w-100 mt-2" onclick="crearAreaRapida()">Crear Área</button>
            </div>
        </div>
    </div>
</div>

<script>
    function crearProveedorRapido() {
        let empresa = document.getElementById('quick_prov_empresa').value;
        let nombre = document.getElementById('quick_prov_nombre').value;
        let email = document.getElementById('quick_prov_email').value;
        if(!empresa) { alert("Empresa es obligatoria"); return; }
        
        fetch('{{ route("api.proveedores.storeRapida") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ empresa: empresa, nombre: nombre, email: email })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                let select = document.getElementById('id_proveedor');
                let option = new Option(data.text, data.id, true, true);
                select.add(option);
                // Return to main modal
                var myModalEl = document.getElementById('modalCrearProveedor');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
                var mainModal = new bootstrap.Modal(document.getElementById('modalNuevoPresupuesto'));
                mainModal.show();
            } else {
                alert("Error: " + data.message);
            }
        });
    }

    function crearAreaRapida() {
        let abrev = document.getElementById('quick_area_abrev').value;
        let desc = document.getElementById('quick_area_desc').value;
        if(!abrev || !desc) { alert("Llena todos los campos"); return; }
        
        fetch('{{ route("api.areas.storeRapida") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ abreviatura: abrev, descripcion: desc })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                let select = document.getElementById('id_area');
                let option = new Option(data.text, data.id, true, true);
                select.add(option);
                var myModalEl = document.getElementById('modalCrearArea');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
                var mainModal = new bootstrap.Modal(document.getElementById('modalNuevoPresupuesto'));
                mainModal.show();
            } else {
                alert("Error al crear área");
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const obraSelect = document.getElementById('id_obra_presupuesto');
        const matRow = document.getElementById('materiales_pendientes_row');
        const matContainer = document.getElementById('materiales_pendientes_container');

        obraSelect.addEventListener('change', function() {
            let id_obra = this.value;
            if(!id_obra) {
                matRow.style.display = 'none';
                matContainer.innerHTML = '';
                return;
            }

            matRow.style.display = 'block';
            matContainer.innerHTML = '<div class="text-center text-muted"><i class="bi bi-arrow-repeat spin"></i> Cargando materiales pendientes...</div>';

            fetch(`/api/obras/${id_obra}/materiales-pendientes`)
                .then(r => r.json())
                .then(data => {
                    if(data.success && data.materiales.length > 0) {
                        let html = '';
                        // Opcion de sin material (no hace falta checkbox, simplemente no seleccionar nada, pero lo ponemos visual para claridad)
                        html += `
                        <div class="form-check mb-2" style="border-bottom: 1px solid #ddd; padding-bottom: 8px;">
                            <input class="form-check-input" type="radio" name="opcion_materiales" id="sin_materiales" value="no" checked onchange="toggleCheckboxes(false)">
                            <label class="form-check-label fw-bold" for="sin_materiales">
                                Sin materiales (o no seleccionar ninguno)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="opcion_materiales" id="con_materiales" value="si" onchange="toggleCheckboxes(true)">
                            <label class="form-check-label fw-bold" for="con_materiales">
                                Incluir los siguientes materiales:
                            </label>
                        </div>
                        <div id="materiales_checkboxes" style="padding-left: 20px; opacity: 0.5; pointer-events: none;">
                        `;

                        data.materiales.forEach(mat => {
                            html += `
                            <div class="d-flex align-items-center mb-2 form-check">
                                <input class="form-check-input mat-cb me-2" type="checkbox" id="mat_chk_${mat.id_material}" onchange="toggleMatInput(this, ${mat.id_material})">
                                <label class="form-check-label flex-grow-1" for="mat_chk_${mat.id_material}">
                                    ${mat.nombre} <span class="badge bg-secondary ms-1">Faltan: ${mat.faltante} ${mat.unidad}</span>
                                </label>
                                <div style="width: 140px;" class="ms-2">
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" min="0.01" max="${mat.faltante}" class="form-control" name="materiales_incluidos[${mat.id_material}]" id="mat_qty_${mat.id_material}" value="${mat.faltante}" disabled>
                                        <span class="input-group-text">${mat.unidad}</span>
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                        html += '</div>';
                        matContainer.innerHTML = html;
                    } else {
                        matContainer.innerHTML = '<div class="text-muted"><i class="bi bi-info-circle"></i> No hay materiales pendientes para esta obra.</div>';
                    }
                })
                .catch(err => {
                    matContainer.innerHTML = '<div class="text-danger">Error al cargar materiales.</div>';
                });
        });
    });

    function toggleCheckboxes(enable) {
        let container = document.getElementById('materiales_checkboxes');
        let checkboxes = document.querySelectorAll('.mat-cb');
        if(enable) {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        } else {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
            checkboxes.forEach(cb => {
                if(cb.checked) {
                    cb.checked = false;
                    toggleMatInput(cb, cb.id.replace('mat_chk_', ''));
                }
            });
        }
    }

    function toggleMatInput(checkbox, matId) {
        let input = document.getElementById('mat_qty_' + matId);
        if (checkbox.checked) {
            input.disabled = false;
        } else {
            input.disabled = true;
        }
    }

    function checkNuevoSelect(selectEl, modalSelector) {
        if (selectEl.value === 'nuevo') {
            selectEl.value = ''; // Reset select
            var mainModal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoPresupuesto'));
            if(mainModal) mainModal.hide();
            
            var newModal = new bootstrap.Modal(document.querySelector(modalSelector));
            newModal.show();
        }
    }
    
    // Carga inicial en caso de que el navegador recupere el valor preseleccionado
    if (document.getElementById('id_obra_presupuesto') && document.getElementById('id_obra_presupuesto').value) {
        document.getElementById('id_obra_presupuesto').dispatchEvent(new Event('change'));
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let hash = window.location.hash;
        if (hash) {
            let tabBtn = document.querySelector('button[data-bs-target="' + hash + '"]');
            if (tabBtn) {
                let tab = new bootstrap.Tab(tabBtn);
                tab.show();
            }
        }
        
        let tabElements = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabElements.forEach(function(el) {
            el.addEventListener('shown.bs.tab', function(e) {
                window.location.hash = e.target.getAttribute('data-bs-target');
            });
        });
    });
</script>
@endsection
