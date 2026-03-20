<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnidadMedida;

class UnidadMedidaController extends Controller
{

    public function index()
    {
        $unidades = UnidadMedida::all();
        return view('unidad_medida.index', compact('unidades'));
    }


    public function create()
    {
        return view('unidad_medida.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|max:255'
        ]);

        UnidadMedida::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('unidad_medida.index')
        ->with('success','Unidad creada correctamente');
    }


    public function edit($id)
    {
        $unidad = UnidadMedida::findOrFail($id);

        return view('unidad_medida.edit', compact('unidad'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|max:255'
        ]);

        $unidad = UnidadMedida::findOrFail($id);

        $unidad->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('unidad_medida.index')
        ->with('success','Unidad actualizada correctamente');
    }


    public function destroy($id)
    {
        $unidad = UnidadMedida::findOrFail($id);

        $unidad->delete();

        return redirect()->route('unidad_medida.index')
        ->with('success','Unidad eliminada correctamente');
    }

}