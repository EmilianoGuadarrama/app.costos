@extends('layout')
@section('title', 'Materiales - ' . ($obra->datosDeObra->nombre ?? 'Obra #' . $obra->id))

@section('content')
<style>
.pu-hdr {
    display: flex; justify-content: space-between; align-items: center;
    background: #fff; padding: 20px 25px; border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 25px;
}
.pu-hdr h1 { margin: 0; font-size: 1.6rem; font-weight: 800; color: #111; }
.btn-back {
    display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 700;
    color: #6b7280; background: #f3f4f6; padding: 8px 14px; border-radius: 8px;
    text-decoration: none; margin-bottom: 10px; transition: 0.2s;
}
.btn-back:hover { background: #e5e7eb; color: #111; }

.metric-card {
    background: #fff; border-radius: 12px; padding: 25px;
    border: 1px solid #e5e7eb; box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    margin-bottom: 25px;
}
.m-title { font-size: 1.2rem; font-weight: 800; color: #111; margin-bottom: 15px; border-bottom: 1px solid #f3f4f6; padding-bottom: 10px; display:flex; align-items:center; justify-content:space-between; }
</style>

<a href="{{ route('obras.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i> Volver a Mis Obras
</a>

<div class="pu-hdr">
    <div>
        <h1><i class="bi bi-bricks me-2" style="color:#9333ea;"></i> Materiales: {{ $obra->datosDeObra->nombre ?? 'Obra #' . $obra->id }}</h1>
        <p class="text-muted mb-0 mt-1" style="font-size: 0.9rem;">
            Control de compras de materiales requeridos y registrados.
        </p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="border-radius:10px; font-weight:600;">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="alert alert-danger" style="border-radius:10px; font-weight:600;">
    <ul class="mb-0">
    @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
</div>
@endif

<div class="metric-card">
    <div class="m-title">
        <span><i class="bi bi-list-check me-2 text-info"></i> Requerimientos de Materiales</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="table table-sm table-hover" style="font-size:0.85rem; vertical-align:middle;">
            <thead style="background: #f9fafb; color: #374151;">
                <tr>
                    <th style="padding: 10px;">Material</th>
                    <th class="text-center" style="padding: 10px;">Cant. Presupuestada</th>
                    <th class="text-center" style="padding: 10px;">Cant. Comprada</th>
                    <th class="text-center" style="padding: 10px;">Cant. Faltante</th>
                    <th class="text-end" style="padding: 10px;">Costo Presupuestado</th>
                    <th class="text-end" style="padding: 10px;">Monto Gastado</th>
                    <th class="text-end" style="padding: 10px;">Presupuesto Disp.</th>
                    <th class="text-center" style="padding: 10px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalCosto = 0; 
                    $totalGastado = 0; 
                @endphp
                @forelse($materialesPendientes as $nivelId => $nivelData)
                    <tr style="background:#f3f4f6;">
                        <td colspan="8" style="padding: 10px; font-weight: 900; font-size: 0.88rem; text-transform: uppercase;">
                            <i class="bi bi-layers me-2"></i>{{ mb_strtoupper($nivelData['nombre']) }}
                        </td>
                    </tr>
                    @foreach($nivelData['areas'] as $areaId => $areaData)
                        @if(!empty($areaData['materiales']))
                            <tr style="background:#f9fafb;">
                                <td colspan="8" style="padding: 7px 11px; padding-left: 28px; font-weight: 700; font-size: 0.74rem; text-transform: uppercase; color: #4b5563;">
                                    <i class="bi bi-geo-alt me-1"></i> {{ strtoupper($areaData['nombre']) }}
                                </td>
                            </tr>
                            @foreach($areaData['materiales'] as $matId => $data)
                                @php
                                    $faltante = max(0, $data['cantidad_total'] - $data['cantidad_comprada']);
                                    $disponible = $data['costo_total'] - $data['gastado'];
                                    $totalCosto += $data['costo_total'];
                                    $totalGastado += $data['gastado'];
                                    $isExcedido = $disponible < 0;
                                    $isCompradoCompleto = $faltante <= 0 && $data['cantidad_total'] > 0;
                                    $modalId = "modalComprar-{$nivelId}-{$areaId}-{$matId}";
                                @endphp
                                <tr>
                                    <td style="padding: 10px; font-weight: 600; padding-left:40px;">
                                        {{ $data['material']->nombre }}
                                        @if($data['material']->marca)
                                            <br><small class="text-muted">{{ $data['material']->marca }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center" style="padding: 10px;">{{ number_format($data['cantidad_total'], 2) }} {{ $data['material']->unidadMedida?->abreviatura }}</td>
                                    <td class="text-center {{ $data['cantidad_comprada'] > 0 ? 'text-success fw-bold' : 'text-muted' }}" style="padding: 10px;">
                                        {{ number_format($data['cantidad_comprada'], 2) }} {{ $data['material']->unidadMedida?->abreviatura }}
                                    </td>
                                    <td class="text-center fw-bold" style="padding: 10px; color: {{ $isCompradoCompleto ? '#059669' : '#dc2626' }}">
                                        {{ number_format($faltante, 2) }} {{ $data['material']->unidadMedida?->abreviatura }}
                                    </td>
                                    <td class="text-end text-secondary" style="padding: 10px;">${{ number_format($data['costo_total'], 2) }}</td>
                                    <td class="text-end {{ $data['gastado'] > 0 ? 'text-danger fw-bold' : 'text-muted' }}" style="padding: 10px;">
                                        ${{ number_format($data['gastado'], 2) }}
                                    </td>
                                    <td class="text-end fw-bold" style="padding: 10px; color: {{ $isExcedido ? '#dc2626' : '#059669' }}">
                                        ${{ number_format($disponible, 2) }}
                                    </td>
                                    <td class="text-center" style="padding: 10px;">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" style="font-weight: 600; padding: 4px 10px; border-radius: 8px;">
                                            <i class="bi bi-cart-plus me-1"></i> Comprar
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Comprar -->
                                <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Registrar Compra: {{ $data['material']->nombre }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('obras.materiales.storeCompra', $obra->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_material" value="{{ $matId }}">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Fecha de Compra</label>
                                                        <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Encargado de la Compra</label>
                                                        <select name="id_persona" class="form-select select-encargado" required>
                                                            <option value="">-- Seleccionar --</option>
                                                            @foreach($personas as $persona)
                                                                <option value="{{ $persona->id }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Forma de Pago</label>
                                                        <select name="id_pre_proveedor" class="form-select select-pago" onchange="toggleEncargado(this)">
                                                            <option value="">Directo (Afecta Egresos de la Obra)</option>
                                                            @foreach($proveedoresAprobados as $preProv)
                                                                <option value="{{ $preProv->id }}">Incluido en Presupuesto: {{ $preProv->proveedor->empresa ?? 'Proveedor S/N' }}</option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted">Selecciona un proveedor si esta compra ya fue pagada dentro de su presupuesto, para no duplicar el gasto en la obra.</small>
                                                        <small class="text-success d-none msg-encargado-prov mt-1"><i class="bi bi-info-circle"></i> El encargado será el proveedor.</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Cantidad Comprada ({{ $data['material']->unidadMedida?->abreviatura }})</label>
                                                        <input type="number" step="0.01" name="cantidad_material" class="form-control" value="{{ $faltante > 0 ? $faltante : '' }}" required min="0.01">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Valor del Material ($)</label>
                                                        <input type="number" step="0.01" name="pago" class="form-control" required min="0">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Registrar Compra</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No hay materiales pendientes de compra.</td>
                    </tr>
                @endforelse

                @if(isset($materialesCompletados) && count($materialesCompletados) > 0)
                    <tr>
                        <td colspan="8" style="padding: 20px 10px 10px 10px; font-weight: bold; font-size: 1.1rem; color: #059669; border-top: 2px solid #059669;">
                            <i class="bi bi-check-circle-fill me-2"></i> MATERIALES COMPLETADOS
                        </td>
                    </tr>
                    @foreach($materialesCompletados as $nivelId => $nivel)
                        <tr style="background: #e5e7eb;">
                            <td colspan="8" style="padding: 10px; font-weight: bold; font-size: 0.95rem; opacity: 0.8;">
                                <i class="bi bi-layers me-1"></i> NIVEL: {{ mb_strtoupper($nivel['nombre']) }}
                            </td>
                        </tr>
                        @foreach($nivel['areas'] as $areaId => $area)
                            <tr style="background: #f3f4f6;">
                                <td colspan="8" style="padding: 8px 10px 8px 25px; font-weight: 600; font-size: 0.85rem; color: #4b5563; opacity: 0.8;">
                                    <i class="bi bi-geo-alt me-1"></i> ÁREA: {{ mb_strtoupper($area['nombre']) }}
                                </td>
                            </tr>
                            @foreach($area['materiales'] as $matId => $data)
                                @php
                                    $faltante = max(0, $data['cantidad_total'] - $data['cantidad_comprada']);
                                    $disponible = $data['costo_total'] - $data['gastado'];
                                    $totalCosto += $data['costo_total'];
                                    $totalGastado += $data['gastado'];
                                    $isExcedido = $disponible < 0;
                                @endphp
                                <tr style="opacity: 0.7;">
                                    <td style="padding: 10px 10px 10px 35px; font-weight: 600;">
                                        {{ $data['material']->nombre }}
                                        @if($data['material']->marca)
                                            <br><small class="text-muted">{{ $data['material']->marca }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center" style="padding: 10px;">{{ number_format($data['cantidad_total'], 2) }} {{ $data['material']->unidadMedida?->abreviatura }}</td>
                                    <td class="text-center text-success fw-bold" style="padding: 10px;">
                                        {{ number_format($data['cantidad_comprada'], 2) }} {{ $data['material']->unidadMedida?->abreviatura }}
                                    </td>
                                    <td class="text-center fw-bold text-success" style="padding: 10px;">
                                        {{ number_format($faltante, 2) }} {{ $data['material']->unidadMedida?->abreviatura }}
                                    </td>
                                    <td class="text-end text-secondary" style="padding: 10px;">${{ number_format($data['costo_total'], 2) }}</td>
                                    <td class="text-end text-danger fw-bold" style="padding: 10px;">
                                        ${{ number_format($data['gastado'], 2) }}
                                    </td>
                                    <td class="text-end fw-bold" style="padding: 10px; color: {{ $isExcedido ? '#dc2626' : '#059669' }}">
                                        ${{ number_format($disponible, 2) }}
                                    </td>
                                    <td class="text-center" style="padding: 10px;">
                                        <span class="badge bg-success"><i class="bi bi-check-lg"></i> Listo</span>
                                        <button class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="modal" data-bs-target="#modalComprar-{{ $nivelId }}-{{ $areaId }}-{{ $matId }}" style="font-size: 0.7rem; padding: 2px 6px;">
                                            + Extra
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endif
            </tbody>
            @if(!empty($materialesPendientes) || !empty($materialesCompletados))
            <tfoot>
                <tr style="background: #f3f4f6;">
                    <th colspan="4" class="text-end" style="padding: 10px;">Totales:</th>
                    <th class="text-end" style="padding: 10px;">${{ number_format($totalCosto, 2) }}</th>
                    <th class="text-end text-danger" style="padding: 10px;">${{ number_format($totalGastado, 2) }}</th>
                    <th class="text-end" style="padding: 10px; color: {{ ($totalCosto - $totalGastado) < 0 ? '#dc2626' : '#059669' }}">
                        ${{ number_format($totalCosto - $totalGastado, 2) }}
                    </th>
                    <th></th>
                </tr>
            </tfoot>
            @endif
        </table>

        <!-- HISTORIAL DE COMPRAS -->
        <div class="mt-5">
            <h4 style="font-weight: bold; color: #374151; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                <i class="bi bi-clock-history me-2"></i> Historial de Compras de Materiales
            </h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size: 0.85rem; background: #fff;">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                            <th>Costo Total</th>
                            <th>Concepto / Proveedor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($egresosMateriales as $egreso)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $egreso->material->nombre ?? 'N/D' }}</td>
                                <td class="text-success fw-bold">
                                    {{ number_format($egreso->cantidad_material, 2) }} {{ $egreso->material->unidadMedida?->abreviatura ?? '' }}
                                </td>
                                <td class="text-danger fw-bold">
                                    @if($egreso->pago == 0 && $egreso->id_pre_proveedor)
                                        <span class="text-muted"><del>$0.00</del> (Cubierto)</span>
                                    @else
                                        ${{ number_format($egreso->monto_material, 2) }}
                                    @endif
                                </td>
                                <td>
                                    {{ $egreso->concepto }}
                                    @if($egreso->id_pre_proveedor)
                                        <br><span class="badge bg-primary text-white mt-1" style="font-size: 0.7rem;"><i class="bi bi-shield-check"></i> Cubierto por Proveedor</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('obras.materiales.destroyCompra', $egreso->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta compra? Se revertirá la cantidad de material.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger py-0 px-2"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No hay compras registradas para esta obra.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleEncargado(selectPago) {
        // Encontrar el modal padre
        let modal = selectPago.closest('.modal');
        let selectEncargado = modal.querySelector('.select-encargado');
        let msgProv = modal.querySelector('.msg-encargado-prov');

        if (selectPago.value) {
            selectEncargado.disabled = true;
            selectEncargado.required = false;
            msgProv.classList.remove('d-none');
            msgProv.classList.add('d-block');
        } else {
            selectEncargado.disabled = false;
            selectEncargado.required = true;
            msgProv.classList.remove('d-block');
            msgProv.classList.add('d-none');
        }
    }
</script>
@endsection
