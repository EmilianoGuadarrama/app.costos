<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class UnidadMedidaController extends Controller
{
    public function index()
    {
        $unidades = UnidadMedida::latest()->get();
        return view('unidad_medida.index', compact('unidades'));
    }

    public function create()
    {
        return view('unidad_medida.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'abreviatura' => 'required|string|max:20|unique:unidades_medida,abreviatura',
            'descripcion' => 'nullable|string|max:180',
        ]);

        UnidadMedida::create($data);

        return redirect()->route('unidad_medida.index')->with('success', 'Unidad creada correctamente.');
    }

    public function show($id)
    {
        $unidad = UnidadMedida::findOrFail($id);
        return view('unidad_medida.show', compact('unidad'));
    }

    public function edit($id)
    {
        $unidad = UnidadMedida::findOrFail($id);
        return view('unidad_medida.edit', compact('unidad'));
    }

    public function update(Request $request, $id)
    {
        $unidad = UnidadMedida::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'abreviatura' => 'required|string|max:20|unique:unidades_medida,abreviatura,' . $unidad->id,
            'descripcion' => 'nullable|string|max:180',
        ]);

        $unidad->update($data);

        return redirect()->route('unidad_medida.index')->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy($id)
    {
        $unidad = UnidadMedida::findOrFail($id);
        $unidad->delete();

        return redirect()->route('unidad_medida.index')->with('success', 'Unidad eliminada correctamente.');
    }
}
