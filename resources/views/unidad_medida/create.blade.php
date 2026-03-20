@extends('layout')

@section('title','Crear Unidad de Medida')

@section('content')

<style>
.form-card{
    max-width:900px;
    margin:0 auto;
    background:#fff;
    border:1px solid rgba(0,0,0,.18);
    padding:32px;
}
.form-wrapper{
    max-width:620px;
    margin:0 auto;
}
</style>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="fw-bold mb-1">Nueva Unidad de Medida</h2>
        <p class="text-secondary mb-0">Captura la información de la unidad.</p>
    </div>

    <a href="{{ route('unidad_medida.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<div class="form-card">

<form action="{{ route('unidad_medida.store') }}" method="POST" class="form-wrapper">

@csrf

<div class="row mb-3 align-items-center">
<label class="col-md-4 fw-semibold">Nombre</label>
<div class="col-md-8">
<input name="nombre" type="text" class="form-control" placeholder="Ej. metro, kg, pieza" required>
</div>
</div>

<div class="row mb-4 align-items-center">
<label class="col-md-4 fw-semibold">Descripción</label>
<div class="col-md-8">
<input name="descripcion" type="text" class="form-control" placeholder="Descripción de la unidad">
</div>
</div>

<div class="d-flex justify-content-center">
<button type="submit" class="btn btn-secondary px-4">
<i class="bi bi-plus-circle me-2"></i> Agregar Unidad
</button>
</div>

</form>

</div>

@endsection