<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $tablaUnidadMedida = (new UnidadMedida())->getTable();

        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:materiales,clave',
            'descripcion' => 'required|string|max:150',
            'marca' => 'required|string|max:120',
            'unidad_medida_id' => ['required', Rule::exists($tablaUnidadMedida, 'id')],
            'precio_unitario' => 'required|numeric|min:0',
        ], [
            'clave.required' => 'La clave es obligatoria.',
            'clave.unique' => 'La clave ya está registrada.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'marca.required' => 'La marca es obligatoria.',
            'unidad_medida_id.required' => 'La unidad de medida es obligatoria.',
            'unidad_medida_id.exists' => 'La unidad de medida seleccionada no es válida.',
            'precio_unitario.required' => 'El precio unitario es obligatorio.',
            'precio_unitario.numeric' => 'El precio unitario debe ser numérico.',
            'precio_unitario.min' => 'El precio unitario no puede ser negativo.',
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
        $tablaUnidadMedida = (new UnidadMedida())->getTable();

        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:materiales,clave,' . $material->id,
            'descripcion' => 'required|string|max:150',
            'marca' => 'required|string|max:120',
            'unidad_medida_id' => ['required', Rule::exists($tablaUnidadMedida, 'id')],
            'precio_unitario' => 'required|numeric|min:0',
        ], [
            'clave.required' => 'La clave es obligatoria.',
            'clave.unique' => 'La clave ya está registrada.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'marca.required' => 'La marca es obligatoria.',
            'unidad_medida_id.required' => 'La unidad de medida es obligatoria.',
            'unidad_medida_id.exists' => 'La unidad de medida seleccionada no es válida.',
            'precio_unitario.required' => 'El precio unitario es obligatorio.',
            'precio_unitario.numeric' => 'El precio unitario debe ser numérico.',
            'precio_unitario.min' => 'El precio unitario no puede ser negativo.',
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