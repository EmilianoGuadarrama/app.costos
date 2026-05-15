@extends('layout')
@section('title', 'Empleados — App Costos')
@section('content')
<style>
.emp-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; }
.emp-title { font-size:1.85rem; font-weight:800; color:#111; margin:0; }
.btn-nuevo { background:#111827; color:#fff; border-radius:10px; padding:.65rem 1.4rem; font-size:.9rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
.btn-nuevo:hover { background:#374151; color:#fff; }

.emp-table { width:100%; border-collapse:collapse; font-size:.85rem; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.04); }
.emp-table th { background:#f9fafb; color:#6b7280; font-size:.7rem; text-transform:uppercase; letter-spacing:1px; padding:12px 16px; text-align:left; border-bottom:1px solid #e5e7eb; }
.emp-table td { padding:14px 16px; border-bottom:1px solid #f3f4f6; color:#374151; }
.emp-table tr:last-child td { border-bottom:none; }
.emp-table tr:hover td { background:#f9fafb; }

.emp-name { font-weight:700; color:#111; font-size:.9rem; display:block; }
.emp-rol { font-size:.75rem; color:#6b7280; text-transform:uppercase; letter-spacing:.5px; margin-top:2px; display:block; }

.btn-acc { color:#9ca3af; background:none; border:none; padding:4px 8px; border-radius:6px; cursor:pointer; font-size:1rem; transition:all .2s; }
.btn-acc:hover { background:#f3f4f6; color:#111; }
.btn-acc.danger:hover { color:#dc2626; background:#fef2f2; }
</style>

<div class="emp-header">
    <h1 class="emp-title">Empleados Registrados</h1>
    <a href="{{ route('empleados.create') }}" class="btn-nuevo"><i class="bi bi-person-plus"></i> Nuevo Empleado</a>
</div>

@if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:10px; margin-bottom:16px;">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    </div>
@endif

@if($empleados->isEmpty())
    <div style="text-align:center; padding:60px 20px; color:#9ca3af;">
        <i class="bi bi-people" style="font-size:3rem; margin-bottom:16px; display:block;"></i>
        <h4>No hay empleados</h4>
        <p>Aún no has registrado a ningún empleado en el sistema.</p>
    </div>
@else
    <div style="overflow-x:auto;">
        <table class="emp-table">
            <thead>
                <tr>
                    <th>Nombre y Rol</th>
                    <th>Contacto</th>
                    <th>Salario Base</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $emp)
                <tr>
                    <td>
                        <span class="emp-name">{{ $emp->persona?->nombre }} {{ $emp->persona?->apellido_paterno }}</span>
                        <span class="emp-rol">{{ $emp->rol ?: 'Sin rol definido' }}</span>
                    </td>
                    <td>
                        @if($emp->persona?->email)<div style="margin-bottom:4px;"><i class="bi bi-envelope me-1 text-muted"></i>{{ $emp->persona->email }}</div>@endif
                        @if($emp->persona?->telefono_1)<div><i class="bi bi-telephone me-1 text-muted"></i>{{ $emp->persona->telefono_1 }}</div>@endif
                        @if(!$emp->persona?->email && !$emp->persona?->telefono_1) <span style="color:#9ca3af;font-size:.8rem;">Sin contacto</span> @endif
                    </td>
                    <td style="font-weight:600;">
                        {{ $emp->salario_base ? '$'.number_format($emp->salario_base, 2) : '—' }}
                    </td>
                    <td style="display:flex;gap:4px;">
                        <a href="{{ route('empleados.edit', $emp->id) }}" class="btn-acc" title="Editar"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('empleados.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este empleado?');" style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-acc danger" title="Eliminar"><i class="bi bi-trash3"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
