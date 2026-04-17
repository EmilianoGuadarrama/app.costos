@extends('layout')
@section('title','Nueva Caja Chica')
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
    <a href="{{ route('cajas_chicas.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Nueva Caja Chica</h1></div>
        <form action="{{ route('cajas_chicas.store') }}" method="POST">
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
                <label for="responsable_id">Responsable Técnico *</label>
                <select id="responsable_id" name="responsable_id" class="form-select" required>
                    <option value="">Seleccione un responsable</option>
                    @foreach($responsables as $r)
                        <option value="{{ $r->id }}" {{ old('responsable_id') == $r->id ? 'selected' : '' }}>{{ $r->nombre }}</option>
                    @endforeach
                </select>
                @error('responsable_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nombre">Nombre de la Caja *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required maxlength="150">
                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="monto_inicial">Monto Inicial *</label>
                <input type="number" step="0.01" id="monto_inicial" name="monto_inicial" class="form-control" value="{{ old('monto_inicial', 0) }}" required>
                @error('monto_inicial') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Guardar Caja Chica</button>
        </form>
    </div>
</div>
@endsection
