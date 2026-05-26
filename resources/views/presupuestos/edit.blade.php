@extends('layout')
@section('title','Editar Presupuesto')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .dash-form-view {
        min-height: 100%;
        background: #f8f8f8;
        font-family: "Arial", sans-serif;
        color: #111;
        padding: 20px;
    }
    .form-panel {
        background: #fff;
        padding: 40px;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,.05);
        max-width: 600px;
        margin: 0 auto;
        border: 1px solid #e5e7eb;
    }
    .header-section {
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 20px;
        margin-bottom: 28px;
    }
    .header-section h1 {
        font-size: 1.85rem;
        font-weight: 700;
        margin: 0;
        font-family: "Garamond","Baskerville",serif;
        color: #111;
    }
    .header-section p {
        color: #6b7280;
        margin: 5px 0 0;
        font-size: .88rem;
    }
    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 700;
        font-size: .8rem;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: .8px;
    }
    .form-control, .form-select {
        width: 100%;
        padding: .55rem .85rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: .9rem;
        color: #111;
        background: #fff;
        transition: border-color .18s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #111;
        outline: none;
        box-shadow: 0 0 0 3px rgba(17,24,39,.05);
    }
    .btn-submit {
        background: #111;
        color: #fff;
        border: none;
        padding: .75rem 1.8rem;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        letter-spacing: .4px;
        transition: background .2s;
    }
    .btn-submit:hover { background: #374151; }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 20px;
        color: #6b7280;
        text-decoration: none;
        font-size: .84rem;
        font-weight: 500;
        transition: color .2s;
    }
    .btn-back:hover { color: #111; }
    .text-danger { color: #b91c1c; font-size: .78rem; margin-top: 4px; display: block; }
</style>
<div class="dash-form-view">
    <a href="{{ route('presupuestos.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section">
            <h1>Editar Presupuesto</h1>
            <p>Modifica los datos generales del presupuesto</p>
        </div>
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
            <button type="submit" class="btn-submit">Actualizar Presupuesto</button>
        </form>
    </div>
</div>
@endsection
