@extends('layout')

@section('title','Editar Proyecto')

@section('content')
    <style>
        .page-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
            gap:16px;
            margin-bottom:20px;
        }

        .page-title{
            font-size:2rem;
            font-weight:800;
            color:#1f2937;
            margin-bottom:4px;
        }

        .page-subtitle{
            color:#6b7280;
            margin:0;
            font-size:.98rem;
        }

        .form-card{
            max-width:1100px;
            margin:0 auto;
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:24px;
            box-shadow:0 10px 30px rgba(0,0,0,.05);
            padding:30px;
        }

        .section-title{
            font-size:1.08rem;
            font-weight:800;
            color:#111827;
            margin-bottom:18px;
            padding-bottom:10px;
            border-bottom:1px solid #e5e7eb;
        }

        .form-label{
            font-weight:700;
            color:#374151;
            margin-bottom:8px;
        }

        .form-control,
        .form-select{
            border-radius:12px;
            border:1px solid #d1d5db;
            padding:.78rem .95rem;
            box-shadow:none;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:#9ca3af;
            box-shadow:0 0 0 .15rem rgba(107,114,128,.15);
        }

        .radio-group{
            background:#f9fafb;
            border:1px solid #e5e7eb;
            border-radius:14px;
            padding:14px 18px;
        }

        .btn-back{
            border-radius:12px;
            padding:.65rem 1rem;
            font-weight:600;
        }

        .btn-save{
            border:none;
            border-radius:12px;
            padding:.80rem 1.25rem;
            font-weight:700;
            background:#6b7280;
            color:#fff;
        }

        .btn-save:hover{
            background:#4b5563;
            color:#fff;
        }

        .btn-cancel{
            border-radius:12px;
            padding:.80rem 1.25rem;
            font-weight:700;
        }

        .file-note{
            display:block;
            margin-top:6px;
            font-size:.84rem;
            color:#6b7280;
        }
    </style>

    @php
        $cliente = $proyecto->cliente;
        $responsable = $proyecto->responsableTecnico;
        $empresa = data_get($proyecto, 'responsableTecnico.empresa');

        $tipoPersonaActual = old('tipo_persona', $cliente->tipo_persona ?? 'fisica');
        $tipoPersonaActual = in_array(mb_strtolower($tipoPersonaActual), ['moral']) ? 'Moral' : 'Física';

        $fechaInicio = old(
            'obra_fecha_inicio',
            !empty($proyecto->fecha_inicio)
                ? \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('Y-m-d')
                : null
        );
    @endphp

    <div class="page-header">
        <div>
            <h2 class="page-title">Editar Proyecto</h2>
            <p class="page-subtitle">Modifica la información general del proyecto.</p>
        </div>

        <a href="{{ route('proyectos.index') }}" class="btn btn-outline-secondary btn-back">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="{{ route('proyectos.update', $proyecto->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger rounded-4 mb-4">
                    <strong>Corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @error('general')
                <div class="alert alert-danger rounded-4 mb-4">
                    {{ $message }}
                </div>
            @enderror

            <h5 class="section-title">Datos del Cliente</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="cliente_nombre" class="form-label">Nombre</label>
                    <input
                        type="text"
                        name="cliente_nombre"
                        id="cliente_nombre"
                        class="form-control @error('cliente_nombre') is-invalid @enderror"
                        value="{{ old('cliente_nombre', $cliente->nombre ?? '') }}"
                        placeholder="Ingresa el nombre del cliente"
                    >
                    @error('cliente_nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="cliente_razon_social" class="form-label">Razón Social</label>
                    <input
                        type="text"
                        name="cliente_razon_social"
                        id="cliente_razon_social"
                        class="form-control @error('cliente_razon_social') is-invalid @enderror"
                        value="{{ old('cliente_razon_social', $cliente->razon_social ?? '') }}"
                        placeholder="Ingresa la razón social"
                    >
                    @error('cliente_razon_social')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="cliente_direccion" class="form-label">Dirección</label>
                    <input
                        type="text"
                        name="cliente_direccion"
                        id="cliente_direccion"
                        class="form-control @error('cliente_direccion') is-invalid @enderror"
                        value="{{ old('cliente_direccion', $cliente->direccion ?? '') }}"
                        placeholder="Ingresa la dirección"
                    >
                    @error('cliente_direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="cliente_telefono" class="form-label">Teléfono</label>
                    <input
                        type="text"
                        name="cliente_telefono"
                        id="cliente_telefono"
                        class="form-control @error('cliente_telefono') is-invalid @enderror"
                        value="{{ old('cliente_telefono', $cliente->telefono ?? '') }}"
                        placeholder="Ingresa el teléfono"
                    >
                    @error('cliente_telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="cliente_correo" class="form-label">Correo</label>
                    <input
                        type="email"
                        name="cliente_correo"
                        id="cliente_correo"
                        class="form-control @error('cliente_correo') is-invalid @enderror"
                        value="{{ old('cliente_correo', $cliente->correo ?? '') }}"
                        placeholder="Ingresa el correo"
                    >
                    @error('cliente_correo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="cliente_rfc" class="form-label">RFC</label>
                    <input
                        type="text"
                        name="cliente_rfc"
                        id="cliente_rfc"
                        class="form-control @error('cliente_rfc') is-invalid @enderror"
                        value="{{ old('cliente_rfc', $cliente->rfc ?? '') }}"
                        placeholder="Ingresa el RFC"
                    >
                    @error('cliente_rfc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="radio-group d-flex align-items-center gap-4 flex-wrap mb-4">
                <span class="fw-bold text-dark">Persona</span>

                <div class="form-check mb-0">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="tipo_persona"
                        id="fisica_edit"
                        value="Física"
                        {{ $tipoPersonaActual === 'Física' ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="fisica_edit">Física</label>
                </div>

                <div class="form-check mb-0">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="tipo_persona"
                        id="moral_edit"
                        value="Moral"
                        {{ $tipoPersonaActual === 'Moral' ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="moral_edit">Moral</label>
                </div>
            </div>

            <h5 class="section-title">Datos de la Obra</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="obra_nombre" class="form-label">Nombre del proyecto</label>
                    <input
                        type="text"
                        name="obra_nombre"
                        id="obra_nombre"
                        class="form-control @error('obra_nombre') is-invalid @enderror"
                        value="{{ old('obra_nombre', $proyecto->nombre ?? '') }}"
                        placeholder="Ingresa el nombre del proyecto"
                    >
                    @error('obra_nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="obra_ubicacion" class="form-label">Ubicación del proyecto</label>
                    <input
                        type="text"
                        name="obra_ubicacion"
                        id="obra_ubicacion"
                        class="form-control @error('obra_ubicacion') is-invalid @enderror"
                        value="{{ old('obra_ubicacion', $proyecto->ubicacion ?? '') }}"
                        placeholder="Ingresa la ubicación"
                    >
                    @error('obra_ubicacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="obra_tipo" class="form-label">Tipo de obra</label>
                    <input
                        type="text"
                        name="obra_tipo"
                        id="obra_tipo"
                        class="form-control @error('obra_tipo') is-invalid @enderror"
                        value="{{ old('obra_tipo', $proyecto->tipo_obra ?? '') }}"
                        placeholder="Ingresa el tipo de obra"
                    >
                    @error('obra_tipo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="obra_superficie" class="form-label">Superficie de terreno</label>
                    <input
                        type="text"
                        name="obra_superficie"
                        id="obra_superficie"
                        class="form-control @error('obra_superficie') is-invalid @enderror"
                        value="{{ old('obra_superficie', $proyecto->superficie_terreno ?? '') }}"
                        placeholder="Ingresa la superficie"
                    >
                    @error('obra_superficie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="obra_uso" class="form-label">Tipo de uso</label>
                    <input
                        type="text"
                        name="obra_uso"
                        id="obra_uso"
                        class="form-control @error('obra_uso') is-invalid @enderror"
                        value="{{ old('obra_uso', $proyecto->tipo_uso ?? '') }}"
                        placeholder="Ingresa el tipo de uso"
                    >
                    @error('obra_uso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="obra_fecha_inicio" class="form-label">Fecha de inicio estimada</label>
                    <input
                        type="date"
                        name="obra_fecha_inicio"
                        id="obra_fecha_inicio"
                        class="form-control @error('obra_fecha_inicio') is-invalid @enderror"
                        value="{{ $fechaInicio }}"
                    >
                    @error('obra_fecha_inicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="obra_duracion" class="form-label">Duración estimada</label>
                    <input
                        type="text"
                        name="obra_duracion"
                        id="obra_duracion"
                        class="form-control @error('obra_duracion') is-invalid @enderror"
                        value="{{ old('obra_duracion', $proyecto->duracion_estimada ?? '') }}"
                        placeholder="Ingresa la duración estimada"
                    >
                    @error('obra_duracion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="estado_proyecto_id" class="form-label">Estado del proyecto</label>
                    <select
                        name="estado_proyecto_id"
                        id="estado_proyecto_id"
                        class="form-select @error('estado_proyecto_id') is-invalid @enderror"
                        required
                    >
                        <option value="">Selecciona un estado</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}" {{ old('estado_proyecto_id', $proyecto->estado_proyecto_id) == $estado->id ? 'selected' : '' }}>
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado_proyecto_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h5 class="section-title">Datos de la Empresa</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="empresa_nombre" class="form-label">Nombre</label>
                    <input
                        type="text"
                        name="empresa_nombre"
                        id="empresa_nombre"
                        class="form-control @error('empresa_nombre') is-invalid @enderror"
                        value="{{ old('empresa_nombre', $empresa->nombre ?? '') }}"
                        placeholder="Ingresa el nombre de la empresa"
                    >
                    @error('empresa_nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="empresa_logo" class="form-label">Logo</label>
                    <input
                        type="file"
                        name="empresa_logo"
                        id="empresa_logo"
                        class="form-control @error('empresa_logo') is-invalid @enderror"
                        accept="image/*"
                    >
                    @if(!empty($empresa->logo_path))
                        <small class="file-note">Archivo actual: {{ basename($empresa->logo_path) }}</small>
                    @endif
                    @error('empresa_logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="empresa_direccion" class="form-label">Dirección</label>
                    <input
                        type="text"
                        name="empresa_direccion"
                        id="empresa_direccion"
                        class="form-control @error('empresa_direccion') is-invalid @enderror"
                        value="{{ old('empresa_direccion', $empresa->direccion ?? '') }}"
                        placeholder="Ingresa la dirección de la empresa"
                    >
                    @error('empresa_direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="empresa_responsable" class="form-label">Responsable técnico</label>
                    <input
                        type="text"
                        name="empresa_responsable"
                        id="empresa_responsable"
                        class="form-control @error('empresa_responsable') is-invalid @enderror"
                        value="{{ old('empresa_responsable', $responsable->nombre ?? '') }}"
                        placeholder="Ingresa el responsable técnico"
                    >
                    @error('empresa_responsable')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="empresa_cargo" class="form-label">Cargo</label>
                    <input
                        type="text"
                        name="empresa_cargo"
                        id="empresa_cargo"
                        class="form-control @error('empresa_cargo') is-invalid @enderror"
                        value="{{ old('empresa_cargo', $responsable->cargo ?? '') }}"
                        placeholder="Ingresa el cargo"
                    >
                    @error('empresa_cargo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="firma_digital" class="form-label">Firma digital</label>
                    <input
                        type="file"
                        name="firma_digital"
                        id="firma_digital"
                        class="form-control @error('firma_digital') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.pdf"
                    >
                    @if(!empty($responsable->firma_path))
                        <small class="file-note">Archivo actual: {{ basename($responsable->firma_path) }}</small>
                    @endif
                    @error('firma_digital')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('proyectos.index') }}" class="btn btn-outline-secondary btn-cancel">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-save me-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection