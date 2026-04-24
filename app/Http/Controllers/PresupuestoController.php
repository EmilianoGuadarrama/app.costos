<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    public function index()
    {
        $presupuestos = Presupuesto::with('proyecto')->latest()->get();
        return view('presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        return view('presupuestos.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'nombre' => 'required|string|max:150',
            'total' => 'nullable|numeric|min:0',
            'estado' => 'required|string|max:50',
        ], [
            'proyecto_id.required' => 'El proyecto es obligatorio.',
            'proyecto_id.exists' => 'El proyecto seleccionado no es válido.',
            'nombre.required' => 'El nombre del presupuesto es obligatorio.',
            'total.numeric' => 'El total debe ser numérico.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        Presupuesto::create([
            'proyecto_id' => $request->proyecto_id,
            'nombre' => $request->nombre,
            'total' => $request->total ?? 0,
            'estado' => $request->estado,
        ]);

        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto creado correctamente.');
    }

    public function show($id)
    {
        $presupuesto = Presupuesto::with('proyecto')->findOrFail($id);
        return view('presupuestos.show', compact('presupuesto'));
    }

    public function edit($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('presupuestos.edit', compact('presupuesto', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'nombre' => 'required|string|max:150',
            'total' => 'nullable|numeric|min:0',
            'estado' => 'required|string|max:50',
        ], [
            'proyecto_id.required' => 'El proyecto es obligatorio.',
            'proyecto_id.exists' => 'El proyecto seleccionado no es válido.',
            'nombre.required' => 'El nombre del presupuesto es obligatorio.',
            'total.numeric' => 'El total debe ser numérico.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $presupuesto->update([
            'proyecto_id' => $request->proyecto_id,
            'nombre' => $request->nombre,
            'total' => $request->total ?? 0,
            'estado' => $request->estado,
        ]);

        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $presupuesto->delete();

        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto eliminado correctamente.');
    }
}