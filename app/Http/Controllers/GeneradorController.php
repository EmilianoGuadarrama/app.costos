<?php

namespace App\Http\Controllers;

use App\Models\Generador;
use App\Models\PresupuestoDetalle;
use Illuminate\Http\Request;

class GeneradorController extends Controller
{
    public function index()
    {
        $generadores = Generador::with(['detalle.concepto', 'detalle.presupuesto'])->latest()->get();
        return view('generadores.index', compact('generadores'));
    }

    public function create()
    {
        $detalles = PresupuestoDetalle::with(['concepto', 'presupuesto'])->get();
        return view('generadores.create', compact('detalles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'presupuesto_detalle_id' => 'required|exists:presupuesto_detalles,id',
            'localizacion' => 'nullable|string|max:150',
            'ejes' => 'nullable|string|max:150',
            'numero_piezas' => 'required|numeric|min:0',
            'ancho' => 'required|numeric|min:0',
            'largo' => 'required|numeric|min:0',
            'alto' => 'required|numeric|min:0',
            'resultado' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:255',
        ]);

        Generador::create($data);

        return redirect()->route('generadores.index')->with('success', 'Generador creado correctamente.');
    }

    public function show($id)
    {
        $generador = Generador::with(['detalle.concepto', 'detalle.presupuesto'])->findOrFail($id);
        return view('generadores.show', compact('generador'));
    }

    public function edit($id)
    {
        $generador = Generador::findOrFail($id);
        $detalles = PresupuestoDetalle::with(['concepto', 'presupuesto'])->get();

        return view('generadores.edit', compact('generador', 'detalles'));
    }

    public function update(Request $request, $id)
    {
        $generador = Generador::findOrFail($id);

        $data = $request->validate([
            'presupuesto_detalle_id' => 'required|exists:presupuesto_detalles,id',
            'localizacion' => 'nullable|string|max:150',
            'ejes' => 'nullable|string|max:150',
            'numero_piezas' => 'required|numeric|min:0',
            'ancho' => 'required|numeric|min:0',
            'largo' => 'required|numeric|min:0',
            'alto' => 'required|numeric|min:0',
            'resultado' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:255',
        ]);

        $generador->update($data);

        return redirect()->route('generadores.index')->with('success', 'Generador actualizado correctamente.');
    }

    public function destroy($id)
    {
        $generador = Generador::findOrFail($id);
        $generador->delete();

        return redirect()->route('generadores.index')->with('success', 'Generador eliminado correctamente.');
    }
}
