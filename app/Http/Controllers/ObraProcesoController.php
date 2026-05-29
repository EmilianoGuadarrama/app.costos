<?php

namespace App\Http\Controllers;

use App\Models\ObraIniciada;
use App\Models\ObraProceso;
use App\Models\ObraEntregada;
use App\Models\IngresoTotal;
use App\Models\EgresoTotal;
use Illuminate\Http\Request;

class ObraProcesoController extends Controller
{
    public function editFechas($id)
    {
        $obra = ObraIniciada::findOrFail($id);
        $diasInhabile = \App\Models\DiaInhabil::orderBy('fecha')->get();
        return view('obras_proceso.fechas', compact('obra', 'diasInhabile'));
    }

    public function updateFechas(Request $request, $id)
    {
        $obra = ObraIniciada::findOrFail($id);
        $request->validate([
            'fecha_inicio' => 'required|date',
            'duracion' => 'required|integer|min:1',
            'estimacion_de_entrega_ymd' => 'required|date'
        ]);

        $obra->fecha_inicio = $request->fecha_inicio;
        $obra->duracion = $request->duracion;
        $obra->save();

        $proceso = ObraProceso::where('id_obra', $id)->first();
        if ($proceso) {
            $proceso->estimacion_de_entrega = $request->estimacion_de_entrega_ymd;
            $proceso->save();
        }

        if ($request->filled('dias_inhabiles_json')) {
            $inhabiles = json_decode($request->dias_inhabiles_json, true);
            if (is_array($inhabiles)) {
                foreach ($inhabiles as $fecha => $desc) {
                    \App\Models\DiaInhabil::firstOrCreate(
                        ['fecha' => $fecha],
                        ['descripcion' => $desc]
                    );
                }
            }
        }

        return redirect()->route('obras_proceso.index')->with('success', 'Fechas y días inhábiles confirmados.');
    }

    public function index()
    {
        $obrasProceso = ObraProceso::with('obraIniciada.datosDeObra')->get();
        return view('obras_proceso.index', compact('obrasProceso'));
    }

    public function show($id)
    {
        $proceso = ObraProceso::with([
            'obraIniciada.datosDeObra', 
            'obraIniciada.ingresos', 
            'obraIniciada.egresos',
            'obraIniciada.preProveedores.proveedor'
        ])->findOrFail($id);

        $obra = $proceso->obraIniciada;

        // Calculate physical progress
        if ($proceso->estado !== 'pausada') {
            $dias = $obra->fecha_inicio ? $obra->fecha_inicio->diffInDays(now()) : 0;
            $proceso->dias_transcurridos = min($dias, $obra->duracion);
            $proceso->porcentaje_avanzado = $obra->duracion > 0 ? round(($proceso->dias_transcurridos / $obra->duracion) * 100, 2) : 0;
        }

        // Calculate financial progress
        $totalIngresos = $obra->ingresos()->sum('monto_dado') ?? 0;
        $totalEgresos = $obra->egresos()->sum('pago') ?? 0;

        $proceso->presupuesto_cubierto = $totalIngresos;
        $presupuestoIntegrado = $proceso->con_iva ? ($obra->total_presupuestado * 1.16) : $obra->total_presupuestado;
        
        $proceso->presupuesto_restante = $presupuestoIntegrado - $totalIngresos;
        $proceso->porcentaje_restante = $presupuestoIntegrado > 0 ? round(($proceso->presupuesto_restante / $presupuestoIntegrado) * 100, 2) : 0;
        
        $proceso->save();

        $diasFaltantes = $proceso->estimacion_de_entrega ? now()->diffInDays($proceso->estimacion_de_entrega, false) : 0;

        return view('obras_proceso.show', compact('proceso', 'obra', 'totalIngresos', 'totalEgresos', 'presupuestoIntegrado', 'diasFaltantes'));
    }

    public function pausar(Request $request, $id)
    {
        $proceso = ObraProceso::findOrFail($id);
        $proceso->estado = $proceso->estado === 'pausada' ? 'en_curso' : 'pausada';
        $proceso->save();

        $msg = $proceso->estado === 'pausada' ? 'Obra pausada.' : 'Obra reanudada.';
        return redirect()->back()->with('success', $msg);
    }

    public function finalizar(Request $request, $id)
    {
        $proceso = ObraProceso::findOrFail($id);
        $obra = $proceso->obraIniciada;

        $totalIngresos = $obra->ingresos()->sum('monto_dado') ?? 0;
        $totalEgresos = $obra->egresos()->sum('pago') ?? 0;

        $entregada = ObraEntregada::create([
            'id_obra' => $proceso->id_obra,
            'fecha_entrega' => now(),
            'ingresos_generales' => $totalIngresos,
            'egresos' => $totalEgresos,
        ]);

        $proceso->estado = 'finalizada';
        $proceso->save();

        return redirect()->route('obras_entregadas.reporte', $entregada->id)->with('success', 'Obra finalizada correctamente.');
    }
}
