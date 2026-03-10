@extends('layout')

@section('title','Editar Proyecto')

@section('content')
    <style>
        .form-card{
            max-width: 980px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid rgba(0,0,0,.18);
            padding: 32px;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h2 class="fw-bold mb-1">Editar Proyecto</h2>
            <p class="text-secondary mb-0">Modifica la información general del proyecto.</p>
        </div>

        <a href="{{ route('proyectos') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf

            <h5 class="fw-bold mb-3">Datos del Cliente</h5>

            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="cliente_nombre" class="form-control" value="Juan Pérez">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Razón Social</label>
                    <input type="text" name="cliente_razon_social" class="form-control" value="Constructora JP">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dirección</label>
                    <input type="text" name="cliente_direccion" class="form-control" value="Av. Principal 123">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Teléfono</label>
                    <input type="text" name="cliente_telefono" class="form-control" value="7221234567">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Correo</label>
                    <input type="email" name="cliente_correo" class="form-control" value="cliente@demo.com">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">RFC</label>
                    <input type="text" name="cliente_rfc" class="form-control" value="JUAP900101ABC">
                </div>
            </div>

            <div class="d-flex align-items-center gap-4 mt-2 mb-4">
                <span class="fw-semibold">Persona</span>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_persona" id="fisica" checked>
                    <label class="form-check-label" for="fisica">Física</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_persona" id="moral">
                    <label class="form-check-label" for="moral">Moral</label>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Datos de la obra</h5>

            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre del proyecto</label>
                    <input type="text" name="obra_nombre" class="form-control" value="Casa Habitación">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ubicación del proyecto</label>
                    <input type="text" name="obra_ubicacion" class="form-control" value="Toluca, Edo. Méx.">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipo de obra</label>
                    <input type="text" name="obra_tipo" class="form-control" value="Residencial">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Superficie de terreno</label>
                    <input type="text" name="obra_superficie" class="form-control" value="250 m²">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipo de uso</label>
                    <input type="text" name="obra_uso" class="form-control" value="Habitacional">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fecha de inicio estimada</label>
                    <input type="date" name="obra_fecha_inicio" class="form-control" value="2026-03-12">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Duración estimada</label>
                    <input type="text" name="obra_duracion" class="form-control" value="6 meses">
                </div>
            </div>

            <h5 class="fw-bold mt-4 mb-3">Datos de la empresa</h5>

            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="empresa_nombre" class="form-control" value="Akraka Estudio">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Logo</label>
                    <input type="file" name="empresa_logo" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dirección</label>
                    <input type="text" name="empresa_direccion" class="form-control" value="Centro, Toluca">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Responsable técnico</label>
                    <input type="text" name="empresa_responsable" class="form-control" value="Ing. Carlos Martínez">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cargo</label>
                    <input type="text" name="empresa_cargo" class="form-control" value="Supervisor de obra">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Firma digital</label>
                    <input type="file" name="firma_digital" class="form-control">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-secondary px-4">
                    <i class="bi bi-save me-2"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>
@endsection
