@extends('layout')
@section('title', 'Editar Empleado')
@section('content')
<style>
.form-wrap { max-width:600px; margin:0 auto; background:#fff; padding:30px; border-radius:14px; border:1px solid #e5e7eb; box-shadow:0 2px 10px rgba(0,0,0,.04); }
.form-title { font-size:1.6rem; font-weight:800; margin:0 0 20px; color:#111; }
.btn-back { display:inline-flex; align-items:center; gap:5px; color:#6b7280; text-decoration:none; font-size:.85rem; margin-bottom:16px; }
.btn-back:hover { color:#111; }

.f-lbl { display:block; font-size:.8rem; font-weight:700; color:#374151; margin-bottom:6px; }
.f-ctrl { width:100%; padding:.6rem .8rem; border:1.5px solid #e5e7eb; border-radius:8px; font-size:.9rem; margin-bottom:16px; }
.f-ctrl:focus { border-color:#2563eb; outline:none; box-shadow:0 0 0 3px rgba(37,99,235,.1); }

.f-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

.btn-guardar { width:100%; background:#111827; color:#fff; border:none; padding:.8rem; border-radius:10px; font-weight:700; font-size:.95rem; cursor:pointer; transition:background .2s; }
.btn-guardar:hover { background:#374151; }

.err-list { background:#fef2f2; color:#b91c1c; padding:12px 16px; border-radius:8px; border:1px solid #fecaca; margin-bottom:16px; font-size:.85rem; }
</style>

<div style="max-width:600px; margin:0 auto;">
    <a href="{{ route('empleados.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver a Empleados</a>

    <div class="form-wrap">
        <h1 class="form-title">Editar Empleado</h1>

        @if($errors->any())
        <div class="err-list">
            <ul style="margin:0; padding-left:16px;">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="f-grid">
                <div>
                    <label class="f-lbl">Nombre(s) *</label>
                    <input type="text" name="nombre" class="f-ctrl" value="{{ old('nombre', $empleado->persona?->nombre) }}" required>
                </div>
                <div>
                    <label class="f-lbl">Apellidos</label>
                    <input type="text" name="apellido_paterno" class="f-ctrl" value="{{ old('apellido_paterno', $empleado->persona?->apellido_paterno) }}">
                </div>
            </div>

            <div class="f-grid">
                <div>
                    <label class="f-lbl">Rol / Cargo</label>
                    <input type="text" name="rol" class="f-ctrl" value="{{ old('rol', $empleado->rol) }}">
                </div>
                <div>
                    <label class="f-lbl">Salario Base ($)</label>
                    <input type="number" step="0.01" name="salario_base" class="f-ctrl" value="{{ old('salario_base', $empleado->salario_base) }}">
                </div>
            </div>

            <hr style="border:0; border-top:1px solid #e5e7eb; margin:10px 0 20px;">

            <div class="f-grid">
                <div>
                    <label class="f-lbl">Teléfono</label>
                    <input type="text" name="telefono_1" class="f-ctrl" value="{{ old('telefono_1', $empleado->persona?->telefono_1) }}">
                </div>
                <div>
                    <label class="f-lbl">Email</label>
                    <input type="email" name="email" class="f-ctrl" value="{{ old('email', $empleado->persona?->email) }}">
                </div>
            </div>

            <button type="submit" class="btn-guardar">Actualizar Empleado</button>
        </form>
    </div>
</div>
@endsection
