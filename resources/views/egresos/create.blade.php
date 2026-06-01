@extends('layout')
@section('title','Nuevo Egreso')
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
    <a href="{{ route('egresos.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Nuevo Egreso</h1></div>
        <form action="{{ route('egresos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="id_obra">Obra *</label>
                <select id="id_obra" name="id_obra" class="form-select" required>
                    <option value="">Seleccione una obra</option>
                    @foreach($obras as $o)
                        <option value="{{ $o->id }}" {{ old('id_obra', request('id_obra')) == $o->id ? 'selected' : '' }}>{{ $o->datosDeObra?->nombre ?? 'Obra #'.$o->id }}</option>
                    @endforeach
                </select>
                @error('id_obra') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="id_area">Área *</label>
                <select id="id_area" name="id_area" class="form-select" required>
                    <option value="">Seleccione un área</option>
                    @foreach($areas as $a)
                        <option value="{{ $a->id }}" {{ old('id_area') == $a->id ? 'selected' : '' }}>{{ $a->abreviatura }} - {{ $a->descripcion }}</option>
                    @endforeach
                </select>
                @error('id_area') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="id_persona">Persona / Proveedor</label>
                <select id="id_persona" name="id_persona" class="form-select">
                    <option value="">-- Opcional --</option>
                    @foreach($personas as $p)
                        <option value="{{ $p->id }}" {{ old('id_persona') == $p->id ? 'selected' : '' }}>{{ $p->nombre }} {{ $p->apellido_paterno }}</option>
                    @endforeach
                </select>
                @error('id_persona') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="id_material">Material Comprado (Opcional - Para control de obra)</label>
                <select id="id_material" name="id_material" class="form-select">
                    <option value="">-- No es material o no especificar --</option>
                    @foreach($materiales as $m)
                        <option value="{{ $m->id }}" {{ old('id_material') == $m->id ? 'selected' : '' }}>{{ $m->nombre }} - {{ $m->marca }} ({{ $m->unidadMedida?->abreviatura }})</option>
                    @endforeach
                </select>
                @error('id_material') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group" id="group_cantidad_material" style="display: none;">
                <label for="cantidad_material">Cantidad Comprada</label>
                <input type="number" step="0.01" id="cantidad_material" name="cantidad_material" class="form-control" value="{{ old('cantidad_material') }}">
                <small class="text-muted">Cantidad de material (solo aplica si selecciona un material arriba).</small>
                @error('cantidad_material') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <script>
                document.getElementById('id_material').addEventListener('change', function() {
                    const group = document.getElementById('group_cantidad_material');
                    if(this.value) {
                        group.style.display = 'block';
                    } else {
                        group.style.display = 'none';
                        document.getElementById('cantidad_material').value = '';
                    }
                });
                // run once on load in case there's old input
                if(document.getElementById('id_material').value) {
                    document.getElementById('group_cantidad_material').style.display = 'block';
                }
            </script>
            <div class="form-group">
                <label for="concepto">Concepto (Descripción)</label>
                <input type="text" id="concepto" name="concepto" class="form-control" value="{{ old('concepto') }}" maxlength="255">
                @error('concepto') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="pago">Monto del pago *</label>
                <input type="number" step="0.01" id="pago" name="pago" class="form-control" value="{{ old('pago', 0) }}" required>
                @error('pago') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha">Fecha *</label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
                @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Guardar Egreso</button>
        </form>
    </div>
</div>
@endsection
