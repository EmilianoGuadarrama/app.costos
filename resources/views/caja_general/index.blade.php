@extends('layout')
@section('title', 'Caja General — App Costos')
@section('content')
<style>
.cg-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:16px; }
.cg-title { font-size:1.85rem; font-weight:800; color:#111827; margin:0; }
.cg-subtitle { color:#6b7280; font-size:.95rem; margin:4px 0 0; }

.cg-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px; }
.cg-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:24px; box-shadow:0 4px 12px rgba(0,0,0,.04); display:flex; flex-direction:column; transition:transform .2s, box-shadow .2s; }
.cg-card:hover { transform:translateY(-3px); box-shadow:0 10px 25px rgba(0,0,0,.08); }

.cg-obra { font-size:1.15rem; font-weight:800; color:#111; margin-bottom:16px; }
.cg-obra i { color:#2563eb; margin-right:8px; }

.cg-stats { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px; }
.stat-box { background:#f9fafb; border-radius:10px; padding:12px; text-align:center; border:1px solid #f3f4f6; }
.stat-lbl { font-size:.65rem; text-transform:uppercase; letter-spacing:1px; color:#6b7280; font-weight:700; margin-bottom:4px; display:block; }
.stat-val { font-size:1.15rem; font-weight:900; }
.val-ingreso { color:#059669; }
.val-egreso { color:#dc2626; }

.cg-saldo-box { background:#111827; color:#fff; border-radius:10px; padding:14px; text-align:center; margin-bottom:20px; }
.cg-saldo-lbl { font-size:.7rem; text-transform:uppercase; letter-spacing:1.5px; color:#9ca3af; margin-bottom:4px; }
.cg-saldo-val { font-size:1.6rem; font-weight:900; color:#fbbf24; }

.btn-ver-caja { background:transparent; color:#111; border:1.5px solid #111; border-radius:10px; padding:.6rem; text-align:center; font-weight:700; font-size:.85rem; text-decoration:none; transition:all .2s; margin-top:auto; }
.btn-ver-caja:hover { background:#111; color:#fff; }

.empty-state { text-align:center; padding:60px 20px; color:#9ca3af; }
</style>

<div class="cg-header">
    <div>
        <h1 class="cg-title">Caja General</h1>
        <p class="cg-subtitle">Resumen financiero de ingresos y egresos por obra</p>
    </div>
</div>

@if($cajas->isEmpty())
    <div class="empty-state">
        <i class="bi bi-wallet2" style="font-size:3.5rem;color:#d1d5db;margin-bottom:16px;display:block;"></i>
        <h4>No hay cajas registradas</h4>
        <p>Aún no hay obras con registros financieros.</p>
    </div>
@else
    <div class="cg-grid">
        @foreach($cajas as $caja)
            @php
                $ingresos = $caja->obra && $caja->obra->ingresos ? $caja->obra->ingresos->sum('monto_dado') : 0;
                $egresos  = $caja->obra && $caja->obra->egresos ? $caja->obra->egresos->sum('pago') : 0;
                $saldo    = $ingresos - $egresos;
            @endphp
            <div class="cg-card">
                <div class="cg-obra">
                    <i class="bi bi-building"></i>{{ $caja->obra?->datosDeObra?->nombre ?? "Obra #{$caja->id_obra}" }}
                </div>

                <div class="cg-stats">
                    <div class="stat-box">
                        <span class="stat-lbl">Ingresos</span>
                        <span class="stat-val val-ingreso">${{ number_format($ingresos, 2) }}</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-lbl">Egresos</span>
                        <span class="stat-val val-egreso">${{ number_format($egresos, 2) }}</span>
                    </div>
                </div>

                <div class="cg-saldo-box">
                    <div class="cg-saldo-lbl">Saldo Disponible</div>
                    <div class="cg-saldo-val">${{ number_format($saldo, 2) }}</div>
                </div>

                <a href="{{ route('caja_general.show', $caja->id) }}" class="btn-ver-caja">
                    Ver Detalles de Caja
                </a>
            </div>
        @endforeach
    </div>
@endif

@endsection
