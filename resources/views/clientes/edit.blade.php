@extends('layout')
@section('title','Editar Cliente')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .dash-form-view{ min-height:100%; background:#f8f8f8; font-family:"Arial",sans-serif; color:#111; padding:20px; }
    .form-panel{ background:#fff; padding:40px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); max-width:700px; margin:0 auto; }
    .header-section{ border-bottom:1px solid #eaeaea; padding-bottom:20px; margin-bottom:30px; }
    .header-section h1{ font-size:1.8rem; font-weight:700; margin:0; font-family:"Garamond","Baskerville",serif; }
    .section-label{ font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:2px; color:#9ca3af; margin:24px 0 14px; border-top:1px solid #f0f0f0; padding-top:16px; }
    .form-group{ margin-bottom:18px; }
    .form-group label{ display:block; margin-bottom:8px; font-weight:600; font-size:.9rem; color:#333; }
    .form-control,.form-select{ width:100%; padding:10px 15px; border:1px solid #ccc; border-radius:6px; font-size:1rem; }
    .form-grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .btn-submit{ background:#111; color:#fff; border:none; padding:12px 25px; border-radius:6px; font-size:.9rem; font-weight:600; cursor:pointer; width:100%; margin-top:8px; }
    .btn-submit:hover{ background:#333; }
    .btn-back{ display:inline-block; margin-bottom:20px; color:#666; text-decoration:none; font-size:.9rem; }
    .text-danger{ color:#dc3545; font-size:.85rem; margin-top:5px; display:block; }
    .alert-err{ background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:12px 16px; color:#b91c1c; font-size:.85rem; margin-bottom:16px; }
</style>
<div class="dash-form-view">
    <a href="{{ route('clientes.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver</a>
    <div class="form-panel">
        <div class="header-section"><h1>Editar Cliente</h1></div>

        @if($errors->any())
            <div class="alert-err"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
            @csrf @method('PUT')

            {{-- ── Datos del Cliente ──────────────────────────────── --}}
            <div class="section-label"><i class="bi bi-building me-1"></i> Datos del cliente</div>

            <div class="form-group">
                <label for="nombre_o_razon_social">Nombre / Razón Social</label>
                <input type="text" id="nombre_o_razon_social" name="nombre_o_razon_social" class="form-control"
                       value="{{ old('nombre_o_razon_social', $cliente->nombre_o_razon_social) }}" maxlength="255">
                @error('nombre_o_razon_social') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="cuenta_catastral">Cuenta Catastral</label>
                    <input type="text" id="cuenta_catastral" name="cuenta_catastral" class="form-control"
                           value="{{ old('cuenta_catastral', $cliente->cuenta_catastral) }}" maxlength="100">
                    @error('cuenta_catastral') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="uso_suelo">Uso de Suelo</label>
                    <input type="text" id="uso_suelo" name="uso_suelo" class="form-control"
                           value="{{ old('uso_suelo', $cliente->uso_suelo) }}" maxlength="150">
                    @error('uso_suelo') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- ── Datos Personales (Persona) ─────────────────────── --}}
            <div class="section-label"><i class="bi bi-person me-1"></i> Datos personales</div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                           value="{{ old('nombre', $cliente->persona?->nombre) }}" required maxlength="150">
                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="apellido_paterno">Apellido Paterno</label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control"
                           value="{{ old('apellido_paterno', $cliente->persona?->apellido_paterno) }}" maxlength="150">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="apellido_materno">Apellido Materno</label>
                    <input type="text" id="apellido_materno" name="apellido_materno" class="form-control"
                           value="{{ old('apellido_materno', $cliente->persona?->apellido_materno) }}" maxlength="150">
                </div>
                <div class="form-group">
                    <label for="rfc">RFC</label>
                    <input type="text" id="rfc" name="rfc" class="form-control"
                           value="{{ old('rfc', $cliente->persona?->rfc) }}" maxlength="13">
                    @error('rfc') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- ── Contacto ────────────────────────────────────────── --}}
            <div class="section-label"><i class="bi bi-telephone me-1"></i> Contacto</div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="telefono_1">Teléfono</label>
                    <input type="text" id="telefono_1" name="telefono_1" class="form-control"
                           value="{{ old('telefono_1', $cliente->persona?->telefono_1) }}" maxlength="20">
                    @error('telefono_1') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email', $cliente->persona?->email) }}" maxlength="255">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <button type="submit" class="btn-submit"><i class="bi bi-save me-1"></i> Actualizar Cliente</button>
        </form>
    </div>
</div>
@endsection
