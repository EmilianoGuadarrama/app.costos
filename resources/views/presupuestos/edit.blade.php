@extends('layout')
@section('title','Editar Presupuesto')
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
    <a href="{{ route('presupuestos.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Editar Presupuesto</h1></div>
        <form action="{{ route('presupuestos.update', $presupuesto) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="proyecto_id">Proyecto *</label>
                <select id="proyecto_id" name="proyecto_id" class="form-select" required>
                    <option value="">Seleccione un proyecto</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id }}" {{ old('proyecto_id', $presupuesto->proyecto_id) == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
                @error('proyecto_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nombre">Nombre del Presupuesto *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $presupuesto->nombre) }}" required maxlength="150">
                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" class="form-select">
                    <option value="Borrador" {{ old('estado', $presupuesto->estado) == 'Borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="Aprobado" {{ old('estado', $presupuesto->estado) == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="Rechazado" {{ old('estado', $presupuesto->estado) == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
                @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Actualizar</button>
        </form>
    </div>
</div>
@endsection
