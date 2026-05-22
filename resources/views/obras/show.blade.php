@extends('layout')
@section('title', 'Datos Generales — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))
@section('content')
<style>
.dg-wrap { font-family:"Arial",sans-serif; }

/* Contadores */
.dg-counters { display:grid; grid-template-columns:repeat(4,1fr); gap:0; border:1px solid #ccc; }
.dg-counter-box { padding:14px 20px; text-align:center; border-right:1px solid #ccc; }
.dg-counter-box:last-child { border-right:none; }
.dg-counter-label { font-size:.68rem; text-transform:uppercase; letter-spacing:1px; color:#555; font-weight:700; display:block; margin-bottom:4px; }
.dg-counter-val { font-size:2rem; font-weight:900; color:#111; line-height:1; }
.dg-counter-val.fecha { font-size:1.5rem; }
.dg-counter-val.negativo { color:#dc2626; }

/* Tabla Excel */
.dg-section-bar { background:#f0f0f0; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:2px; color:#555; padding:6px 12px; border:1px solid #ccc; border-top:none; text-align:right; }
.dg-table { width:100%; border-collapse:collapse; font-size:.88rem; border:1px solid #ccc; border-top:none; }
.dg-table td { padding:9px 14px; border-bottom:1px solid #e0e0e0; border-right:1px solid #e0e0e0; }
.dg-table td:first-child { font-weight:700; color:#374151; background:#fafafa; width:220px; font-size:.8rem; text-transform:uppercase; }
.dg-table td:last-child { border-right:none; }
.dg-table tr:last-child td { border-bottom:none; }
.dg-table tr.highlight td { background:#f9f9f9; }

/* Acciones */
.dg-actions { display:flex; gap:10px; margin-bottom:18px; flex-wrap:wrap; align-items:center; }
.btn-back-link { text-decoration:none; color:#6b7280; font-size:.88rem; display:flex; align-items:center; gap:5px; }
.btn-back-link:hover { color:#111; }
.btn-editar { background:#111827; color:#fff; border:none; border-radius:10px; padding:.6rem 1.2rem; font-size:.82rem; font-weight:700; text-decoration:none; }
.btn-editar:hover { background:#374151; color:#fff; }
.btn-presupuesto { background:#2563eb; color:#fff; border:none; border-radius:10px; padding:.6rem 1.2rem; font-size:.82rem; font-weight:700; text-decoration:none; }
.btn-presupuesto:hover { background:#1d4ed8; color:#fff; }

/* Niveles */
.niveles-lista { margin-top:8px; display:flex; flex-wrap:wrap; gap:8px; }
.nivel-tag { background:#f3f4f6; border-radius:8px; padding:6px 14px; font-size:.82rem; font-weight:600; color:#374151; border:1px solid #e5e7eb; }

@media(max-width:640px) { .dg-counters { grid-template-columns:1fr 1fr; } }
</style>

<div class="dg-wrap">
    @php
        $datos     = $obra->datosDeObra;
        $encargado = $obra->encargado?->persona;
        $hoy       = now();
        $duracion  = (int) $obra->duracion;
        $diasTrans = $obra->dias_transcurridos;
        $diasFalt  = $obra->dias_faltan;
        $fechaFin  = ($obra->fecha_inicio && $duracion)
                     ? $obra->fecha_inicio->copy()->addDays($duracion) : null;
        $totalObra = $obra->totalObra;
        $m2        = $datos?->dimensiones_m2;
        $totalFin  = $totalObra?->total_final ?? 0;
        $pxm2      = ($m2 && $m2 > 0 && $totalFin > 0) ? round($totalFin / $m2, 2) : null;
    @endphp

    <div class="dg-actions">
        <a href="{{ route('obras.index') }}" class="btn-back-link">
            <i class="bi bi-arrow-left"></i> Mis obras
        </a>
        <div style="flex:1"></div>
        <a href="{{ route('obras.edit', $obra->id) }}" class="btn-editar" id="btn-editar-obra">
            <i class="bi bi-pencil me-1"></i> Editar
        </a>
        @php
            $tieneRenglones = $obra->asignaConceptos()->exists()
                           || $obra->asignaMateriales()->exists()
                           || $obra->asignaMaquinaria()->exists();
        @endphp
        @if($tieneRenglones)
        <a href="{{ route('obras.presupuesto', $obra->id) }}" class="btn-presupuesto" id="btn-ver-presupuesto">
            <i class="bi bi-file-earmark-text me-1"></i> Ver Presupuesto
        </a>
        @else
        <button type="button" class="btn-presupuesto" id="btn-ver-presupuesto" style="cursor:pointer;"
                onclick="alertaSinPresup({{ $obra->id }}, '{{ addslashes($datos?->nombre ?? 'Obra #'.$obra->id) }}')">
            <i class="bi bi-file-earmark-text me-1"></i> Ver Presupuesto
        </button>
        @endif
        <a href="{{ route('obras.presupuesto.create', $obra->id) }}" class="btn-presupuesto" style="background:#059669;" id="btn-agregar-presupuesto">
            <i class="bi bi-plus-lg me-1"></i> Agregar Conceptos
        </a>
    </div>

    <!-- Contadores HOY / FALTAN / DURACIÓN / TRANSCURRIDOS -->
    <div class="dg-counters">
        <div class="dg-counter-box">
            <span class="dg-counter-label">Hoy</span>
            <div class="dg-counter-val fecha">{{ $hoy->format('n/j/Y') }}</div>
        </div>
        <div class="dg-counter-box">
            <span class="dg-counter-label">Los días que faltan</span>
            <div class="dg-counter-val {{ $diasFalt !== null && $diasFalt < 0 ? 'negativo' : '' }}">
                {{ $diasFalt ?? '—' }}
            </div>
        </div>
        <div class="dg-counter-box">
            <span class="dg-counter-label">Lo que dura</span>
            <div class="dg-counter-val">{{ $duracion ?: '—' }}</div>
        </div>
        <div class="dg-counter-box">
            <span class="dg-counter-label">Días transcurridos</span>
            <div class="dg-counter-val">{{ max(0, $diasTrans) }}</div>
        </div>
    </div>

    <!-- Tabla Datos Generales -->
    <div class="dg-section-bar">PROYECTO / OBRA</div>
    <table class="dg-table">
        <tr><td>Concepto</td><td>Variable o Renglón</td></tr>
        <tr><td>Nombre de la obra</td><td>{{ $datos?->nombre ?? '—' }}</td></tr>
        <tr><td>Descripción</td><td>{{ $datos?->descripcion ?? '—' }}</td></tr>
        <tr><td>Responsable / Encargado</td>
            <td>{{ $encargado ? $encargado->nombre.' '.$encargado->apellido_paterno : '—' }}
                @if($obra->encargado?->rol)<span style="color:#9ca3af;font-size:.85em;"> · {{ $obra->encargado->rol }}</span>@endif
            </td>
        </tr>
        <tr><td>Teléfono 1</td><td>{{ $encargado?->telefono_1 ?? '—' }}</td></tr>
        <tr><td>Email</td><td>{{ $encargado?->email ?? '—' }}</td></tr>
        <tr><td>Fecha de Inicio</td><td>{{ $obra->fecha_inicio?->format('d/m/Y') ?? '—' }}</td></tr>
        <tr><td>Fecha de Entrega est.</td><td>{{ $fechaFin?->format('d/m/Y') ?? '—' }}</td></tr>
        <tr class="highlight"><td>Duración (Días)</td><td>{{ $duracion ?: '—' }}</td></tr>
        <tr class="highlight"><td>Transcurridos (Días)</td><td>{{ max(0,$diasTrans) }}</td></tr>
        <tr><td>Dimensiones (M²)</td><td>{{ $m2 ? number_format($m2,2).' m²' : '—' }}</td></tr>
        <tr><td>Número de Niveles</td><td>{{ $datos?->num_niveles ?? '—' }}</td></tr>
        <tr><td>Precio por M² estimado</td>
            <td>{{ $obra->precio_por_m2_estimado ? '$'.number_format($obra->precio_por_m2_estimado,2) : '—' }}</td></tr>
        <tr><td>Total Obra Estimado</td>
            <td>{{ $obra->total_de_obra_estimado ? '$'.number_format($obra->total_de_obra_estimado,2) : '—' }}</td></tr>
        <tr class="highlight"><td>Total Presupuestado</td>
            <td><strong>{{ $totalFin ? '$'.number_format($totalFin,2) : '—' }}</strong></td></tr>
        <tr class="highlight"><td>Total por M²</td>
            <td>{{ $pxm2 ? '$'.number_format($pxm2,2) : '—' }}</td></tr>
    </table>

    <!-- Niveles -->
    @if($obra->niveles->count())
    <div class="dg-section-bar" style="margin-top:20px;">NIVELES DE LA OBRA</div>
    <div style="border:1px solid #ccc;border-top:none;padding:16px;">
        <div class="niveles-lista">
            @foreach($obra->niveles as $nivel)
            <div class="nivel-tag">
                <i class="bi bi-layers me-1"></i>{{ $nivel->descripcion }}
                @if($nivel->m2)<span style="color:#9ca3af;"> · {{ number_format($nivel->m2,2) }} m²</span>@endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Totales financieros -->
    @if($totalObra)
    <div class="dg-section-bar" style="margin-top:20px;">TOTALES DEL PRESUPUESTO</div>
    <table class="dg-table">
        <tr><td>Total sin IVA</td><td>${{ number_format($totalObra->total_inicial,2) }}</td></tr>
        <tr><td>IVA</td><td>${{ number_format($totalObra->total_iva,2) }}</td></tr>
        <tr class="highlight"><td>Total Final</td><td><strong>${{ number_format($totalObra->total_final,2) }}</strong></td></tr>
    </table>
    @endif
</div>

<!-- Modal Presupuesto Vacío -->
<div id="modalSinPresup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(17,24,39,0.7); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; text-align:center; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
        <div style="width:64px; height:64px; background:#fef3c7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
            <i class="bi bi-file-earmark-x" style="font-size:2rem; color:#d97706;"></i>
        </div>
        <h3 style="font-size:1.2rem; font-weight:800; color:#111; margin-bottom:8px;">Presupuesto Vacío</h3>
        <p style="font-size:.9rem; color:#4b5563; margin-bottom:24px;">La obra <strong id="modalObraNombre"></strong> aún no tiene renglones en su presupuesto.</p>
        
        <div style="display:flex; flex-direction:column; gap:10px;">
            <a href="#" id="modalBtnAgregar" class="btn btn-primary" style="background:#2563eb; border:none; border-radius:10px; padding:10px; font-weight:700;">
                <i class="bi bi-plus-lg me-1"></i> Agregar Renglones
            </a>
            <button type="button" onclick="cerrarAlertaPresup()" style="background:transparent; border:1.5px solid #e5e7eb; border-radius:10px; padding:10px; color:#4b5563; font-weight:600; cursor:pointer;">
                Cancelar
            </button>
        </div>
    </div>
</div>

<script>
function alertaSinPresup(obraId, nombreObra) {
    document.getElementById('modalObraNombre').textContent = nombreObra;
    document.getElementById('modalBtnAgregar').href = `/obras/${obraId}/presupuesto/agregar`;
    document.getElementById('modalSinPresup').style.display = 'flex';
}
function cerrarAlertaPresup() {
    document.getElementById('modalSinPresup').style.display = 'none';
}
</script>
@endsection
