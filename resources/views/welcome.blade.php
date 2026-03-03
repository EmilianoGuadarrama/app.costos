{{-- resources/views/welcome.blade.php --}}
@extends('layout')

@section('title','Inicio | App Precios Unitarios')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="w-100" style="max-width: 700px  ;">
            <div class="p-4">
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ================== DATOS DEL CLIENTE ================== --}}
                    <h6 class="fw-bold mb-3">Datos del Cliente</h6>

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nombre</label>
                            <input type="text" name="cliente_nombre" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Razón Social</label>
                            <input type="text" name="cliente_razon_social" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dirección</label>
                            <input type="text" name="cliente_direccion" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Teléfono</label>
                            <input type="text" name="cliente_telefono" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Correo</label>
                            <input type="email" name="cliente_correo" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">RFC</label>
                            <input type="text" name="cliente_rfc" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-2 mb-4">
                        <div class="small fw-semibold me-2">Persona</div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="radio" name="cliente_persona" id="persona_fisica" value="fisica" checked>
                            <label class="form-check-label small" for="persona_fisica">Física</label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="radio" name="cliente_persona" id="persona_moral" value="moral">
                            <label class="form-check-label small" for="persona_moral">Moral</label>
                        </div>
                    </div>

                    {{-- ================== DATOS DE LA OBRA ================== --}}
                    <h6 class="fw-bold mb-3">Datos de la obra</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nombre del proyecto</label>
                            <input type="text" name="obra_nombre" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Ubicación del proyecto</label>
                            <input type="text" name="obra_ubicacion" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tipo de obra</label>
                            <input type="text" name="obra_tipo" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Superficie de terreno</label>
                            <input type="text" name="obra_superficie" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tipo de uso</label>
                            <input type="text" name="obra_uso" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Fecha de inicio estimada</label>
                            <input type="date" name="obra_fecha_inicio" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Duración estimada</label>
                            <input type="text" name="obra_duracion" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                    </div>

                    {{-- ================== DATOS DE LA EMPRESA ================== --}}
                    <h6 class="fw-bold mb-3">Datos de la empresa</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nombre</label>
                            <input type="text" name="empresa_nombre" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Logo</label>
                            <input type="file" name="empresa_logo" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dirección</label>
                            <input type="text" name="empresa_direccion" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Responsable técnico</label>
                            <input type="text" name="empresa_responsable" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Cargo</label>
                            <input type="text" name="empresa_cargo" class="form-control form-control-sm" placeholder="Placeholder">
                        </div>
                    </div>

                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Firma digital</label>
                            <input type="file" name="firma_digital" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6 d-flex justify-content-end">
                            <button type="submit" class="btn btn-secondary btn-sm px-3">
                                <i class="bi bi-plus-circle me-2"></i> Agregar Información
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
