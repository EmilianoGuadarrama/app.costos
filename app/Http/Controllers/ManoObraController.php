<?php

namespace App\Http\Controllers;

use App\Models\ManoObra;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class ManoObraController extends Controller
{
    public function index()
    {
        $manoObras = ManoObra::with('unidadMedida')->orderBy('nombre')->get();
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
            'nombre' => 'required|string|max:255',
            'id_unidad_medida' => 'nullable|exists:unidades_medida,id',
            'precio_x_unidad' => 'required|numeric|min:0',
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
            'nombre' => 'required|string|max:255',
            'id_unidad_medida' => 'nullable|exists:unidades_medida,id',
            'precio_x_unidad' => 'required|numeric|min:0',
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
