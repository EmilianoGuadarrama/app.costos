<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materiales = Material::with('unidadMedida')->latest()->get();
        return view('materiales.index', compact('materiales'));
    }

    public function create()
    {
        $unidades = UnidadMedida::orderBy('nombre')->get();
        return view('materiales.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:materiales,clave',
            'nombre' => 'required|string|max:150',
            'marca' => 'nullable|string|max:120',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'precio_unitario' => 'required|numeric|min:0',
        ]);

        Material::create($data);

        return redirect()->route('materiales.index')->with('success', 'Material creado correctamente.');
    }

    public function show($id)
    {
        $material = Material::with('unidadMedida')->findOrFail($id);
        return view('materiales.show', compact('material'));
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        $unidades = UnidadMedida::orderBy('nombre')->get();

        return view('materiales.edit', compact('material', 'unidades'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:materiales,clave,' . $material->id,
            'nombre' => 'required|string|max:150',
            'marca' => 'nullable|string|max:120',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'precio_unitario' => 'required|numeric|min:0',
        ]);

        $material->update($data);

        return redirect()->route('materiales.index')->with('success', 'Material actualizado correctamente.');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()->route('materiales.index')->with('success', 'Material eliminado correctamente.');
    }
}
