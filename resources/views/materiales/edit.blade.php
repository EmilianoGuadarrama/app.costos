@extends('layout')
@section('title','Editar Material')

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
    <a href="{{ route('materiales.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Volver
    </a>

    <div class="form-panel">
        <div class="header-section">
            <h1>Editar Material</h1>
        </div>

        <form action="{{ route('materiales.update', $material->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="clave">Clave *</label>
                <input type="text" id="clave" name="clave" class="form-control" value="{{ old('clave', $material->clave) }}" required maxlength="50">
                @error('clave')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción *</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control" value="{{ old('descripcion', $material->descripcion) }}" required maxlength="150">
                @error('descripcion')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="marca">Marca *</label>
                <input type="text" id="marca" name="marca" class="form-control" value="{{ old('marca', $material->marca) }}" required maxlength="120">
                @error('marca')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="unidad_medida_id">Unidad de Medida *</label>
                <select id="unidad_medida_id" name="unidad_medida_id" class="form-select" required>
                    <option value="">Seleccione</option>
                    @foreach($unidades as $u)
                        <option value="{{ $u->id }}" {{ old('unidad_medida_id', $material->unidad_medida_id) == $u->id ? 'selected' : '' }}>
                            {{ $u->nombre }} ({{ $u->abreviatura }})
                        </option>
                    @endforeach
                </select>
                @error('unidad_medida_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="precio_unitario">Precio Unitario *</label>
                <input type="number" step="0.01" id="precio_unitario" name="precio_unitario" class="form-control" value="{{ old('precio_unitario', $material->precio_unitario) }}" required>
                @error('precio_unitario')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Actualizar</button>
        </form>
    </div>
</div>
@endsection