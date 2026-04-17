@extends('layout')
@section('title','Editar Maquinaria')
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
    <a href="{{ route('maquinaria_equipo.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Editar Maquinaria/Equipo</h1></div>
        <form action="{{ route('maquinaria_equipo.update', $maquinaria) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="clave">Clave *</label>
                <input type="text" id="clave" name="clave" class="form-control" value="{{ old('clave', $maquinaria->clave) }}" required>
                @error('clave') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="equipo">Equipo *</label>
                <input type="text" id="equipo" name="equipo" class="form-control" value="{{ old('equipo', $maquinaria->equipo) }}" required>
                @error('equipo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="unidad_medida_id">Unidad de Medida *</label>
                <select id="unidad_medida_id" name="unidad_medida_id" class="form-select" required>
                    <option value="">Seleccione</option>
                    @foreach($unidades as $u)
                        <option value="{{ $u->id }}" {{ old('unidad_medida_id', $maquinaria->unidad_medida_id) == $u->id ? 'selected' : '' }}>{{ $u->nombre }} ({{ $u->abreviatura }})</option>
                    @endforeach
                </select>
                @error('unidad_medida_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="costo_por_hora">Costo por Hora *</label>
                <input type="number" step="0.01" id="costo_por_hora" name="costo_por_hora" class="form-control" value="{{ old('costo_por_hora', $maquinaria->costo_por_hora) }}" required>
                @error('costo_por_hora') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Actualizar</button>
        </form>
    </div>
</div>
@endsection
