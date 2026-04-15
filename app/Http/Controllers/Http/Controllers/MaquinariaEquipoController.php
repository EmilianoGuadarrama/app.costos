<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MaquinariaEquipo;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class MaquinariaEquipoController extends Controller
{
    public function index()
    {
        $maquinarias = MaquinariaEquipo::with('unidadMedida')->latest()->get();
        return view('maquinaria_equipo.index', compact('maquinarias'));
    }

    public function create()
    {
        $unidades = UnidadMedida::orderBy('nombre')->get();
        return view('maquinaria_equipo.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:maquinaria_equipos,clave',
            'equipo' => 'required|string|max:150',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'costo_por_hora' => 'required|numeric|min:0',
        ]);

        MaquinariaEquipo::create($data);

        return redirect()->route('maquinaria_equipo.index')->with('success', 'Maquinaria creada correctamente.');
    }

    public function show($id)
    {
        $maquinaria = MaquinariaEquipo::with('unidadMedida')->findOrFail($id);
        return view('maquinaria_equipo.show', compact('maquinaria'));
    }

    public function edit($id)
    {
        $maquinaria = MaquinariaEquipo::findOrFail($id);
        $unidades = UnidadMedida::orderBy('nombre')->get();

        return view('maquinaria_equipo.edit', compact('maquinaria', 'unidades'));
    }

    public function update(Request $request, $id)
    {
        $maquinaria = MaquinariaEquipo::findOrFail($id);

        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:maquinaria_equipos,clave,' . $maquinaria->id,
            'equipo' => 'required|string|max:150',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'costo_por_hora' => 'required|numeric|min:0',
        ]);

        $maquinaria->update($data);

        return redirect()->route('maquinaria_equipo.index')->with('success', 'Maquinaria actualizada correctamente.');
    }

    public function destroy($id)
    {
        $maquinaria = MaquinariaEquipo::findOrFail($id);
        $maquinaria->delete();

        return redirect()->route('maquinaria_equipo.index')->with('success', 'Maquinaria eliminada correctamente.');
    }
}
