<?php

namespace App\Http\Controllers;

use App\Models\Generador;
use App\Models\Concepto;
use Illuminate\Http\Request;

class GeneradorController extends Controller
{
    public function index()
    {
        $generadores = Generador::with('concepto.unidadMedida')->latest()->get();
        return view('generadores.index', compact('generadores'));
    }

    public function create()
    {
        $conceptos = Concepto::with('unidadMedida')->orderBy('descripcion')->get();
        return view('generadores.create', compact('conceptos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'concepto_id' => 'required|exists:conceptos,id',
            'localizacion' => 'nullable|string|max:150',
            'ejes' => 'nullable|string|max:80',
            'no_piezas' => 'required|numeric|min:0',
            'largo' => 'required|numeric|min:0',
            'ancho' => 'required|numeric|min:0',
            'alto' => 'required|numeric|min:0',
            'resultado' => 'required|numeric|min:0',
        ]);

        Generador::create($data);

        return redirect()->route('generadores.index')->with('success', 'Generador creado correctamente.');
    }

    public function show(Generador $generadore)
    {
        $generadore->load('concepto.unidadMedida');
        return view('generadores.show', ['generador' => $generadore]);
    }

    public function edit(Generador $generadore)
    {
        $conceptos = Concepto::with('unidadMedida')->orderBy('descripcion')->get();
        return view('generadores.edit', ['generador' => $generadore, 'conceptos' => $conceptos]);
    }

    public function update(Request $request, Generador $generadore)
    {
        $data = $request->validate([
            'concepto_id' => 'required|exists:conceptos,id',
            'localizacion' => 'nullable|string|max:150',
            'ejes' => 'nullable|string|max:80',
            'no_piezas' => 'required|numeric|min:0',
            'largo' => 'required|numeric|min:0',
            'ancho' => 'required|numeric|min:0',
            'alto' => 'required|numeric|min:0',
            'resultado' => 'required|numeric|min:0',
        ]);

        $generadore->update($data);

        return redirect()->route('generadores.index')->with('success', 'Generador actualizado correctamente.');
    }

    public function destroy(Generador $generadore)
    {
        $generadore->delete();
        return redirect()->route('generadores.index')->with('success', 'Generador eliminado correctamente.');
    }
}
