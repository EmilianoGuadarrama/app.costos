@extends('layout')
@section('title', 'Nueva Obra — App Costos')
@section('content')
<style>
.form-wrap { max-width: 860px; margin: 0 auto; }
.form-title { font-size: 1.7rem; font-weight: 800; color: #111; margin: 0 0 4px; }
.form-sub   { color: #6b7280; font-size: .9rem; margin: 0 0 28px; }

.btn-back   { color: #6b7280; text-decoration: none; font-size: .85rem;
              display: inline-flex; align-items: center; gap: 5px; margin-bottom: 18px; }
.btn-back:hover { color: #111; }

/* Secciones */
.fs-card {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 16px;
    padding: 24px 28px; margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.fs-card-title {
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 2px; color: #9ca3af;
    margin-bottom: 20px; padding-bottom: 10px;
    border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center; gap: 8px;
}
.fs-card-title i { color: #2563eb; font-size: .9rem; }

/* Grid */
.fg-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.fg-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.fg-1 { display: grid; grid-template-columns: 1fr; gap: 16px; }

label.fl { font-size: .8rem; font-weight: 700; color: #374151;
           display: block; margin-bottom: 5px; }
label.fl span { color: #dc2626; margin-left: 2px; }

.fc {
    width: 100%; padding: .55rem .85rem;
    border: 1.5px solid #e5e7eb; border-radius: 9px;
    font-size: .86rem; background: #fff; color: #111;
    transition: border-color .2s;
}
.fc:focus { border-color: #2563eb; outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.fc-hint  { font-size: .73rem; color: #9ca3af; margin-top: 3px; }

/* Niveles dinámicos */
.nivel-row {
    display: grid; grid-template-columns: 1fr 120px 36px; gap: 8px;
    align-items: center; margin-bottom: 8px;
}
.btn-rm-nivel {
    background: none; border: none; color: #d1d5db;
    cursor: pointer; font-size: 1rem; padding: 0 4px;
}
.btn-rm-nivel:hover { color: #dc2626; }
.btn-add-nivel {
    background: none; border: 1px dashed #d1d5db; color: #6b7280;
    border-radius: 8px; padding: 6px 14px; font-size: .8rem;
    cursor: pointer; margin-top: 4px; display: inline-flex; align-items: center; gap: 5px;
}
.btn-add-nivel:hover { border-color: #2563eb; color: #2563eb; }

/* Acciones finales */
.form-actions {
    display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px;
}
.btn-save {
    background: #111827; color: #fff; border: none; border-radius: 11px;
    padding: .75rem 2rem; font-size: .9rem; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 7px; transition: background .2s;
}
.btn-save:hover { background: #374151; }
.btn-cancel {
    background: transparent; color: #6b7280;
    border: 1.5px solid #e5e7eb; border-radius: 11px;
    padding: .75rem 1.5rem; font-size: .9rem; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center;
    transition: all .2s;
}
.btn-cancel:hover { border-color: #111; color: #111; }

.alert-err {
    background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
    padding: 12px 16px; color: #b91c1c; font-size: .85rem; margin-bottom: 18px;
}

/* Autocompletado cliente */
.ac-wrap { position: relative; }
.ac-list { position: absolute; top: 100%; left: 0; right: 0; z-index: 200;
           background: #fff; border: 1.5px solid #d1d5db; border-top: none;
           border-radius: 0 0 8px 8px; max-height: 180px; overflow-y: auto; display: none; }
.ac-item { padding: 7px 12px; font-size: .83rem; cursor: pointer; border-bottom: 1px solid #f3f4f6; }
.ac-item:hover { background: #f0f9ff; }
.ac-item.nuevo { color: #2563eb; font-weight: 700; }

/* Panel nuevo cliente */
.cliente-panel { background: #f0fdf4; border: 1.5px dashed #86efac; border-radius: 10px; padding: 14px 16px; margin-top: 10px; display: none; }
.cliente-panel .cp-title { font-size: .75rem; font-weight: 800; color: #166534; margin-bottom: 10px; }

/* Calendario días inhábiles */
.inhab-toggle { background: none; border: 1px dashed #f59e0b; color: #92400e; border-radius: 8px;
                padding: 5px 12px; font-size: .78rem; cursor: pointer; margin-top: 8px;
                display: inline-flex; align-items: center; gap: 5px; }
.inhab-toggle:hover { background: #fffbeb; }
.inhab-cal { display: none; margin-top: 12px; background: #fffbeb; border: 1px solid #fde68a;
             border-radius: 12px; padding: 14px; }
.inhab-cal-header { display: flex; justify-content: space-between; align-items: center;
                    margin-bottom: 10px; font-weight: 700; font-size: .85rem; color: #92400e; }
.inhab-cal-header button { background: none; border: 1px solid #fde68a; border-radius: 6px;
                           padding: 2px 8px; cursor: pointer; font-weight: 700; color: #92400e; }
.inhab-days-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; font-size: .75rem; }
.day-name { color: #92400e; font-weight: 700; padding: 4px 2px; }
.day-cell { padding: 5px 2px; border-radius: 6px; cursor: pointer; transition: all .15s; }
.day-cell:hover:not(.other-month):not(.sunday) { background: #fde68a; }
.day-cell.sunday { color: #d1d5db; cursor: default; text-decoration: line-through; }
.day-cell.other-month { color: #e5e7eb; cursor: default; }
.day-cell.selected { background: #f59e0b; color: #fff; font-weight: 700; border-radius: 6px; }
.day-cell.saved-inhabil { background: #f97316; color: #fff; font-weight: 700; border-radius: 6px; }
.inhab-list { margin-top: 10px; font-size: .78rem; color: #92400e; }
.inhab-chip { display: inline-flex; align-items: center; gap: 4px; background: #fde68a;
              border-radius: 12px; padding: 2px 8px; margin: 2px; font-size: .73rem; }
.btn-rm-chip { background: none; border: none; cursor: pointer; color: #92400e; padding: 0 2px; font-size: .75rem; }

@media(max-width: 640px) { .fg-2, .fg-3 { grid-template-columns: 1fr; } }
</style>

<div class="form-wrap">
    <a href="{{ route('obras.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Mis obras
    </a>

    @if($errors->any())
    <div class="alert-err">
        <strong><i class="bi bi-exclamation-triangle me-1"></i>Por favor corrige los siguientes errores:</strong>
        <ul class="mb-0 mt-2 ps-3">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <h1 class="form-title">Nueva Obra</h1>
    <p class="form-sub">Registra los datos generales de la nueva obra o proyecto.</p>

    <form action="{{ route('obras.store') }}" method="POST" id="formNuevaObra">
        @csrf

        {{-- ── 1. Datos básicos ── --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-building"></i> Datos de la Obra</div>

            <div class="fg-1" style="margin-bottom:16px;">
                <div>
                    <label class="fl" for="nombre">Nombre de la obra <span>*</span></label>
                    <input type="text" id="nombre" name="nombre" class="fc"
                           value="{{ old('nombre') }}" placeholder="Ej. Residencia Pedregal"
                           required maxlength="255">
                </div>
            </div>

            <div class="fg-1" style="margin-bottom:16px;">
                <div>
                    <label class="fl" for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="fc" rows="2"
                              placeholder="Descripción general de la obra...">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <div class="fg-2">
                <div>
                    <label class="fl" for="dimensiones_m2">Dimensiones (m²)</label>
                    <input type="number" id="dimensiones_m2" name="dimensiones_m2" class="fc"
                           step="0.01" min="0" value="{{ old('dimensiones_m2') }}"
                           placeholder="0.00">
                </div>
                <div>
                    <label class="fl" for="num_niveles">Número de niveles</label>
                    <input type="number" id="num_niveles" name="num_niveles" class="fc"
                           min="1" value="{{ old('num_niveles', 1) }}"
                           placeholder="1" oninput="sincronizarNumNiveles()">
                    <p class="fc-hint">Se crearán automáticamente en la tabla de Niveles.</p>
                </div>
            </div>

            <div style="margin-top:16px; border-top:1px dashed #e5e7eb; padding-top:16px;">
                <label class="fl" style="color:#2563eb; margin-bottom:10px;"><i class="bi bi-geo-alt me-1"></i>Dirección de la Obra</label>
                <div class="fg-2">
                    <div>
                        <label class="fl">Calle y número</label>
                        <input type="text" name="obra_calle" class="fc" value="{{ old('obra_calle') }}">
                    </div>
                    <div>
                        <label class="fl">Colonia</label>
                        <input type="text" name="obra_colonia" class="fc" value="{{ old('obra_colonia') }}">
                    </div>
                </div>
                <div class="fg-3" style="margin-top:10px;">
                    <div>
                        <label class="fl">Delegación/Municipio</label>
                        <input type="text" name="obra_del" class="fc" value="{{ old('obra_del') }}">
                    </div>
                    <div>
                        <label class="fl">Código Postal</label>
                        <input type="number" name="obra_cp" class="fc" value="{{ old('obra_cp') }}">
                    </div>
                    <div>
                        <label class="fl">Estado</label>
                        <select name="obra_estado" class="fc">
                            <option value="">— Seleccionar —</option>
                            @foreach($estados as $est)
                            <option value="{{ $est->id }}" {{ old('obra_estado') == $est->id ? 'selected' : '' }}>{{ $est->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 2. Cliente ── --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-person-badge"></i> Cliente de la Obra</div>
            <div class="fg-1">
                <div>
                    <label class="fl">Buscar cliente existente</label>
                    <div class="ac-wrap">
                        <input type="text" id="cliente_txt" class="fc" placeholder="Escribir nombre del cliente…" autocomplete="off">
                        <div class="ac-list" id="cliente_list"></div>
                    </div>
                    <input type="hidden" name="id_cliente" id="id_cliente_hid" value="{{ old('id_cliente') }}">

                    @if($clientes->isEmpty())
                        <p class="fc-hint" style="color:#f59e0b;"><i class="bi bi-info-circle"></i> No hay clientes registrados.</p>
                    @endif

                    {{-- Panel registrar nuevo cliente --}}
                    <div class="cliente-panel" id="clienteNuevoPanel">
                        <div class="cp-title"><i class="bi bi-person-plus me-1"></i>Registrar nuevo cliente</div>
                        <div class="fg-2" style="margin-bottom:10px;">
                            <div>
                                <label class="fl">Nombre completo *</label>
                                <input type="text" name="cliente_nuevo_nombre" id="clienteNuevoNombre" class="fc" placeholder="Nombre del cliente">
                            </div>
                            <div>
                                <label class="fl">Teléfono</label>
                                <input type="text" name="cliente_nuevo_tel" class="fc" placeholder="Ej. 55 1234 5678">
                            </div>
                        </div>
                        <div class="fg-2">
                            <div>
                                <label class="fl">Correo electrónico</label>
                                <input type="email" name="cliente_nuevo_email" class="fc" placeholder="correo@ejemplo.com" value="{{ old('cliente_nuevo_email') }}">
                            </div>
                            <div>
                                <label class="fl" style="display:flex; justify-content:space-between;">
                                    <span>RFC <span>*</span></span>
                                    <label style="font-weight:normal; font-size:0.75rem; color:#2563eb; cursor:pointer; margin:0;">
                                        <input type="checkbox" onchange="toggleClientNA(this)" style="vertical-align:middle; margin-right:2px;"> No aplica
                                    </label>
                                </label>
                                <input type="text" name="cliente_nuevo_rfc" class="fc" placeholder="Ej. ABCD123456XYZ" value="{{ old('cliente_nuevo_rfc') }}">
                            </div>
                        </div>
                        <div class="fg-2" style="margin-top:10px;">
                            <div>
                                <label class="fl">Uso de suelo <span>*</span></label>
                                <input type="text" name="cliente_nuevo_uso" class="fc" placeholder="Ej. Habitacional" value="{{ old('cliente_nuevo_uso') }}">
                            </div>
                            <div>
                                <label class="fl">Cuenta catastral <span>*</span></label>
                                <input type="text" name="cliente_nuevo_catastral" class="fc" placeholder="Ej. 123-456-789" value="{{ old('cliente_nuevo_catastral') }}">
                            </div>
                        </div>
                        <div style="margin-top:16px; border-top:1px dashed #86efac; padding-top:16px;">
                            <label class="fl" style="color:#166534; margin-bottom:10px;"><i class="bi bi-geo-alt me-1"></i>Dirección Fiscal <span>*</span></label>
                            <div class="fg-2">
                                <div>
                                    <label class="fl">Calle y número</label>
                                    <input type="text" name="cliente_calle" class="fc" value="{{ old('cliente_calle') }}">
                                </div>
                                <div>
                                    <label class="fl">Colonia</label>
                                    <input type="text" name="cliente_colonia" class="fc" value="{{ old('cliente_colonia') }}">
                                </div>
                            </div>
                            <div class="fg-3" style="margin-top:10px;">
                                <div>
                                    <label class="fl">Delegación/Municipio</label>
                                    <input type="text" name="cliente_del" class="fc" value="{{ old('cliente_del') }}">
                                </div>
                                <div>
                                    <label class="fl">Código Postal</label>
                                    <input type="number" name="cliente_cp" class="fc" value="{{ old('cliente_cp') }}">
                                </div>
                                <div>
                                    <label class="fl">Estado</label>
                                    <select name="cliente_estado" class="fc">
                                        <option value="">— Seleccionar —</option>
                                        @foreach($estados as $est)
                                        <option value="{{ $est->id }}" {{ old('cliente_estado') == $est->id ? 'selected' : '' }}>{{ $est->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p class="fc-hint" style="margin-top:14px;"><i class="bi bi-info-circle me-1"></i>El cliente se registrará automáticamente al guardar la obra. Los campos marcados con (*) son requeridos.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 3. Responsable ── --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-person-badge"></i> Encargado / Responsable</div>
            <div class="fg-2">
                <div>
                    <label class="fl" for="encargado_id_empleado">Encargado de obra</label>
                    <select id="encargado_id_empleado" name="encargado_id_empleado" class="fc">
                        <option value="">— Sin asignar —</option>
                        @foreach($empleados as $emp)
                        <option value="{{ $emp->id }}"
                            {{ old('encargado_id_empleado') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->persona?->nombre }} {{ $emp->persona?->apellido_paterno }}
                            @if($emp->rol) · {{ $emp->rol }} @endif
                        </option>
                        @endforeach
                    </select>
                    @if($empleados->isEmpty())
                        <p class="fc-hint" style="color:#f59e0b;"><i class="bi bi-info-circle"></i> No hay empleados registrados.</p>
                    @endif
                </div>
                <div></div>
            </div>
        </div>

        {{-- ── 4. Fechas y duración ── --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-calendar3"></i> Tiempos</div>
            <div class="fg-3">
                <div>
                    <label class="fl" for="fecha_inicio">Fecha de Inicio <span>*</span></label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="fc"
                           value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}" required>
                </div>
                <div>
                    <label class="fl" for="duracion">Duración (días hábiles)</label>
                    <input type="number" id="duracion" name="duracion" class="fc"
                           min="1" value="{{ old('duracion') }}" placeholder="Ej. 180">
                    <p class="fc-hint">No se contarán domingos ni días inhábiles marcados.</p>
                </div>
                <div>
                    <label class="fl">Fecha est. de entrega</label>
                    <input type="text" id="fecha_entrega_calc" class="fc" readonly
                           style="background:#f9fafb;color:#374151;font-weight:600;"
                           placeholder="Se calcula automáticamente">
                </div>
            </div>

            {{-- Días inhábiles --}}
            <div style="margin-top:14px;">
                <button type="button" class="inhab-toggle" onclick="toggleCalInhab()">
                    <i class="bi bi-calendar-x"></i>
                    Marcar días inhábiles / festivos
                    <span id="inhabBadge" style="background:#f59e0b;color:#fff;border-radius:10px;padding:1px 7px;font-size:.7rem;display:none;"></span>
                </button>

                <div class="inhab-cal" id="inhabCal">
                    <div class="inhab-cal-header">
                        <button type="button" onclick="cambiarMes(-1)">&#8249;</button>
                        <span id="mesLabel"></span>
                        <button type="button" onclick="cambiarMes(1)">&#8250;</button>
                    </div>
                    <div class="inhab-days-grid">
                        <div class="day-name">Dom</div><div class="day-name">Lun</div><div class="day-name">Mar</div>
                        <div class="day-name">Mié</div><div class="day-name">Jue</div><div class="day-name">Vie</div>
                        <div class="day-name">Sáb</div>
                    </div>
                    <div class="inhab-days-grid" id="diasGrid"></div>
                    <div class="inhab-list" id="inhabList">
                        <strong>Días inhábiles marcados:</strong><br>
                        <div id="inhabChips" style="margin-top:4px;">Ninguno</div>
                    </div>
                    <div style="margin-top:10px;font-size:.72rem;color:#92400e;"><i class="bi bi-info-circle me-1"></i>Los domingos ya no se cuentan automáticamente. Haz clic en un día para marcarlo/desmarcarlo como inhábil.</div>
                </div>
            </div>
        </div>

        {{-- ── 5. Estimaciones económicas ── --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-cash-stack"></i> Estimaciones (opcional)</div>
            <div class="fg-2">
                <div>
                    <label class="fl" for="precio_por_m2_estimado">Precio est. por m² ($)</label>
                    <input type="number" id="precio_por_m2_estimado" name="precio_por_m2_estimado"
                           class="fc" step="0.01" min="0" value="{{ old('precio_por_m2_estimado') }}"
                           placeholder="0.00" oninput="calcularTotalEstimado()">
                </div>
                <div>
                    <label class="fl" for="total_de_obra_estimado">Total de obra estimado ($)</label>
                    <input type="number" id="total_de_obra_estimado" name="total_de_obra_estimado"
                           class="fc" step="0.01" min="0" value="{{ old('total_de_obra_estimado') }}"
                           placeholder="0.00">
                    <p class="fc-hint">Se calcula automáticamente (m² × precio/m²) o ingrésalo manualmente.</p>
                </div>
            </div>
        </div>

        {{-- ── 6. Niveles ── --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-layers"></i> Niveles de la Obra</div>
            <p style="font-size:.82rem;color:#6b7280;margin-bottom:14px;">
                Define cada nivel/piso de la obra. Si la dejas vacía se creará "Planta Baja" automáticamente.
            </p>

            <div id="nivelesContainer"></div>

            <button type="button" class="btn-add-nivel" id="btnAddNivel">
                <i class="bi bi-plus"></i> Agregar nivel
            </button>
        </div>

        {{-- Campos ocultos días inhábiles --}}
        <input type="hidden" id="diasInhabilesJson" name="dias_inhabiles_json" value="[]">

        <div class="form-actions">
            <a href="{{ route('obras.index') }}" class="btn-cancel">
                <i class="bi bi-x-lg me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn-save" id="btn-guardar-obra">
                <i class="bi bi-check-lg"></i> Crear Obra
            </button>
        </div>
    </form>
</div>

<script>
// ── AUTOCOMPLETADO CLIENTE ─────────────────────────────────────────────────
const clienteInput = document.getElementById('cliente_txt');
const clienteList  = document.getElementById('cliente_list');
const clienteHid   = document.getElementById('id_cliente_hid');
const clientePanel = document.getElementById('clienteNuevoPanel');

clienteInput.addEventListener('input', async function() {
    const q = this.value.trim();
    clienteHid.value = '';
    clientePanel.style.display = 'none';
    if (!q) { clienteList.style.display='none'; return; }

    // Búsqueda con API
    const resp = await fetch(`{{ route('api.clientes.buscar') }}?q=${encodeURIComponent(q)}`);
    const data = await resp.json();

    clienteList.innerHTML = '';
    data.forEach(c => {
        const div = document.createElement('div');
        div.className = 'ac-item';
        div.textContent = c.texto + (c.tel ? ' · '+c.tel : '');
        div.onclick = () => {
            clienteInput.value = c.texto;
            clienteHid.value = c.id;
            clienteList.style.display='none';
            clientePanel.style.display='none';
        };
        clienteList.appendChild(div);
    });

    const nv = document.createElement('div');
    nv.className = 'ac-item nuevo';
    nv.innerHTML = '<i class="bi bi-person-plus me-1"></i>Registrar nuevo cliente: "' + q + '"';
    nv.onclick = () => {
        clienteHid.value = '';
        clientePanel.style.display='block';
        document.getElementById('clienteNuevoNombre').value = q;
        clienteList.style.display='none';
    };
    clienteList.appendChild(nv);
    clienteList.style.display = 'block';
});

document.addEventListener('click', e => {
    if(!e.target.closest('.ac-wrap')) clienteList.style.display='none';
});

// ── NIVELES ────────────────────────────────────────────────────────────────
let nivelIdx = 0;

function agregarNivel(desc = '', m2 = '') {
    const cont  = document.getElementById('nivelesContainer');
    const ri    = nivelIdx++;
    const div   = document.createElement('div');
    div.className = 'nivel-row';
    div.dataset.ni = ri;
    div.innerHTML = `
        <div>
            <input type="text" name="niveles[${ri}][descripcion]" class="fc"
                   value="${desc}" placeholder="Ej. Planta Baja, Nivel 1, Azotea...">
        </div>
        <div>
            <input type="number" name="niveles[${ri}][m2]" class="fc"
                   step="0.01" min="0" value="${m2}" placeholder="m²">
        </div>
        <div>
            <button type="button" class="btn-rm-nivel" onclick="this.closest('.nivel-row').remove()">
                <i class="bi bi-trash3"></i>
            </button>
        </div>
    `;
    cont.appendChild(div);
}

agregarNivel('Planta Baja');
document.getElementById('btnAddNivel').addEventListener('click', () => agregarNivel());

// ── CÁLCULO FECHA SIN DOMINGOS + DÍAS INHÁBILES ────────────────────────────
let inhabLocales = {}; // {YYYY-MM-DD: 'descripcion'}

// Pre-cargar los días inhábiles guardados en BD
@php
$inhabBD = isset($diasInhabile) ? $diasInhabile->map(fn($d) => ['fecha' => $d->fecha->format('Y-m-d'), 'descripcion' => $d->descripcion ?? '']) : collect();
@endphp
const inhabBD = @json($inhabBD);
inhabBD.forEach(d => { inhabLocales[d.fecha] = d.descripcion || 'Día inhábil'; });

function calcularFechaEntrega() {
    const fi  = document.getElementById('fecha_inicio').value;
    const dur = parseInt(document.getElementById('duracion').value);
    const out = document.getElementById('fecha_entrega_calc');
    if (!fi || !dur || dur < 1) { out.value = ''; return; }

    let d = new Date(fi + 'T12:00:00'); // Mediodía para evitar problema de TZ
    let diasRestantes = dur;

    while (diasRestantes > 0) {
        d.setDate(d.getDate() + 1);
        const dayOfWeek = d.getDay(); // 0 = domingo
        const key = d.toISOString().slice(0,10);
        if (dayOfWeek === 0) continue;         // Omitir domingos
        if (inhabLocales[key] !== undefined) continue; // Omitir inhábiles
        diasRestantes--;
    }

    out.value = d.toLocaleDateString('es-MX', { day:'2-digit', month:'2-digit', year:'numeric' });
}

document.getElementById('fecha_inicio').addEventListener('input', calcularFechaEntrega);
document.getElementById('duracion').addEventListener('input', calcularFechaEntrega);
calcularFechaEntrega();

// ── CALENDARIO DE DÍAS INHÁBILES ───────────────────────────────────────────
let calMes  = new Date().getMonth();
let calAnio = new Date().getFullYear();

function toggleCalInhab() {
    const cal = document.getElementById('inhabCal');
    cal.style.display = cal.style.display === 'block' ? 'none' : 'block';
    if (cal.style.display === 'block') renderCal();
}

function cambiarMes(delta) {
    calMes += delta;
    if (calMes < 0)  { calMes = 11; calAnio--; }
    if (calMes > 11) { calMes = 0;  calAnio++; }
    renderCal();
}

function renderCal() {
    const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    document.getElementById('mesLabel').textContent = meses[calMes] + ' ' + calAnio;

    const grid = document.getElementById('diasGrid');
    grid.innerHTML = '';

    const primerDia = new Date(calAnio, calMes, 1).getDay(); // 0=dom
    const diasEnMes = new Date(calAnio, calMes + 1, 0).getDate();

    // Celdas vacías antes del primer día
    for (let i = 0; i < primerDia; i++) {
        const div = document.createElement('div');
        div.className = 'day-cell other-month';
        grid.appendChild(div);
    }

    for (let d = 1; d <= diasEnMes; d++) {
        const fecha = new Date(calAnio, calMes, d);
        const key   = fecha.toISOString().slice(0,10);
        const dow   = fecha.getDay();

        const div = document.createElement('div');
        div.className = 'day-cell' + (dow===0 ? ' sunday' : '');
        div.textContent = d;

        if (inhabBD.find(x => x.fecha === key)) div.classList.add('saved-inhabil');
        else if (inhabLocales[key] !== undefined) div.classList.add('selected');

        if (dow !== 0) {
            div.onclick = () => toggleInhabil(key, d);
        }
        grid.appendChild(div);
    }
    renderChips();
}

function toggleInhabil(key, dia) {
    if (inhabLocales[key] !== undefined) {
        delete inhabLocales[key];
    } else {
        inhabLocales[key] = 'Día inhábil personalizado';
    }
    document.getElementById('diasInhabilesJson').value = JSON.stringify(inhabLocales);
    renderCal();
    calcularFechaEntrega();
    actualizarBadge();
}

function renderChips() {
    const chips = document.getElementById('inhabChips');
    const claves = Object.keys(inhabLocales);
    if (!claves.length) { chips.textContent = 'Ninguno'; return; }
    chips.innerHTML = claves.sort().map(k => {
        const [y,m,d] = k.split('-');
        return `<span class="inhab-chip">${d}/${m}/${y}
            <button type="button" class="btn-rm-chip" onclick="toggleInhabil('${k}')">✕</button>
        </span>`;
    }).join('');
}

function actualizarBadge() {
    const badge = document.getElementById('inhabBadge');
    const n = Object.keys(inhabLocales).length;
    badge.textContent = n > 0 ? n : '';
    badge.style.display = n > 0 ? 'inline-block' : 'none';
}

// Inicializar badge con los días de BD
actualizarBadge();

// ── TOTAL ESTIMADO ─────────────────────────────────────────────────────────
function calcularTotalEstimado() {
    const m2    = parseFloat(document.getElementById('dimensiones_m2').value   || 0);
    const pxm2  = parseFloat(document.getElementById('precio_por_m2_estimado').value || 0);
    const total = document.getElementById('total_de_obra_estimado');
    if (m2 > 0 && pxm2 > 0) {
        total.value = (m2 * pxm2).toFixed(2);
    }
}
document.getElementById('dimensiones_m2').addEventListener('input', calcularTotalEstimado);
</script>
@endsection
