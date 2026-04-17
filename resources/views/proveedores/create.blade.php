@extends('layout')
@section('title','Nuevo Proveedor')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .dash-form-view{ min-height:100%; background:#f8f8f8; font-family:"Arial",sans-serif; color:#111; padding:20px; }
    .form-panel{ background:#fff; padding:40px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); max-width:600px; margin:0 auto; }
    .header-section{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:30px; }
    .header-section h1{ font-size:1.8rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .form-group{ margin-bottom:20px; }
    .form-group label{ display:block; margin-bottom:8px; font-weight:600; font-size:.9rem; color:#333; }
    .form-control{ width:100%; padding:10px 15px; border:1px solid #ccc; border-radius:6px; font-size:1rem; }
    .btn-submit{ background:#111; color:#fff; border:none; padding:12px 25px; border-radius:6px; font-size:.9rem; font-weight:600; cursor:pointer; width:100%; }
    .btn-submit:hover{ background:#333; }
    .btn-back{ display:inline-block; margin-bottom:20px; color:#666; text-decoration:none; font-size:.9rem; }
    .text-danger{ color:#dc3545; font-size:.85rem; margin-top:5px; display:block; }
</style>
<div class="dash-form-view">
    <a href="{{ route('proveedores.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Nuevo Proveedor</h1></div>
        <form action="{{ route('proveedores.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required maxlength="150">
                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="contacto">Contacto</label>
                <input type="text" id="contacto" name="contacto" class="form-control" value="{{ old('contacto') }}" maxlength="150">
                @error('contacto') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control" value="{{ old('telefono') }}" maxlength="20">
                @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" class="form-control" value="{{ old('correo') }}" maxlength="120">
                @error('correo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion') }}" maxlength="255">
                @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <input type="text" id="tipo" name="tipo" class="form-control" value="{{ old('tipo') }}" maxlength="50" placeholder="Ej: Materiales, Servicios...">
                @error('tipo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Guardar Proveedor</button>
        </form>
    </div>
</div>
@endsection
