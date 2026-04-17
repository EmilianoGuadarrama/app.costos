@extends('layout')
@section('title','Editar Ingreso')
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
        <div class="header-section"><h1>Editar Ingreso</h1></div>
        <form action="{{ route('ingresos.update', $ingreso) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="proyecto_id">Proyecto *</label>
                <select id="proyecto_id" name="proyecto_id" class="form-select" required>
                    <option value="">Seleccione un proyecto</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id }}" {{ old('proyecto_id', $ingreso->proyecto_id) == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
                @error('proyecto_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="cliente_id">Cliente *</label>
                <select id="cliente_id" name="cliente_id" class="form-select" required>
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id', $ingreso->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
                @error('cliente_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="concepto">Concepto *</label>
                <input type="text" id="concepto" name="concepto" class="form-control" value="{{ old('concepto', $ingreso->concepto) }}" required maxlength="255">
                @error('concepto') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="monto">Monto *</label>
                <input type="number" step="0.01" id="monto" name="monto" class="form-control" value="{{ old('monto', $ingreso->monto) }}" required>
                @error('monto') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha">Fecha *</label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="{{ old('fecha', \Carbon\Carbon::parse($ingreso->fecha)->format('Y-m-d')) }}" required>
                @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="comprobante">Comprobante (Ruta/URL)</label>
                <input type="text" id="comprobante" name="comprobante" class="form-control" value="{{ old('comprobante', $ingreso->comprobante) }}" maxlength="255">
                @error('comprobante') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Actualizar Ingreso</button>
        </form>
    </div>
</div>
@endsection
