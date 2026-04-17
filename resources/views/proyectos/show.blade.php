@extends('layout')

@section('title','Detalle del Proyecto')

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

        .detail-card{
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

        .form-control[readonly]{
            background:#f9fafb;
            border:1px solid #d1d5db;
            border-radius:12px;
            padding:.78rem .95rem;
            color:#374151;
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

        .btn-edit{
            border:none;
            border-radius:12px;
            padding:.75rem 1.1rem;
            font-weight:700;
            background:#6b7280;
            color:#fff;
        }

        .btn-edit:hover{
            background:#4b5563;
            color:#fff;
        }
    </style>

    <div class="page-header">
        <div>
            <h2 class="page-title">Detalle del Proyecto</h2>
            <p class="page-subtitle">Consulta la información general registrada del proyecto.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('proyectos.index') }}" class="btn btn-outline-secondary btn-back">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            <a href="{{ route('proyectos.edit', $proyecto->id ?? 1) }}" class="btn btn-edit">
                <i class="bi bi-pencil-square me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="detail-card">
        <h5 class="section-title">Datos del Cliente</h5>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->cliente_nombre ?? 'Juan Pérez' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Razón Social</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->cliente_razon_social ?? 'Constructora JP' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->cliente_direccion ?? 'Av. Principal 123' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->cliente_telefono ?? '7221234567' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->cliente_correo ?? 'cliente@demo.com' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">RFC</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->cliente_rfc ?? 'JUAP900101ABC' }}">
            </div>
        </div>

        <div class="radio-group d-flex align-items-center gap-4 flex-wrap mb-4">
            <span class="fw-bold text-dark">Persona</span>

            <div class="form-check mb-0">
                <input class="form-check-input" type="radio" disabled checked>
                <label class="form-check-label">Física</label>
            </div>

            <div class="form-check mb-0">
                <input class="form-check-input" type="radio" disabled>
                <label class="form-check-label">Moral</label>
            </div>
        </div>

        <h5 class="section-title">Datos de la Obra</h5>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Nombre del proyecto</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->obra_nombre ?? 'Casa Habitación' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ubicación del proyecto</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->obra_ubicacion ?? 'Toluca, Edo. Méx.' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Tipo de obra</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->obra_tipo ?? 'Residencial' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Superficie de terreno</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->obra_superficie ?? '250 m²' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Tipo de uso</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->obra_uso ?? 'Habitacional' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Fecha de inicio estimada</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->obra_fecha_inicio ?? '2026-03-12' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Duración estimada</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->obra_duracion ?? '6 meses' }}">
            </div>
        </div>

        <h5 class="section-title">Datos de la Empresa</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->empresa_nombre ?? 'Akiraka Estudio' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Logo</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->empresa_logo ?? 'logo_empresa.png' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->empresa_direccion ?? 'Centro, Toluca' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Responsable técnico</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->empresa_responsable ?? 'Ing. Carlos Martínez' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Cargo</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->empresa_cargo ?? 'Supervisor de obra' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Firma digital</label>
                <input type="text" class="form-control" readonly value="{{ $proyecto->firma_digital ?? 'firma_digital.png' }}">
            </div>
        </div>
    </div>
@endsection
