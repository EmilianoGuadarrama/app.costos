@extends('layout')
@section('title','Estados de Proyecto')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .dash-index-view{ min-height:100%; background:#f8f8f8; font-family:"Garamond","Baskerville",serif; color:#111; padding:20px; }
    .index-panel{ background:#fff; padding:40px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); max-width:800px; margin:0 auto; }
    .header-section{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:30px; display:flex; justify-content:space-between; align-items:flex-end; gap:16px; flex-wrap:wrap; }
    .header-section h1{ font-size:2.2rem; font-weight:700; margin:0; }
    .header-actions{ display:flex; gap:10px; flex-wrap:wrap; }
    .btn-add-new{ background:#111; color:#fff; border:none; padding:10px 20px; border-radius:6px; font-size:.8rem; letter-spacing:1px; text-transform:uppercase; text-decoration:none; font-family:Arial,sans-serif; }
    .btn-add-new:hover{ background:#333; color:#fff; }
    .project-table{ width:100%; border-collapse:separate; border-spacing:0 12px; }
    .project-table thead th{ text-align:left; color:#888; font-size:.75rem; letter-spacing:2px; text-transform:uppercase; padding:0 20px 10px; font-family:Arial,sans-serif; }
    .project-row{ background:#fff; outline:1px solid #eee; transition:all .3s ease; }
    .project-row:hover{ transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,.05); }
    .project-row td{ padding:15px 20px; vertical-align:middle; font-family:Arial,sans-serif; }
    .action-cell{ text-align:right; white-space:nowrap; }
    .btn-icon-action{ background:none; border:none; font-size:1.15rem; cursor:pointer; transition:transform .2s ease, color .3s ease; padding:5px; margin-left:10px; display:inline-flex; align-items:center; justify-content:center; color:#888; text-decoration:none; }
    .btn-icon-action:hover{ transform:scale(1.15); color:#111; }
    .empty-state{ text-align:center; padding:28px 18px !important; color:#777; font-style:italic; font-family:Arial,sans-serif; background:#fff; }
</style>
<div class="dash-index-view">
    <div class="index-panel">
        <div class="header-section">
            <div><h1>Estados de Proyecto</h1></div>
            <div class="header-actions">
                <a href="{{ route('estados_proyecto.create') }}" class="btn-add-new"><i class="bi bi-plus-circle me-1"></i> Nuevo Estado</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="project-table">
                <thead><tr><th>ID</th><th>Nombre</th><th style="text-align:right;">Acciones</th></tr></thead>
                <tbody>
                @forelse($estados as $estado)
                    <tr class="project-row">
                        <td>{{ $estado->id }}</td>
                        <td><strong>{{ $estado->nombre }}</strong></td>
                        <td class="action-cell">
                            <a href="{{ route('estados_proyecto.show', $estado) }}" class="btn-icon-action"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('estados_proyecto.edit', $estado) }}" class="btn-icon-action"><i class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('estados_proyecto.destroy', $estado) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar estado?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon-action"><i class="bi bi-trash3"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="project-row"><td colspan="3" class="empty-state">No hay estados registrados por el momento.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
