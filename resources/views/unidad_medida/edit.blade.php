@extends('layout')

@section('title', 'Editar Unidad')

@section('content')

<style>
.edit-wrapper{
    max-width: 900px;
    margin: 0 auto;
}

.edit-header{
    display:flex;
    justify-content:space-between;
    align-items:start;
    gap:12px;
    flex-wrap:wrap;
    margin-bottom:20px;
}

.edit-card{
    background:#fff;
    border:1px solid rgba(0,0,0,.18);
    padding:32px;
}

.edit-form{
    max-width:620px;
    margin:0 auto;
}

.edit-form .form-label{
    font-weight:600;
    color:#222;
}

.actions-bottom{
    display:flex;
    justify-content:center;
    margin-top:24px;
}
</style>

<div class="edit-wrapper">

<div class="edit-header">

<div>
<h2 class="fw-bold mb-1">Editar Unidad de Medida</h2>
<p class="text-secondary mb-0">Modifica la información de la unidad.</p>
</div>

<a href="{{ route('unidad_medida.index') }}" class="btn btn-outline-secondary">
<i class="bi bi-arrow-left me-1"></i> Volver
</a>

</div>


<div class="edit-card">

<form class="edit-form" method="POST" action="{{ route('unidad_medida.update',$unidad->id_unidad) }}">

@csrf
@method('PUT')

<div class="row mb-3 align-items-center">

<label class="col-md-4 form-label">Nombre</label>

<div class="col-md-8">
<input
type="text"
name="nombre"
class="form-control"
value="{{ $unidad->nombre }}"
required>
</div>

</div>


<div class="row mb-3 align-items-center">

<label class="col-md-4 form-label">Descripción</label>

<div class="col-md-8">
<input
type="text"
name="descripcion"
class="form-control"
value="{{ $unidad->descripcion }}">
</div>

</div>


<div class="actions-bottom">

<button type="submit" class="btn btn-secondary px-4">
<i class="bi bi-save me-2"></i> Guardar cambios
</button>

</div>

</form>

</div>

</div>

@endsection