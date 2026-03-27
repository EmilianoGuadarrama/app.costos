<?php

namespace App\Http\Controllers;

use App\Models\EstadoProyecto;
use Illuminate\Http\Request;

class EstadoProyectoController extends Controller
{
    public function index()
    {
        $estados = EstadoProyecto::latest()->get();
        return view('estados_proyecto.index', compact('estados'));
    }

    public function create()
    {
        return view('estados_proyecto.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:60|unique:estados_proyecto,nombre',
        ]);

        EstadoProyecto::create($data);

        return redirect()->route('estados_proyecto.index')->with('success', 'Estado creado correctamente.');
    }

    public function show($id)
    {
        $estado = EstadoProyecto::findOrFail($id);
        return view('estados_proyecto.show', compact('estado'));
    }

    public function edit($id)
    {
        $estado = EstadoProyecto::findOrFail($id);
        return view('estados_proyecto.edit', compact('estado'));
    }

    public function update(Request $request, $id)
    {
        $estado = EstadoProyecto::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:60|unique:estados_proyecto,nombre,' . $estado->id,
        ]);

        $estado->update($data);

        return redirect()->route('estados_proyecto.index')->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $estado = EstadoProyecto::findOrFail($id);
        $estado->delete();

        return redirect()->route('estados_proyecto.index')->with('success', 'Estado eliminado correctamente.');
    }
}
