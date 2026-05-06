<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\PresupuestoDetalle;
use App\Models\AnalisisPu;
use App\Models\Concepto;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresupuestoController extends Controller
{
    public function index()
    {
        $presupuestos = Presupuesto::with(['proyecto', 'detalles'])
            ->latest()
            ->get();

        return view('presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        // Solo conceptos que tienen APU definido (necesario para tener PU)
        $conceptos = Concepto::with(['unidadMedida', 'analisisPu'])
            ->whereHas('analisisPu')
            ->orderBy('clave')
            ->get();

        return view('presupuestos.create', compact('proyectos', 'conceptos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyecto_id'                      => 'required|exists:proyectos,id',
            'nombre'                           => 'required|string|max:150',
            'fecha'                            => 'required|date',
            'observaciones'                    => 'nullable|string',
            // Detalles
            'detalles.*.concepto_id'           => 'required|exists:conceptos,id',
            'detalles.*.cantidad'              => 'required|numeric|min:0.0001',
            'detalles.*.pu_unitario_snapshot'  => 'required|numeric|min:0',
        ], [
            'proyecto_id.required' => 'El proyecto es obligatorio.',
            'nombre.required'      => 'El nombre del presupuesto es obligatorio.',
            'fecha.required'       => 'La fecha es obligatoria.',
        ]);

        DB::beginTransaction();
        try {
            $presupuesto = Presupuesto::create([
                'proyecto_id'   => $request->proyecto_id,
                'nombre'        => $request->nombre,
                'fecha'         => $request->fecha,
                'observaciones' => $request->observaciones,
            ]);

            foreach ($request->input('detalles', []) as $row) {
                if (!empty($row['concepto_id'])) {
                    PresupuestoDetalle::create([
                        'presupuesto_id'      => $presupuesto->id,
                        'concepto_id'         => $row['concepto_id'],
                        'cantidad'            => $row['cantidad'],
                        'pu_unitario_snapshot' => $row['pu_unitario_snapshot'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('presupuestos.show', $presupuesto->id)
                ->with('success', 'Presupuesto creado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->withErrors(['general' => 'Error al guardar el presupuesto.']);
        }
    }

    public function show($id)
    {
        $presupuesto = Presupuesto::with([
            'proyecto.cliente',
            'detalles.concepto.unidadMedida',
            'detalles.concepto.area',
        ])->findOrFail($id);

        return view('presupuestos.show', compact('presupuesto'));
    }

    public function edit($id)
    {
        $presupuesto = Presupuesto::with(['detalles.concepto'])->findOrFail($id);
        $proyectos   = Proyecto::orderBy('nombre')->get();
        $conceptos   = Concepto::with(['unidadMedida', 'analisisPu'])
            ->whereHas('analisisPu')
            ->orderBy('clave')
            ->get();

        return view('presupuestos.edit', compact('presupuesto', 'proyectos', 'conceptos'));
    }

    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        $request->validate([
            'proyecto_id'                      => 'required|exists:proyectos,id',
            'nombre'                           => 'required|string|max:150',
            'fecha'                            => 'required|date',
            'observaciones'                    => 'nullable|string',
            'detalles.*.concepto_id'           => 'required|exists:conceptos,id',
            'detalles.*.cantidad'              => 'required|numeric|min:0.0001',
            'detalles.*.pu_unitario_snapshot'  => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $presupuesto->update([
                'proyecto_id'   => $request->proyecto_id,
                'nombre'        => $request->nombre,
                'fecha'         => $request->fecha,
                'observaciones' => $request->observaciones,
            ]);

            $presupuesto->detalles()->delete();

            foreach ($request->input('detalles', []) as $row) {
                if (!empty($row['concepto_id'])) {
                    PresupuestoDetalle::create([
                        'presupuesto_id'       => $presupuesto->id,
                        'concepto_id'          => $row['concepto_id'],
                        'cantidad'             => $row['cantidad'],
                        'pu_unitario_snapshot' => $row['pu_unitario_snapshot'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('presupuestos.show', $presupuesto->id)
                ->with('success', 'Presupuesto actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->withErrors(['general' => 'Error al actualizar el presupuesto.']);
        }
    }

    public function destroy($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $presupuesto->delete();

        return redirect()->route('presupuestos.index')
            ->with('success', 'Presupuesto eliminado correctamente.');
    }
}