<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\EstadoProyecto;
use App\Models\Proyecto;
use App\Models\ResponsableTecnico;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::with(['cliente', 'responsableTecnico', 'estado'])
            ->latest()
            ->get();

        return view('proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $responsables = ResponsableTecnico::orderBy('nombre')->get();
        $estados = EstadoProyecto::orderBy('nombre')->get();

        return view('proyectos.create', compact('clientes', 'responsables', 'estados'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'responsable_tecnico_id' => 'nullable|exists:responsable_tecnicos,id',
            'estado_proyecto_id' => 'required|exists:estados_proyecto,id',
            'nombre' => 'required|string|max:150',
            'ubicacion' => 'nullable|string|max:200',
            'tipo_obra' => 'nullable|string|max:120',
            'superficie_terreno' => 'nullable|numeric',
            'tipo_uso' => 'nullable|string|max:120',
            'fecha_inicio' => 'nullable|date',
            'duracion_estimada' => 'nullable|string|max:100',
        ]);

        Proyecto::create($data);

        return redirect()->route('proyectos.index')->with('success', 'Proyecto creado correctamente.');
    }

    public function show($id)
    {
        $proyecto = Proyecto::with(['cliente', 'responsableTecnico', 'estado'])->findOrFail($id);
        return view('proyectos.show', compact('proyecto'));
    }

    public function edit($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $clientes = Cliente::orderBy('nombre')->get();
        $responsables = ResponsableTecnico::orderBy('nombre')->get();
        $estados = EstadoProyecto::orderBy('nombre')->get();

        return view('proyectos.edit', compact('proyecto', 'clientes', 'responsables', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'responsable_tecnico_id' => 'nullable|exists:responsable_tecnicos,id',
            'estado_proyecto_id' => 'required|exists:estados_proyecto,id',
            'nombre' => 'required|string|max:150',
            'ubicacion' => 'nullable|string|max:200',
            'tipo_obra' => 'nullable|string|max:120',
            'superficie_terreno' => 'nullable|numeric',
            'tipo_uso' => 'nullable|string|max:120',
            'fecha_inicio' => 'nullable|date',
            'duracion_estimada' => 'nullable|string|max:100',
        ]);

        $proyecto->update($data);

        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado correctamente.');
    }
}
