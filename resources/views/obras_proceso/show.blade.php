@extends('layout')
@section('title', 'Detalles de Obra en Proceso')

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

.dash-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
.metric-card {
    background: #fff; border-radius: 12px; padding: 25px;
    border: 1px solid #e5e7eb; box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}
.m-title { font-size: 1.1rem; font-weight: 800; color: #111; margin-bottom: 15px; border-bottom: 1px solid #f3f4f6; padding-bottom: 10px; }
.m-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem; }
.m-row span { color: #6b7280; font-weight: 600; }
.m-row strong { color: #111; font-weight: 800; }

.btn-pause { background: #f59e0b; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; }
.btn-pause:hover { background: #d97706; }
.btn-resume { background: #3b82f6; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; }
.btn-resume:hover { background: #2563eb; }

.btn-finalizar { background: #dc2626; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 800; cursor: pointer; font-size: 1rem; transition: 0.2s; width: 100%; margin-top: 15px;}
.btn-finalizar:hover { background: #b91c1c; }

.progress-container { width: 100%; background: #f3f4f6; border-radius: 8px; height: 12px; overflow: hidden; margin-top: 5px; }
.progress-bar { height: 100%; background: #059669; }
.progress-bar.warning { background: #f59e0b; }
.progress-bar.danger { background: #dc2626; }
</style>

<a href="{{ route('obras_proceso.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i> Volver a Obras en Proceso
</a>

<div class="pu-hdr">
    <div>
        <h1>Detalles de Obra: {{ $obra->datosDeObra->nombre ?? 'Obra #' . $obra->id }}</h1>
        <p class="text-muted mb-0 mt-1" style="font-size: 0.9rem;">
            Estado: 
            <strong style="color: {{ $proceso->estado == 'pausada' ? '#d97706' : '#15803d' }}">
                {{ strtoupper(str_replace('_', ' ', $proceso->estado)) }}
            </strong>
        </p>
    </div>
    <div>
        <a href="{{ route('ingresos.create', ['id_obra' => $obra->id]) }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;font-weight:600;margin-right:10px;">+ Ingreso</a>
        <a href="{{ route('egresos.create', ['id_obra' => $obra->id]) }}" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-weight:600;margin-right:10px;">+ Egreso</a>
        <form action="{{ route('obras_proceso.pausar', $proceso->id) }}" method="POST" style="display:inline;">
            @csrf
            @if($proceso->estado === 'pausada')
                <button type="submit" class="btn-resume"><i class="bi bi-play-fill"></i> Reanudar Obra</button>
            @else
                <button type="submit" class="btn-pause"><i class="bi bi-pause-fill"></i> Pausar Obra</button>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="border-radius:10px; font-weight:600;">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
</div>
@endif

<div class="dash-grid">
    <!-- Progreso Físico -->
    <div class="metric-card">
        <div class="m-title"><i class="bi bi-calendar-range me-2 text-primary"></i> Progreso Físico (Tiempo)</div>
        <div class="m-row">
            <span>Días Estimados:</span>
            <strong>{{ $obra->duracion }} días</strong>
        </div>
        <div class="m-row">
            <span>Días Transcurridos:</span>
            <strong>{{ round($proceso->dias_transcurridos) }} días</strong>
        </div>
        <div class="m-row">
            <span>Fecha de Entrega Estimada:</span>
            <strong>{{ $proceso->estimacion_de_entrega ? $proceso->estimacion_de_entrega->format('d/m/Y') : 'N/A' }}</strong>
        </div>
        <div class="mt-4">
            <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem; font-weight:700;">
                <span>Avance</span>
                <span>{{ $proceso->porcentaje_avanzado }}%</span>
            </div>
            <div class="progress-container">
                <div class="progress-bar {{ $proceso->porcentaje_avanzado > 90 ? 'danger' : '' }}" style="width: {{ min(100, $proceso->porcentaje_avanzado) }}%;"></div>
            </div>
        </div>
        
        @if($proceso->estado !== 'pausada' && $diasFaltantes <= 10)
            <div style="margin-top: 25px; padding-top: 15px; border-top: 1px solid #f3f4f6;">
                <p style="color:#dc2626; font-size:0.85rem; font-weight:700; margin-bottom:5px;">
                    <i class="bi bi-exclamation-triangle-fill"></i> La obra está por terminar (Faltan {{ round($diasFaltantes) }} días).
                </p>
                <form action="{{ route('obras_proceso.finalizar', $proceso->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de finalizar esta obra? Se generará el reporte final y no se podrá revertir.');">
                    @csrf
                    <button type="submit" class="btn-finalizar">Finalizar Obra y Generar Reporte</button>
                </form>
            </div>
        @endif
    </div>

    <!-- Progreso Financiero -->
    <div class="metric-card">
        <div class="m-title"><i class="bi bi-cash-stack me-2 text-success"></i> Progreso Financiero</div>
        <div class="m-row">
            <span>Presupuesto Integrado {{ $proceso->con_iva ? '(Con IVA)' : '(Sin IVA)' }}:</span>
            <strong>${{ number_format($presupuestoIntegrado, 2) }}</strong>
        </div>
        <div class="m-row" style="color: #059669;">
            <span>Ingresos Registrados:</span>
            <strong>${{ number_format($totalIngresos, 2) }}</strong>
        </div>
        <div class="m-row" style="color: #dc2626;">
            <span>Egresos Registrados:</span>
            <strong>${{ number_format($totalEgresos, 2) }}</strong>
        </div>
        <div class="m-row" style="margin-top:10px; padding-top:10px; border-top:1px dashed #e5e7eb;">
            <span>Presupuesto Restante (Por Cobrar):</span>
            <strong>${{ number_format($proceso->presupuesto_restante, 2) }}</strong>
        </div>
        
        @php
            $pctIngresos = $presupuestoIntegrado > 0 ? ($totalIngresos / $presupuestoIntegrado) * 100 : 0;
        @endphp
        <div class="mt-4">
            <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem; font-weight:700;">
                <span>Cobranza vs Presupuesto</span>
                <span>{{ round($pctIngresos, 2) }}%</span>
            </div>
            <div class="progress-container">
                <div class="progress-bar" style="width: {{ min(100, $pctIngresos) }}%; background:#10b981;"></div>
            </div>
        </div>
    </div>
</div>

<div class="dash-grid">
    <!-- Tabla de Ingresos -->
    <div class="metric-card">
        <div class="m-title"><i class="bi bi-arrow-down-circle text-success me-2"></i> Historial de Ingresos</div>
        @if($obra->ingresos->isEmpty())
            <p style="color:#6b7280; font-size:0.9rem;">No hay ingresos registrados.</p>
        @else
            <div style="max-height: 250px; overflow-y: auto;">
                <table class="table table-sm" style="font-size:0.85rem;">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th class="text-end">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($obra->ingresos as $ingreso)
                        <tr>
                            <td>{{ $ingreso->fecha ? $ingreso->fecha->format('d/m/Y') : '' }}</td>
                            <td>{{ $ingreso->concepto }}</td>
                            <td class="text-end text-success fw-bold">+${{ number_format($ingreso->monto_dado, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Tabla de Egresos -->
    <div class="metric-card">
        <div class="m-title"><i class="bi bi-arrow-up-circle text-danger me-2"></i> Historial de Egresos</div>
        @if($obra->egresos->isEmpty())
            <p style="color:#6b7280; font-size:0.9rem;">No hay egresos registrados.</p>
        @else
            <div style="max-height: 250px; overflow-y: auto;">
                <table class="table table-sm" style="font-size:0.85rem;">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th class="text-end">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($obra->egresos as $egreso)
                        <tr>
                            <td>{{ $egreso->fecha ? $egreso->fecha->format('d/m/Y') : '' }}</td>
                            <td>{{ $egreso->concepto }}</td>
                            <td class="text-end text-danger fw-bold">-${{ number_format($egreso->pago, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

    <!-- Modificaciones al Presupuesto -->
    <div class="metric-card" style="margin-top: 25px;">
        <div class="m-title" data-bs-toggle="collapse" data-bs-target="#collapseModificaciones" style="cursor:pointer; display:flex; justify-content:space-between;">
            <span><i class="bi bi-clock-history text-warning me-2"></i> Modificaciones al Presupuesto Original</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div id="collapseModificaciones" class="collapse show">
            @php
                $modificaciones = $obra->versionesPresupuesto()->where('numero_version', '>', 1)->orderBy('numero_version')->get();
            @endphp
            @if($modificaciones->isEmpty())
                <p style="color:#6b7280; font-size:0.9rem; margin-top:10px;">
                    No se han realizado modificaciones al presupuesto original.
                    <!-- DEBUG: Obra ID {{ $obra->id }}. Total versiones: {{ $obra->versionesPresupuesto()->count() }} -->
                </p>
            @else
                <div style="overflow-x: auto; margin-top:10px;">
                    <table class="table table-sm" style="font-size:0.85rem;">
                        <thead>
                            <tr>
                                <th>Versión</th>
                                <th>Fecha de Modificación</th>
                                <th>Motivo / Descripción del Cambio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modificaciones as $mod)
                            <tr>
                                <td><span class="badge bg-secondary">V{{ $mod->numero_version }}</span></td>
                                <td>{{ $mod->created_at ? $mod->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    <div style="font-weight: 500; margin-bottom: 5px;">
                                        @if(empty($mod->motivo_cambio) || strtolower(trim($mod->motivo_cambio)) === 'nueva versión')
                                            Versión {{ $mod->numero_version }}
                                        @else
                                            {{ $mod->motivo_cambio }}
                                        @endif
                                    </div>
                                    @php
                                        $cambios = $mod->getDiffConAnterior();
                                    @endphp
                                    @if(count($cambios) > 0)
                                        <div style="margin-top: 5px;">
                                            <a data-bs-toggle="collapse" href="#cambios-v{{ $mod->numero_version }}" role="button" aria-expanded="false" aria-controls="cambios-v{{ $mod->numero_version }}" style="font-size: 0.8rem; text-decoration: none;">
                                                <i class="bi bi-list-ul"></i> Ver detalles de modificaciones
                                            </a>
                                            <div class="collapse" id="cambios-v{{ $mod->numero_version }}" style="margin-top: 5px;">
                                                <ul style="padding-left: 20px; font-size: 0.8rem; margin-bottom: 0; color: #4b5563;">
                                                    @foreach($cambios as $cambio)
                                                        <li>{!! $cambio !!}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($mod->es_activa)
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-light text-dark border">Histórico</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Presupuestos a Proveedores Aprobados -->
    <div class="metric-card" style="margin-top: 25px;">
        <div class="m-title" data-bs-toggle="collapse" data-bs-target="#collapseProveedores" style="cursor:pointer; display:flex; justify-content:space-between;">
            <span><i class="bi bi-person-lines-fill text-primary me-2"></i> Presupuestos a Proveedores Pendientes</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div id="collapseProveedores" class="collapse show">
            @php
                $proveedoresAprobados = $obra->preProveedores ? $obra->preProveedores->where('estado', 'aprobado')->where('saldo', '>', 0) : collect();
            @endphp
            @if($proveedoresAprobados->isEmpty())
                <p style="color:#6b7280; font-size:0.9rem; margin-top:10px;">No hay presupuestos de proveedores pendientes para esta obra.</p>
            @else
                <div style="overflow-x: auto; margin-top:10px;">
                <table class="table table-sm" style="font-size:0.85rem;">
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th>Descripción</th>
                            <th class="text-end">Presupuesto Aprobado</th>
                            <th class="text-end">Pagado</th>
                            <th class="text-end">Deuda Restante</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proveedoresAprobados as $pre)
                        @php
                            $deudaRestante = $pre->total - $pre->pagado;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $pre->proveedor->empresa ?? 'S/N' }}</strong>
                                <br><small class="text-muted">{{ $pre->proveedor->persona->nombre ?? '' }} {{ $pre->proveedor->persona->apellido_paterno ?? '' }}</small>
                            </td>
                            <td>{{ $pre->area->abreviatura ?? 'N/A' }}</td>
                            <td class="text-end text-primary fw-bold">${{ number_format($pre->total, 2) }}</td>
                            <td class="text-end text-success fw-bold">${{ number_format($pre->pagado, 2) }}</td>
                            <td class="text-end text-danger fw-bold">${{ number_format($deudaRestante, 2) }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalPagoObra{{ $pre->id }}" title="Registrar Pago" style="padding:0.25rem 0.5rem; font-size:0.8rem;"><i class="bi bi-cash"></i> Pago</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalExtrasObra{{ $pre->id }}" title="Editar Extras" style="padding:0.25rem 0.5rem; font-size:0.8rem;"><i class="bi bi-plus-slash-minus"></i> Extras</button>
                            </td>
                        </tr>

                        <!-- Modal Registrar Pago -->
                        <div class="modal fade" id="modalPagoObra{{ $pre->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered text-start">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Registrar Pago a Proveedor</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('pre_proveedores.pago', $pre->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p><strong>Proveedor:</strong> {{ $pre->proveedor->empresa ?? 'S/N' }}</p>
                                            <p><strong>Saldo Pendiente:</strong> ${{ number_format($pre->saldo, 2) }}</p>
                                            <div class="mb-3">
                                                <label class="form-label">Monto a Pagar ($)</label>
                                                <input type="number" step="0.01" name="monto_pago" class="form-control" max="{{ $pre->saldo }}" required>
                                                <small class="text-muted">Este monto se registrará automáticamente en Egresos y Caja General.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Registrar Pago</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Editar Extras -->
                        <div class="modal fade" id="modalExtrasObra{{ $pre->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered text-start">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Añadir / Editar Extras</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('pre_proveedores.updateExtras', $pre->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p><strong>Proveedor:</strong> {{ $pre->proveedor->empresa ?? 'S/N' }}</p>
                                            <p><strong>Presupuesto Original:</strong> ${{ number_format($pre->presupuesto, 2) }}</p>
                                            <div class="mb-3">
                                                <label class="form-label">Monto de Extras ($)</label>
                                                <input type="number" step="0.01" name="extras" class="form-control" value="{{ $pre->extras }}" required min="0">
                                                <small class="text-muted">Esto recalculará el Total y el Saldo de este presupuesto.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Extras</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@if((isset($materialesPendientes) && count($materialesPendientes) > 0) || (isset($materialesCompletados) && count($materialesCompletados) > 0))
    <div class="metric-card" style="margin-top: 25px;">
        <div class="m-title" data-bs-toggle="collapse" data-bs-target="#collapseMateriales" style="cursor:pointer; display:flex; justify-content:space-between;">
            <span><i class="bi bi-box-seam text-info me-2"></i> Materiales Pendientes de Compra</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div id="collapseMateriales" class="collapse show">
            <div style="overflow-x: auto;">
                <table class="table table-sm" style="font-size:0.85rem;">
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
                        @foreach($materialesPendientes as $nivelId => $nivel)
                            <tr style="background: #e5e7eb;">
                                <td colspan="8" style="padding: 10px; font-weight: bold; font-size: 0.95rem;">
                                    <i class="bi bi-layers me-1"></i> NIVEL: {{ mb_strtoupper($nivel['nombre']) }}
                                </td>
                            </tr>
                            @foreach($nivel['areas'] as $areaId => $area)
                                <tr style="background: #f3f4f6;">
                                    <td colspan="8" style="padding: 8px 10px 8px 25px; font-weight: 600; font-size: 0.85rem; color: #4b5563;">
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
                                        $isCompradoCompleto = $faltante <= 0 && $data['cantidad_total'] > 0;
                                    @endphp
                                    <tr>
                                        <td style="padding: 10px 10px 10px 35px; font-weight: 600;">
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
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalComprar-{{ $nivelId }}-{{ $areaId }}-{{ $matId }}" style="font-weight: 600; padding: 4px 10px; border-radius: 8px;">
                                                <i class="bi bi-cart-plus me-1"></i> Comprar
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Comprar -->
                                    <div class="modal fade" id="modalComprar-{{ $nivelId }}-{{ $areaId }}-{{ $matId }}" tabindex="-1" aria-hidden="true">
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
                                                            <label class="form-label fw-bold">Forma de Pago</label>
                                                            <select name="id_pre_proveedor" class="form-select">
                                                                <option value="">Directo (Afecta Egresos de la Obra)</option>
                                                                @foreach($proveedoresAprobados as $preProv)
                                                                    <option value="{{ $preProv->id }}">Incluido en Presupuesto: {{ $preProv->proveedor->empresa ?? 'Proveedor S/N' }}</option>
                                                                @endforeach
                                                            </select>
                                                            <small class="text-muted">Selecciona un proveedor si esta compra ya fue pagada dentro de su presupuesto, para no duplicar el gasto en la obra.</small>
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
                            @endforeach
                        @endforeach
                    </tbody>
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
                </table>
            </div>
        </div>
    </div>

@endif

@endsection
