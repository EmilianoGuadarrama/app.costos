@extends('layout')
@section('title', 'Datos Generales — ' . $proyecto->nombre)
@section('content')
<style>
/* ── Datos Generales — Estilo Excel ── */
.dg-wrap { font-family: "Arial", sans-serif; }

/* Header de contadores (HOY / FALTAN / DURACIÓN / TRANSCURRIDOS) */
.dg-counters {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    border: 1px solid #ccc;
    border-radius: 0;
    margin-bottom: 0;
}
.dg-counter-box {
    padding: 14px 20px;
    text-align: center;
    border-right: 1px solid #ccc;
}
.dg-counter-box:last-child { border-right: none; }
.dg-counter-label {
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #555;
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
}
.dg-counter-value {
    font-size: 2rem;
    font-weight: 900;
    color: #111;
    line-height: 1;
}
.dg-counter-value.fecha { font-size: 1.6rem; }
.dg-counter-value.negativo { color: #dc2626; }

/* Tabla de datos generales */
.dg-section-header {
    background: #f0f0f0;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #555;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
    text-align: right;
}
.dg-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .88rem;
    border: 1px solid #ccc;
    border-top: none;
}
.dg-table td {
    padding: 9px 14px;
    border-bottom: 1px solid #e0e0e0;
    border-right: 1px solid #e0e0e0;
}
.dg-table td:first-child {
    font-weight: 700;
    color: #374151;
    background: #fafafa;
    width: 200px;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .3px;
}
.dg-table td:last-child { border-right: none; }
.dg-table tr:last-child td { border-bottom: none; }
.dg-table .highlighted td { background: #f9f9f9; }

/* Títulos de sección */
.dg-section-title {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #888;
    padding: 20px 0 8px;
}

/* Acciones */
.dg-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    align-items: center;
}
.btn-dg-back { text-decoration: none; color: #6b7280; font-size: .88rem; display: flex; align-items: center; gap: 5px; }
.btn-dg-back:hover { color: #111; }
.btn-dg-edit {
    background: #111827; color: #fff; border: none; border-radius: 10px;
    padding: .6rem 1.2rem; font-size: .82rem; font-weight: 700; text-decoration: none;
    display: flex; align-items: center; gap: 6px;
}
.btn-dg-edit:hover { background: #374151; color: #fff; }
.btn-dg-presup {
    background: #2563eb; color: #fff; border: none; border-radius: 10px;
    padding: .6rem 1.2rem; font-size: .82rem; font-weight: 700; text-decoration: none;
    display: flex; align-items: center; gap: 6px;
}
.btn-dg-presup:hover { background: #1d4ed8; color: #fff; }

/* Lista de presupuestos */
.presup-list {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 6px;
}
.presup-row {
    display: flex;
    align-items: center;
    padding: 12px 18px;
    border-bottom: 1px solid #f3f4f6;
    gap: 14px;
    text-decoration: none;
    color: inherit;
    transition: background .18s;
}
.presup-row:last-child { border-bottom: none; }
.presup-row:hover { background: #f9fafb; }
.presup-row-name { font-weight: 700; font-size: .9rem; color: #111; flex: 1; }
.presup-row-fecha { font-size: .8rem; color: #9ca3af; }
.presup-row-total { font-weight: 800; color: #2563eb; font-size: .95rem; font-variant-numeric: tabular-nums; }
.presup-row-estado {
    font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .3px;
    background: #dbeafe; color: #1d4ed8; border-radius: 20px; padding: 2px 10px;
}

@media (max-width: 640px) {
    .dg-counters { grid-template-columns: 1fr 1fr; }
}
</style>

<div class="dg-wrap">
    <!-- Acciones -->
    <div class="dg-actions">
        <a href="{{ route('proyectos.index') }}" class="btn-dg-back">
            <i class="bi bi-arrow-left"></i> Mis proyectos
        </a>
        <div style="flex:1"></div>
        <a href="{{ route('proyectos.edit', $proyecto->id) }}" class="btn-dg-edit" id="btn-editar-proyecto">
            <i class="bi bi-pencil-square"></i> Editar
        </a>
        <a href="{{ route('presupuestos.create', ['proyecto_id' => $proyecto->id]) }}" class="btn-dg-presup" id="btn-nuevo-presupuesto">
            <i class="bi bi-plus-lg"></i> Nuevo Presupuesto
        </a>
    </div>

    @php
        $hoy             = now();
        $fechaInicio     = $proyecto->fecha_inicio ? \Carbon\Carbon::parse($proyecto->fecha_inicio) : null;
        $duracion        = (int) ($proyecto->duracion_estimada ?? 0);
        $diasTranscurr   = $fechaInicio ? (int) $fechaInicio->diffInDays($hoy, false) : 0;
        $diasFaltan      = $duracion > 0 ? $duracion - $diasTranscurr : null;
        $fechaFin        = ($fechaInicio && $duracion > 0) ? $fechaInicio->copy()->addDays($duracion) : null;

        $cliente = $proyecto->cliente;
        $responsable = $proyecto->responsableTecnico;
        $empresa = $responsable?->empresa;

        $totalPres = $proyecto->presupuestos->sum('total');
        $m2 = $proyecto->superficie_terreno;
        $totalPorM2 = ($m2 && $m2 > 0 && $totalPres > 0) ? round($totalPres / $m2, 2) : null;
    @endphp

    <!-- ═══ CONTADORES HOY / FALTAN / DURACIÓN / TRANSCURRIDOS ═══ -->
    <div class="dg-counters">
        <div class="dg-counter-box">
            <span class="dg-counter-label">Hoy</span>
            <div class="dg-counter-value fecha">{{ $hoy->format('n/j/Y') }}</div>
        </div>
        <div class="dg-counter-box">
            <span class="dg-counter-label">Los días que faltan</span>
            <div class="dg-counter-value {{ $diasFaltan !== null && $diasFaltan < 0 ? 'negativo' : '' }}">
                {{ $diasFaltan !== null ? $diasFaltan : '—' }}
            </div>
        </div>
        <div class="dg-counter-box">
            <span class="dg-counter-label">Lo que dura</span>
            <div class="dg-counter-value">{{ $duracion ?: '0' }}</div>
        </div>
        <div class="dg-counter-box">
            <span class="dg-counter-label">Días transcurridos</span>
            <div class="dg-counter-value">{{ max(0, $diasTranscurr) }}</div>
        </div>
    </div>

    <!-- ═══ TABLA PROYECTO ═══ -->
    <div class="dg-section-header">PROYECTO</div>
    <table class="dg-table">
        <tr>
            <td>Concepto</td>
            <td>Variable o Renglón</td>
        </tr>
        <tr>
            <td>Nombre</td>
            <td>{{ $proyecto->nombre }}</td>
        </tr>
        <tr>
            <td>Responsables</td>
            <td>{{ $responsable?->nombre ?? '—' }}</td>
        </tr>
        <tr>
            <td>Contacto</td>
            <td>{{ $cliente?->contacto_principal ?? $cliente?->nombre ?? '—' }}</td>
        </tr>
        <tr>
            <td>Teléfono 1</td>
            <td>{{ $cliente?->telefono ?? '—' }}</td>
        </tr>
        <tr>
            <td>Teléfono 2</td>
            <td>—</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $cliente?->correo ?? '—' }}</td>
        </tr>
        <tr>
            <td>Calle y Número</td>
            <td>{{ $proyecto->ubicacion ?? '—' }}</td>
        </tr>
        <tr>
            <td>Colonia</td>
            <td>—</td>
        </tr>
        <tr>
            <td>Delegación</td>
            <td>—</td>
        </tr>
        <tr>
            <td>Estado</td>
            <td>{{ $proyecto->estado?->nombre ?? '—' }}</td>
        </tr>
        <tr>
            <td>Código Postal</td>
            <td>—</td>
        </tr>
        <tr>
            <td>Cuenta Catastral</td>
            <td>—</td>
        </tr>
        <tr>
            <td>Uso de Suelo</td>
            <td>{{ $proyecto->tipo_uso ?? '—' }}</td>
        </tr>
        <tr>
            <td>Nombre o Razón Social</td>
            <td>{{ $cliente?->razon_social ?? $cliente?->nombre ?? '—' }}</td>
        </tr>
        <tr>
            <td>R.F.C.</td>
            <td>{{ $cliente?->rfc ?? '—' }}</td>
        </tr>
        <tr>
            <td>Dirección Fiscal</td>
            <td>{{ $cliente?->direccion ?? '—' }}</td>
        </tr>
        <tr>
            <td>Fecha de Inicio</td>
            <td>{{ $fechaInicio?->format('d/m/Y') ?? '—' }}</td>
        </tr>
        <tr>
            <td>Fecha de Entrega</td>
            <td>{{ $fechaFin?->format('d/m/Y') ?? '—' }}</td>
        </tr>
        <tr class="highlighted">
            <td>Duración (Días)</td>
            <td>{{ $duracion ?: '—' }}</td>
        </tr>
        <tr class="highlighted">
            <td>Transcurridos (Días)</td>
            <td>{{ max(0, $diasTranscurr) }}</td>
        </tr>
        <tr>
            <td>Dimensión (M2 totales - venta)</td>
            <td>{{ $m2 ? number_format($m2, 2) . ' m²' : '—' }}</td>
        </tr>
        <tr>
            <td>Precio por M2 estimado ($)</td>
            <td>{{ $totalPorM2 ? '$' . number_format($totalPorM2, 2) : '—' }}</td>
        </tr>
        <tr>
            <td>Total Obra Estimado ($)</td>
            <td>—</td>
        </tr>
        <tr>
            <td>Total Obra Presupuestado ($)</td>
            <td><strong>${{ number_format($totalPres, 2) }}</strong></td>
        </tr>
        <tr class="highlighted">
            <td>Total (%)</td>
            <td>—</td>
        </tr>
        <tr class="highlighted">
            <td>Total por M2 ($)</td>
            <td>{{ $totalPorM2 ? '$' . number_format($totalPorM2, 2) : '—' }}</td>
        </tr>
    </table>

    <!-- ═══ PRESUPUESTOS DE ESTE PROYECTO ═══ -->
    @if($proyecto->presupuestos->count() > 0)
    <div class="dg-section-title"><i class="bi bi-file-earmark-text me-1"></i> Presupuestos del Proyecto</div>
    <div class="presup-list">
        @foreach($proyecto->presupuestos->sortByDesc('created_at') as $p)
        <a href="{{ route('presupuestos.show', $p->id) }}" class="presup-row" id="presup-link-{{ $p->id }}">
            <div>
                <i class="bi bi-file-earmark-spreadsheet" style="color:#2563eb;font-size:1.1rem;"></i>
            </div>
            <div class="presup-row-name">{{ $p->nombre }}</div>
            <div class="presup-row-fecha">
                {{ $p->fecha ? \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') : ($p->created_at?->format('d/m/Y') ?? '—') }}
            </div>
            <div class="presup-row-total">${{ number_format($p->total, 2) }}</div>
            <div class="presup-row-estado">{{ ucfirst($p->estado ?? 'borrador') }}</div>
            <i class="bi bi-chevron-right" style="color:#d1d5db;"></i>
        </a>
        @endforeach
    </div>
    @else
    <div class="dg-section-title"><i class="bi bi-file-earmark-text me-1"></i> Presupuestos del Proyecto</div>
    <div style="padding:24px;text-align:center;color:#9ca3af;font-size:.9rem;border:1px solid #e5e7eb;border-radius:12px;">
        <i class="bi bi-file-earmark-plus" style="font-size:2rem;display:block;margin-bottom:8px;color:#d1d5db;"></i>
        Sin presupuestos. <a href="{{ route('presupuestos.create', ['proyecto_id' => $proyecto->id]) }}" style="color:#2563eb;text-decoration:none;font-weight:700;">Crear el primero →</a>
    </div>
    @endif
</div>
@endsection
