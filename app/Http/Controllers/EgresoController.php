<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use App\Models\Proyecto;
use App\Models\CategoriaEgreso;
use Illuminate\Http\Request;

class EgresoController extends Controller
{
    public function index()
    {
        $egresos = Egreso::with(['proyecto', 'categoria'])->latest()->get();
        return view('egresos.index', compact('egresos'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $categorias = CategoriaEgreso::orderBy('nombre')->get();
        return view('egresos.create', compact('proyectos', 'categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'categoria_id' => 'required|exists:categorias_egreso,id',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'comprobante' => 'nullable|string|max:255',
        ]);

        Egreso::create($data);

        return redirect()->route('egresos.index')->with('success', 'Egreso creado correctamente.');
    }

    public function show(Egreso $egreso)
    {
        $egreso->load(['proyecto', 'categoria']);
        return view('egresos.show', compact('egreso'));
    }

    public function edit(Egreso $egreso)
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $categorias = CategoriaEgreso::orderBy('nombre')->get();
        return view('egresos.edit', compact('egreso', 'proyectos', 'categorias'));
    }

    public function update(Request $request, Egreso $egreso)
    {
        $data = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'categoria_id' => 'required|exists:categorias_egreso,id',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'comprobante' => 'nullable|string|max:255',
        ]);

        $egreso->update($data);

        return redirect()->route('egresos.index')->with('success', 'Egreso actualizado correctamente.');
    }

    public function destroy(Egreso $egreso)
    {
        $egreso->delete();
        return redirect()->route('egresos.index')->with('success', 'Egreso eliminado correctamente.');
    }
}
