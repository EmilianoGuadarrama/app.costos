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
    </style>

    <div class="page-header">
        <div>
            <h2 class="page-title">Editar Proyecto</h2>
            <p class="page-subtitle">Modifica la información general del proyecto.</p>
        </div>

        <a href="{{ route('proyectos') }}" class="btn btn-outline-secondary btn-back">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h5 class="section-title">Datos del Cliente</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="cliente_nombre" class="form-control" value="Juan Pérez">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Razón Social</label>
                    <input type="text" name="cliente_razon_social" class="form-control" value="Constructora JP">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="cliente_direccion" class="form-control" value="Av. Principal 123">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="cliente_telefono" class="form-control" value="7221234567">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo</label>
                    <input type="email" name="cliente_correo" class="form-control" value="cliente@demo.com">
                </div>

                <div class="col-md-6">
                    <label class="form-label">RFC</label>
                    <input type="text" name="cliente_rfc" class="form-control" value="JUAP900101ABC">
                </div>
            </div>

            <div class="radio-group d-flex align-items-center gap-4 flex-wrap mb-4">
                <span class="fw-bold text-dark">Persona</span>

                <div class="form-check mb-0">
                    <input class="form-check-input" type="radio" name="tipo_persona" id="fisica" value="Física" checked>
                    <label class="form-check-label" for="fisica">Física</label>
                </div>

                <div class="form-check mb-0">
                    <input class="form-check-input" type="radio" name="tipo_persona" id="moral" value="Moral">
                    <label class="form-check-label" for="moral">Moral</label>
                </div>
            </div>

            <h5 class="section-title">Datos de la Obra</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Nombre del proyecto</label>
                    <input type="text" name="obra_nombre" class="form-control" value="Casa Habitación">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ubicación del proyecto</label>
                    <input type="text" name="obra_ubicacion" class="form-control" value="Toluca, Edo. Méx.">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipo de obra</label>
                    <input type="text" name="obra_tipo" class="form-control" value="Residencial">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Superficie de terreno</label>
                    <input type="text" name="obra_superficie" class="form-control" value="250 m²">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipo de uso</label>
                    <input type="text" name="obra_uso" class="form-control" value="Habitacional">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fecha de inicio estimada</label>
                    <input type="date" name="obra_fecha_inicio" class="form-control" value="2026-03-12">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Duración estimada</label>
                    <input type="text" name="obra_duracion" class="form-control" value="6 meses">
                </div>
            </div>

            <h5 class="section-title">Datos de la Empresa</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="empresa_nombre" class="form-control" value="Akiraka Estudio">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Logo</label>
                    <input type="file" name="empresa_logo" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="empresa_direccion" class="form-control" value="Centro, Toluca">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Responsable técnico</label>
                    <input type="text" name="empresa_responsable" class="form-control" value="Ing. Carlos Martínez">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cargo</label>
                    <input type="text" name="empresa_cargo" class="form-control" value="Supervisor de obra">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Firma digital</label>
                    <input type="file" name="firma_digital" class="form-control">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('proyectos') }}" class="btn btn-outline-secondary btn-cancel">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-save me-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection
