<?php

namespace App\Http\Controllers;

use App\Models\CategoriaEgreso;
use Illuminate\Http\Request;

class CategoriaEgresoController extends Controller
{
    public function index()
    {
        $categorias = CategoriaEgreso::latest()->get();
        return view('categorias_egreso.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias_egreso.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias_egreso,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);

        CategoriaEgreso::create($data);

        return redirect()->route('categorias_egreso.index')->with('success', 'Categoría de egreso creada correctamente.');
    }

    public function show(CategoriaEgreso $categorias_egreso)
    {
        return view('categorias_egreso.show', compact('categorias_egreso'));
    }

    public function edit(CategoriaEgreso $categorias_egreso)
    {
        return view('categorias_egreso.edit', compact('categorias_egreso'));
    }

    public function update(Request $request, CategoriaEgreso $categorias_egreso)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias_egreso,nombre,' . $categorias_egreso->id,
            'descripcion' => 'nullable|string|max:255',
        ]);

        $categorias_egreso->update($data);

        return redirect()->route('categorias_egreso.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(CategoriaEgreso $categorias_egreso)
    {
        $categorias_egreso->delete();
        return redirect()->route('categorias_egreso.index')->with('success', 'Categoría eliminada correctamente.');
    }
}
