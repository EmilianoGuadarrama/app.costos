<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ManoObra;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class ManoObraController extends Controller
{
    public function index()
    {
        $manoObras = ManoObra::with('unidadMedida')->latest()->get();
        return view('mano_obra.index', compact('manoObras'));
    }

    public function create()
    {
        $unidades = UnidadMedida::orderBy('nombre')->get();
        return view('mano_obra.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:mano_obra,clave',
            'categoria' => 'required|string|max:150',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'salario_unitario' => 'required|numeric|min:0',
        ]);

        ManoObra::create($data);

        return redirect()->route('mano_obra.index')->with('success', 'Mano de obra creada correctamente.');
    }

    public function show($id)
    {
        $mano = ManoObra::with('unidadMedida')->findOrFail($id);
        return view('mano_obra.show', compact('mano'));
    }

    public function edit($id)
    {
        $mano = ManoObra::findOrFail($id);
        $unidades = UnidadMedida::orderBy('nombre')->get();

        return view('mano_obra.edit', compact('mano', 'unidades'));
    }

    public function update(Request $request, $id)
    {
        $mano = ManoObra::findOrFail($id);

        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:mano_obra,clave,' . $mano->id,
            'categoria' => 'required|string|max:150',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'salario_unitario' => 'required|numeric|min:0',
        ]);

        $mano->update($data);

        return redirect()->route('mano_obra.index')->with('success', 'Mano de obra actualizada correctamente.');
    }

    public function destroy($id)
    {
        $mano = ManoObra::findOrFail($id);
        $mano->delete();

        return redirect()->route('mano_obra.index')->with('success', 'Mano de obra eliminada correctamente.');
    }
}
