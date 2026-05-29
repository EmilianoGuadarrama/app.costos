@php
    $porcentaje = $p->total > 0 ? ($p->pagado / $p->total) * 100 : 0;
@endphp
<tr>
    <td>
        <strong>{{ $p->proveedor->empresa ?? 'S/N' }}</strong>
        <br><small class="text-muted">{{ $p->proveedor->persona->nombre ?? '' }} {{ $p->proveedor->persona->apellido_paterno ?? '' }}</small>
    </td>
    <td>
        <span class="badge bg-secondary">{{ $p->area->abreviatura ?? 'N/A' }}</span>
        <br><small>{{ $p->obra ? $p->obra->datosDeObra->nombre : 'EGRESOS GENERALES' }}</small>
    </td>
    <td class="text-right">${{ number_format($p->presupuesto, 2) }}</td>
    <td class="text-right">${{ number_format($p->extras, 2) }}</td>
    <td class="text-right"><strong>${{ number_format($p->total, 2) }}</strong></td>
    <td class="text-right text-success">${{ number_format($p->pagado, 2) }}</td>
    <td class="text-right text-danger">${{ number_format($p->saldo, 2) }}</td>
    <td class="text-right">{{ number_format($porcentaje, 0) }}%</td>
    <td class="text-center">
        @if($type === 'pendiente')
            <span class="badge-estado estado-pendiente d-block mb-1">Pendiente</span>
            
            <form action="{{ route('pre_proveedores.aprobar', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Aprobar este presupuesto?');">
                @csrf
                <button type="submit" class="btn-action btn-aprobar" title="Aprobar"><i class="bi bi-check-circle"></i></button>
            </form>
            
            <button class="btn-action btn-extras" data-bs-toggle="modal" data-bs-target="#modalExtras{{ $p->id }}" title="Editar Extras"><i class="bi bi-plus-slash-minus"></i></button>
            
            <form action="{{ route('pre_proveedores.destroy', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Mover a papelera? Se borrará definitivamente el {{ now()->addDays(30)->format('d/m/Y') }}.');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-action btn-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
            </form>

        @elseif($type === 'aprobado')
            <span class="badge-estado estado-aprobado d-block mb-1">Aprobado</span>
            
            <button class="btn-action btn-pago" data-bs-toggle="modal" data-bs-target="#modalPago{{ $p->id }}" title="Registrar Pago"><i class="bi bi-cash"></i></button>
            <button class="btn-action btn-extras" data-bs-toggle="modal" data-bs-target="#modalExtras{{ $p->id }}" title="Editar Extras"><i class="bi bi-plus-slash-minus"></i></button>
            
            <form action="{{ route('pre_proveedores.destroy', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Mover a papelera? Se borrará definitivamente el {{ now()->addDays(30)->format('d/m/Y') }}.');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-action btn-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
            </form>

        @elseif($type === 'finalizado')
            <span class="badge bg-primary d-block mb-1">Finalizado</span>
            
            <button class="btn-action btn-extras" data-bs-toggle="modal" data-bs-target="#modalExtras{{ $p->id }}" title="Editar Extras"><i class="bi bi-plus-slash-minus"></i></button>
            
            <form action="{{ route('pre_proveedores.destroy', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Mover a papelera? Se borrará definitivamente el {{ now()->addDays(30)->format('d/m/Y') }}.');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-action btn-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
            </form>

        @elseif($type === 'papelera')
            <span class="badge bg-dark d-block mb-1">Eliminado</span>
            <small class="text-muted d-block mb-2" style="font-size: 0.68rem; line-height: 1.1;">Se borrará el:<br><strong>{{ $p->deleted_at->addDays(30)->format('d/m/Y') }}</strong></small>
            <form action="{{ route('pre_proveedores.restore', $p->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-action btn-restore" title="Recuperar"><i class="bi bi-arrow-counterclockwise"></i> Recuperar</button>
            </form>
        @endif
    </td>
</tr>

<!-- Modal Editar Extras -->
@if($type !== 'papelera')
<div class="modal fade" id="modalExtras{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-start">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Añadir / Editar Extras</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pre_proveedores.updateExtras', $p->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p><strong>Proveedor:</strong> {{ $p->proveedor->empresa ?? 'S/N' }}</p>
                    <p><strong>Presupuesto Original:</strong> ${{ number_format($p->presupuesto, 2) }}</p>
                    <div class="mb-3">
                        <label class="form-label">Monto de Extras ($)</label>
                        <input type="number" step="0.01" name="extras" class="form-control" value="{{ $p->extras }}" required min="0">
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

<!-- Modal Registrar Pago (Solo Aprobados) -->
@if($type === 'aprobado')
<div class="modal fade" id="modalPago{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-start">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Pago a Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pre_proveedores.pago', $p->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p><strong>Proveedor:</strong> {{ $p->proveedor->empresa }}</p>
                    <p><strong>Saldo Pendiente:</strong> ${{ number_format($p->saldo, 2) }}</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Monto a Pagar ($)</label>
                        <input type="number" step="0.01" name="monto_pago" class="form-control" max="{{ $p->saldo }}" required>
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
@endif
@endif
