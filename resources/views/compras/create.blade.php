@extends('layout')
@section('title','Nueva Compra')
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
    <a href="{{ route('compras.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Nueva Compra</h1></div>
        <form action="{{ route('compras.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="proyecto_id">Proyecto *</label>
                <select id="proyecto_id" name="proyecto_id" class="form-select" required>
                    <option value="">Seleccione un proyecto</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id }}" {{ old('proyecto_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
                @error('proyecto_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="proveedor_id">Proveedor *</label>
                <select id="proveedor_id" name="proveedor_id" class="form-select" required>
                    <option value="">Seleccione un proveedor</option>
                    @foreach($proveedores as $prov)
                        <option value="{{ $prov->id }}" {{ old('proveedor_id') == $prov->id ? 'selected' : '' }}>{{ $prov->nombre }}</option>
                    @endforeach
                </select>
                @error('proveedor_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="area_id">Área *</label>
                <select id="area_id" name="area_id" class="form-select" required>
                    <option value="">Seleccione un área</option>
                    @foreach($areas as $a)
                        <option value="{{ $a->id }}" {{ old('area_id') == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>
                    @endforeach
                </select>
                @error('area_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_compra">Fecha de Compra *</label>
                <input type="date" id="fecha_compra" name="fecha_compra" class="form-control" value="{{ old('fecha_compra', date('Y-m-d')) }}" required>
                @error('fecha_compra') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="estado">Estado *</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Pagada" {{ old('estado') == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                    <option value="Cancelada" {{ old('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
                @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="factura">Factura</label>
                <input type="text" id="factura" name="factura" class="form-control" value="{{ old('factura') }}" maxlength="100">
                @error('factura') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Guardar Compra</button>
        </form>
    </div>
</div>
@endsection
