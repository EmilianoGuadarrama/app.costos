<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Concepto;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class ConceptoController extends Controller
{
    public function index()
    {
        $conceptos = Concepto::with('unidadMedida')->latest()->get();
        return view('conceptos.index', compact('conceptos'));
    }

    public function create()
    {
        $unidades = UnidadMedida::orderBy('nombre')->get();
        return view('conceptos.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:conceptos,clave',
            'partida' => 'nullable|string|max:100',
            'subpartida' => 'nullable|string|max:100',
            'descripcion' => 'required|string|max:255',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
        ]);

        Concepto::create($data);

        return redirect()->route('conceptos.index')->with('success', 'Concepto creado correctamente.');
    }

    public function show($id)
    {
        $concepto = Concepto::with('unidadMedida')->findOrFail($id);
        return view('conceptos.show', compact('concepto'));
    }

    public function edit($id)
    {
        $concepto = Concepto::findOrFail($id);
        $unidades = UnidadMedida::orderBy('nombre')->get();

        return view('conceptos.edit', compact('concepto', 'unidades'));
    }

    public function update(Request $request, $id)
    {
        $concepto = Concepto::findOrFail($id);

        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:conceptos,clave,' . $concepto->id,
            'partida' => 'nullable|string|max:100',
            'subpartida' => 'nullable|string|max:100',
            'descripcion' => 'required|string|max:255',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
        ]);

        $concepto->update($data);

        return redirect()->route('conceptos.index')->with('success', 'Concepto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $concepto = Concepto::findOrFail($id);
        $concepto->delete();

        return redirect()->route('conceptos.index')->with('success', 'Concepto eliminado correctamente.');
    }
}
