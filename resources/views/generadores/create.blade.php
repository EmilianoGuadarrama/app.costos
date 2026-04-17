@extends('layout')
@section('title','Nuevo Generador')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .dash-form-view{ min-height:100%; background:#f8f8f8; font-family:"Arial",sans-serif; color:#111; padding:20px; }
    .form-panel{ background:#fff; padding:40px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); max-width:700px; margin:0 auto; }
    .header-section{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:30px; }
    .header-section h1{ font-size:1.8rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .form-group{ margin-bottom:20px; }
    .form-group label{ display:block; margin-bottom:8px; font-weight:600; font-size:.9rem; color:#333; }
    .form-control,.form-select{ width:100%; padding:10px 15px; border:1px solid #ccc; border-radius:6px; font-size:1rem; }
    .form-control:focus,.form-select:focus{ border-color:#111; outline:none; }
    .btn-submit{ background:#111; color:#fff; border:none; padding:12px 25px; border-radius:6px; font-size:.9rem; font-weight:600; cursor:pointer; width:100%; }
    .btn-submit:hover{ background:#333; }
    .btn-back{ display:inline-block; margin-bottom:20px; color:#666; text-decoration:none; font-size:.9rem; }
    .btn-back:hover{ color:#111; }
    .text-danger{ color:#dc3545; font-size:.85rem; margin-top:5px; display:block; }
</style>
<div class="dash-form-view">
    <a href="{{ route('generadores.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Nuevo Generador</h1></div>
        <form action="{{ route('generadores.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="concepto_id">Concepto *</label>
                <select id="concepto_id" name="concepto_id" class="form-select" required>
                    <option value="">Seleccione un concepto</option>
                    @foreach($conceptos as $c)
                        <option value="{{ $c->id }}" {{ old('concepto_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->clave }} — {{ $c->descripcion }} ({{ $c->unidadMedida->abreviatura ?? '' }})
                        </option>
                    @endforeach
                </select>
                @error('concepto_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="localizacion">Localización</label>
                <input type="text" id="localizacion" name="localizacion" class="form-control" value="{{ old('localizacion') }}" maxlength="150">
                @error('localizacion') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="ejes">Ejes</label>
                <input type="text" id="ejes" name="ejes" class="form-control" value="{{ old('ejes') }}" maxlength="80">
                @error('ejes') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="no_piezas">No. Piezas *</label>
                    <input type="number" step="0.01" id="no_piezas" name="no_piezas" class="form-control" value="{{ old('no_piezas', 0) }}" required>
                    @error('no_piezas') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label for="largo">Largo *</label>
                    <input type="number" step="0.0001" id="largo" name="largo" class="form-control" value="{{ old('largo', 0) }}" required>
                    @error('largo') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="ancho">Ancho *</label>
                    <input type="number" step="0.0001" id="ancho" name="ancho" class="form-control" value="{{ old('ancho', 0) }}" required>
                    @error('ancho') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label for="alto">Alto *</label>
                    <input type="number" step="0.0001" id="alto" name="alto" class="form-control" value="{{ old('alto', 0) }}" required>
                    @error('alto') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="resultado">Resultado *</label>
                <input type="number" step="0.0001" id="resultado" name="resultado" class="form-control" value="{{ old('resultado', 0) }}" required>
                @error('resultado') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-submit">Guardar Generador</button>
        </form>
    </div>
</div>
@endsection
