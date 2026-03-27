<?php

namespace App\Http\Controllers;

use App\Models\PresupuestoDetalle;
use App\Models\Presupuesto;
use App\Models\Concepto;
use Illuminate\Http\Request;

class PresupuestoDetalleController extends Controller
{
    public function index()
    {
        $detalles = PresupuestoDetalle::with(['presupuesto', 'concepto'])->latest()->get();
        return view('presupuesto_detalles.index', compact('detalles'));
    }

    public function create()
    {
        $presupuestos = Presupuesto::orderBy('nombre')->get();
        $conceptos = Concepto::orderBy('descripcion')->get();

        return view('presupuesto_detalles.create', compact('presupuestos', 'conceptos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'presupuesto_id' => 'required|exists:presupuestos,id',
            'concepto_id' => 'required|exists:conceptos,id',
            'cantidad' => 'required|numeric|min:0',
            'pu_unitario_snapshot' => 'nullable|numeric|min:0',
        ]);

        PresupuestoDetalle::create($data);

        return redirect()->route('presupuesto_detalles.index')->with('success', 'Detalle creado correctamente.');
    }

    public function show($id)
    {
        $detalle = PresupuestoDetalle::with(['presupuesto', 'concepto'])->findOrFail($id);
        return view('presupuesto_detalles.show', compact('detalle'));
    }

    public function edit($id)
    {
        $detalle = PresupuestoDetalle::findOrFail($id);
        $presupuestos = Presupuesto::orderBy('nombre')->get();
        $conceptos = Concepto::orderBy('descripcion')->get();

        return view('presupuesto_detalles.edit', compact('detalle', 'presupuestos', 'conceptos'));
    }

    public function update(Request $request, $id)
    {
        $detalle = PresupuestoDetalle::findOrFail($id);

        $data = $request->validate([
            'presupuesto_id' => 'required|exists:presupuestos,id',
            'concepto_id' => 'required|exists:conceptos,id',
            'cantidad' => 'required|numeric|min:0',
            'pu_unitario_snapshot' => 'nullable|numeric|min:0',
        ]);

        $detalle->update($data);

        return redirect()->route('presupuesto_detalles.index')->with('success', 'Detalle actualizado correctamente.');
    }

    public function destroy($id)
    {
        $detalle = PresupuestoDetalle::findOrFail($id);
        $detalle->delete();

        return redirect()->route('presupuesto_detalles.index')->with('success', 'Detalle eliminado correctamente.');
    }
}
