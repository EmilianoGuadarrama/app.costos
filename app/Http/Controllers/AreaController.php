<?php
namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::orderBy('abreviatura')->paginate(50);
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        return view('areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'abreviatura' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
        ]);
        Area::create($request->only('abreviatura','descripcion'));
        return redirect()->route('areas.index')->with('success', 'Área creada.');
    }

    public function show($id)
    {
        $area = Area::with('conceptos')->findOrFail($id);
        return view('areas.show', compact('area'));
    }

    public function edit($id)
    {
        $area = Area::findOrFail($id);
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);
        $request->validate([
            'abreviatura' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
        ]);
        $area->update($request->only('abreviatura','descripcion'));
        return redirect()->route('areas.index')->with('success', 'Área actualizada.');
    }

    public function destroy($id)
    {
        Area::findOrFail($id)->delete();
        return redirect()->route('areas.index')->with('success', 'Área eliminada.');
    }
}
