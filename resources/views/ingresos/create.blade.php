@extends('layout')
@section('title','Nuevo Ingreso')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .dash-form-view{ min-height:100%; background:#f8f8f8; font-family:"Arial",sans-serif; color:#111; padding:20px; }
    .form-panel{ background:#fff; padding:40px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); max-width:600px; margin:0 auto; }
    .header-section{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:30px; }
    .header-section h1{ font-size:1.8rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .form-group{ margin-bottom:20px; }
    .form-group label{ display:block; margin-bottom:8px; font-weight:600; font-size:.9rem; color:#333; }
    .form-control,.form-select{ width:100%; padding:10px 15px; border:1px solid #ccc; border-radius:6px; font-size:1rem; }
    .btn-submit{ background:#111; color:#fff; border:none; padding:12px 25px; border-radius:6px; font-size:.9rem; font-weight:600; cursor:pointer; width:100%; }
    .btn-submit:hover{ background:#333; }
    .btn-back{ display:inline-block; margin-bottom:20px; color:#666; text-decoration:none; font-size:.9rem; }
    .text-danger{ color:#dc3545; font-size:.85rem; margin-top:5px; display:block; }
</style>
<div class="dash-form-view">
    <a href="{{ route('ingresos.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Nuevo Ingreso</h1></div>
        <form action="{{ route('ingresos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="id_obra">Proyecto/Obra *</label>
                <select id="id_obra" name="id_obra" class="form-select" required onchange="actualizarRestante()">
                    <option value="" data-restante="">Seleccione un proyecto</option>
                    @foreach($obras as $p)
                        <option value="{{ $p->id }}" data-restante="{{ $p->obraProceso ? number_format($p->obraProceso->presupuesto_restante, 2) : 'N/A' }}" {{ old('id_obra', request('id_obra')) == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
                <small id="restante-info" style="color:#059669; font-weight:700; margin-top:8px; display:block;"></small>
                @error('id_obra') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="id_empleado">Empleado/Cliente</label>
                <select id="id_empleado" name="id_empleado" class="form-select">
                    <option value="">Seleccione... (opcional)</option>
                    @foreach($empleados as $c)
                        <option value="{{ $c->id }}" {{ old('id_empleado') == $c->id ? 'selected' : '' }}>{{ $c->persona?->nombre }} {{ $c->persona?->apellido_paterno }}</option>
                    @endforeach
                </select>
                @error('id_empleado') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="concepto">Concepto *</label>
                <input type="text" id="concepto" name="concepto" class="form-control" value="{{ old('concepto') }}" required maxlength="255">
                @error('concepto') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="monto_dado">Monto *</label>
                <input type="number" step="0.01" id="monto_dado" name="monto_dado" class="form-control" value="{{ old('monto_dado', 0) }}" required>
                @error('monto_dado') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha">Fecha *</label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
                @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Guardar Ingreso</button>
        </form>
    </div>
</div>
<script>
function actualizarRestante() {
    const select = document.getElementById('id_obra');
    const option = select.options[select.selectedIndex];
    const restanteInfo = document.getElementById('restante-info');
    
    if(option.value) {
        const restante = option.getAttribute('data-restante');
        if(restante !== 'N/A') {
            restanteInfo.innerHTML = '<i class="bi bi-info-circle-fill"></i> Restante por cobrar: $' + restante;
        } else {
            restanteInfo.innerHTML = '<i class="bi bi-info-circle-fill"></i> Sin presupuesto aprobado';
        }
    } else {
        restanteInfo.innerHTML = '';
    }
}
// Run on load in case old() value is selected
document.addEventListener('DOMContentLoaded', function() {
    actualizarRestante();
});
</script>
@endsection
