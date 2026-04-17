<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::latest()->get();
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        return view('areas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clave' => 'required|string|max:20|unique:areas,clave',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
        ]);

        Area::create($data);

        return redirect()->route('areas.index')->with('success', 'Área creada correctamente.');
    }

    public function show(Area $area)
    {
        return view('areas.show', compact('area'));
    }

    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $data = $request->validate([
            'clave' => 'required|string|max:20|unique:areas,clave,' . $area->id,
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $area->update($data);

        return redirect()->route('areas.index')->with('success', 'Área actualizada correctamente.');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Área eliminada correctamente.');
    }
}
