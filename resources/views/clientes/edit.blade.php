@extends('layout')
@section('title','Editar Cliente')
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
    <a href="{{ route('clientes.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Editar Cliente</h1></div>
        <form action="{{ route('clientes.update', $cliente) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="tipo_persona">Tipo de Persona *</label>
                <select id="tipo_persona" name="tipo_persona" class="form-select" required>
                    <option value="fisica" {{ old('tipo_persona', $cliente->tipo_persona) == 'fisica' ? 'selected' : '' }}>Física</option>
                    <option value="moral" {{ old('tipo_persona', $cliente->tipo_persona) == 'moral' ? 'selected' : '' }}>Moral</option>
                </select>
                @error('tipo_persona') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre) }}" required maxlength="150">
                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="razon_social">Razón Social</label>
                <input type="text" id="razon_social" name="razon_social" class="form-control" value="{{ old('razon_social', $cliente->razon_social) }}" maxlength="150">
                @error('razon_social') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="rfc">RFC</label>
                <input type="text" id="rfc" name="rfc" class="form-control" value="{{ old('rfc', $cliente->rfc) }}" maxlength="20">
                @error('rfc') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion) }}" maxlength="200">
                @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}" maxlength="20">
                @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" class="form-control" value="{{ old('correo', $cliente->correo) }}" maxlength="120">
                @error('correo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Actualizar Cliente</button>
        </form>
    </div>
</div>
@endsection
