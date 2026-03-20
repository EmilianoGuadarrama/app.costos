@extends('layout')

@section('title','Unidades de Medida')

@section('content')

<style>
.tbl-grid{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
    font-size:.92rem;
    background:#fff;
}
.tbl-grid th,.tbl-grid td{
    border:1px solid rgba(0,0,0,.35);
    padding:10px 8px;
    vertical-align:middle;
    background:#fff;
}
.tbl-grid thead th{
    text-transform:uppercase;
    letter-spacing:.04em;
    font-size:.72rem;
    font-weight:800;
    text-align:center;
    padding:8px 6px;
}
.actions{
    display:flex;
    justify-content:flex-end;
    gap:10px;
}
.icon-btn{
    width:28px;
    height:28px;
    display:grid;
    place-items:center;
    border:0;
    background:transparent;
    border-radius:4px;
    padding:0;
    color:#111;
    text-decoration:none;
}
.icon-btn:hover{ background:rgba(0,0,0,.06); color:#111; }
.tbl-grid tbody td{ height:44px; }
</style>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h4 class="fw-bold mb-1">Unidades de Medida</h4>
        <div class="text-secondary small">Listado general de unidades.</div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('unidad_medida.create') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-plus-circle me-1"></i> Nueva Unidad
        </a>
    </div>
</div>

<div class="table-responsive">
<table class="tbl-grid">

<thead>
<tr>
<th>ID</th>
<th>NOMBRE</th>
<th>DESCRIPCIÓN</th>
<th>ACCIONES</th>
</tr>
</thead>

<tbody>

@foreach($unidades as $unidad)
<tr>

<td>{{ $unidad->id_unidad }}</td>
<td>{{ $unidad->nombre }}</td>
<td>{{ $unidad->descripcion }}</td>

<td>
<div class="actions">

<a class="icon-btn"
href="{{ route('unidad_medida.edit',$unidad->id_unidad) }}"
title="Editar">
<i class="bi bi-pencil"></i>
</a>

<form action="{{ route('unidad_medida.destroy',$unidad->id_unidad) }}" method="POST">
@csrf
@method('DELETE')

<button class="icon-btn" type="submit" title="Eliminar">
<i class="bi bi-trash"></i>
</button>

</form>

</div>
</td>

</tr>
@endforeach

</tbody>
</table>
</div>

@endsection