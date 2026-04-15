<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    public function index()
    {
        $presupuestos = Presupuesto::with(['proyecto', 'detalles.concepto'])
            ->latest()
            ->get();

        return view('presupuesto.index', compact('presupuestos'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        return view('presupuesto.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'nombre' => 'required|string|max:150',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        Presupuesto::create($data);

        return redirect()->route('presupuesto.index')->with('success', 'Presupuesto creado correctamente.');
    }

    public function show($id)
    {
        $presupuesto = Presupuesto::with(['proyecto', 'detalles.concepto'])->findOrFail($id);
        return view('presupuesto.show', compact('presupuesto'));
    }

    public function edit($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('presupuesto.edit', compact('presupuesto', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        $data = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'nombre' => 'required|string|max:150',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $presupuesto->update($data);

        return redirect()->route('presupuesto.index')->with('success', 'Presupuesto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $presupuesto->delete();

        return redirect()->route('presupuesto.index')->with('success', 'Presupuesto eliminado correctamente.');
    }
}
