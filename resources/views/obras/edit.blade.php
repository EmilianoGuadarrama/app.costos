@extends('layout')
@section('title', 'Editar Obra — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))
@section('content')
<style>
.form-wrap { max-width: 860px; margin: 0 auto; }
.form-title { font-size: 1.7rem; font-weight: 800; color: #111; margin: 0 0 4px; }
.form-sub   { color: #6b7280; font-size: .9rem; margin: 0 0 28px; }
.btn-back   { color: #6b7280; text-decoration: none; font-size: .85rem;
              display: inline-flex; align-items: center; gap: 5px; margin-bottom: 18px; }
.btn-back:hover { color: #111; }
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
.fs-card-title i { color: #2563eb; }
.fg-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.fg-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.fg-1 { display: grid; grid-template-columns: 1fr; gap: 16px; }
label.fl { font-size: .8rem; font-weight: 700; color: #374151; display: block; margin-bottom: 5px; }
label.fl span { color: #dc2626; }
.fc {
    width: 100%; padding: .55rem .85rem;
    border: 1.5px solid #e5e7eb; border-radius: 9px;
    font-size: .86rem; background: #fff; color: #111; transition: border-color .2s;
}
.fc:focus { border-color: #2563eb; outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.fc-hint  { font-size: .73rem; color: #9ca3af; margin-top: 3px; }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px; }
.btn-save {
    background: #111827; color: #fff; border: none; border-radius: 11px;
    padding: .75rem 2rem; font-size: .9rem; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 7px;
}
.btn-save:hover { background: #374151; }
.btn-cancel {
    background: transparent; color: #6b7280; border: 1.5px solid #e5e7eb;
    border-radius: 11px; padding: .75rem 1.5rem; font-size: .9rem; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center;
}
.btn-cancel:hover { border-color: #111; color: #111; }
.alert-err {
    background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
    padding: 12px 16px; color: #b91c1c; font-size: .85rem; margin-bottom: 18px;
}
@media(max-width:640px){ .fg-2,.fg-3{grid-template-columns:1fr;} }
</style>

<div class="form-wrap">
    <a href="{{ route('obras.show', $obra->id) }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Datos generales
    </a>

    @if($errors->any())
    <div class="alert-err">
        <strong><i class="bi bi-exclamation-triangle me-1"></i>Errores:</strong>
        <ul class="mb-0 mt-2 ps-3">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <h1 class="form-title">Editar Obra</h1>
    <p class="form-sub"><strong>{{ $obra->datosDeObra?->nombre ?? "Obra #$obra->id" }}</strong></p>

    <form action="{{ route('obras.update', $obra->id) }}" method="POST" id="formEditarObra">
        @csrf
        @method('PUT')

        {{-- 1. Datos básicos --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-building"></i> Datos de la Obra</div>
            <div class="fg-1" style="margin-bottom:16px;">
                <div>
                    <label class="fl" for="nombre">Nombre de la obra <span>*</span></label>
                    <input type="text" id="nombre" name="nombre" class="fc" required
                           value="{{ old('nombre', $obra->datosDeObra?->nombre) }}" maxlength="255">
                </div>
            </div>
            <div class="fg-1" style="margin-bottom:16px;">
                <div>
                    <label class="fl" for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="fc" rows="2">{{ old('descripcion', $obra->datosDeObra?->descripcion) }}</textarea>
                </div>
            </div>
            <div class="fg-2">
                <div>
                    <label class="fl" for="dimensiones_m2">Dimensiones (m²)</label>
                    <input type="number" id="dimensiones_m2" name="dimensiones_m2" class="fc"
                           step="0.01" min="0"
                           value="{{ old('dimensiones_m2', $obra->datosDeObra?->dimensiones_m2) }}">
                </div>
                <div>
                    <label class="fl">Número de niveles</label>
                    <input type="number" class="fc" readonly style="background:#f9fafb;color:#6b7280;"
                           value="{{ $obra->niveles->count() }}">
                    <p class="fc-hint">Edita los niveles en la sección "Datos Generales".</p>
                </div>
            </div>
        </div>

        {{-- 2. Encargado --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-person-badge"></i> Encargado</div>
            <div class="fg-2">
                <div>
                    <label class="fl" for="encargado_id_empleado">Encargado de obra</label>
                    <select id="encargado_id_empleado" name="encargado_id_empleado" class="fc">
                        <option value="">— Sin asignar —</option>
                        @foreach($empleados as $emp)
                        <option value="{{ $emp->id }}"
                            {{ old('encargado_id_empleado', $obra->encargado_id_empleado) == $emp->id ? 'selected' : '' }}>
                            {{ $emp->persona?->nombre }} {{ $emp->persona?->apellido_paterno }}
                            @if($emp->rol) · {{ $emp->rol }} @endif
                        </option>
                        @endforeach
                    </select>
                </div>
                <div></div>
            </div>
        </div>

        {{-- 3. Tiempos --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-calendar3"></i> Tiempos</div>
            <div class="fg-3">
                <div>
                    <label class="fl" for="fecha_inicio">Fecha de Inicio <span>*</span></label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="fc" required
                           value="{{ old('fecha_inicio', $obra->fecha_inicio?->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="fl" for="duracion">Duración (días)</label>
                    <input type="number" id="duracion" name="duracion" class="fc" min="1"
                           value="{{ old('duracion', $obra->duracion) }}">
                </div>
                <div>
                    <label class="fl">Fecha est. entrega</label>
                    <input type="text" id="fecha_entrega_calc" class="fc" readonly
                           style="background:#f9fafb;color:#6b7280;">
                </div>
            </div>
        </div>

        {{-- 4. Estimaciones --}}
        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-cash-stack"></i> Estimaciones económicas</div>
            <div class="fg-2">
                <div>
                    <label class="fl" for="precio_por_m2_estimado">Precio est. por m² ($)</label>
                    <input type="number" id="precio_por_m2_estimado" name="precio_por_m2_estimado"
                           class="fc" step="0.01" min="0"
                           value="{{ old('precio_por_m2_estimado', $obra->precio_por_m2_estimado) }}"
                           oninput="calcularTotal()">
                </div>
                <div>
                    <label class="fl" for="total_de_obra_estimado">Total de obra estimado ($)</label>
                    <input type="number" id="total_de_obra_estimado" name="total_de_obra_estimado"
                           class="fc" step="0.01" min="0"
                           value="{{ old('total_de_obra_estimado', $obra->total_de_obra_estimado) }}">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('obras.show', $obra->id) }}" class="btn-cancel">
                <i class="bi bi-x-lg me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn-save" id="btn-guardar-editar">
                <i class="bi bi-check-lg"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
function calcularFechaEntrega() {
    const fi  = document.getElementById('fecha_inicio').value;
    const dur = parseInt(document.getElementById('duracion').value);
    const out = document.getElementById('fecha_entrega_calc');
    if (fi && dur > 0) {
        const d = new Date(fi);
        d.setDate(d.getDate() + dur);
        out.value = d.toLocaleDateString('es-MX', { day:'2-digit', month:'2-digit', year:'numeric' });
    } else {
        out.value = '';
    }
}
document.getElementById('fecha_inicio').addEventListener('input', calcularFechaEntrega);
document.getElementById('duracion').addEventListener('input', calcularFechaEntrega);
calcularFechaEntrega();

function calcularTotal() {
    const m2   = parseFloat(document.getElementById('dimensiones_m2').value   || 0);
    const pxm2 = parseFloat(document.getElementById('precio_por_m2_estimado').value || 0);
    if (m2 > 0 && pxm2 > 0) {
        document.getElementById('total_de_obra_estimado').value = (m2 * pxm2).toFixed(2);
    }
}
document.getElementById('dimensiones_m2').addEventListener('input', calcularTotal);
</script>
@endsection
