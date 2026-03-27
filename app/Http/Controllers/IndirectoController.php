<?php

namespace App\Http\Controllers;

use App\Models\Indirecto;
use Illuminate\Http\Request;

class IndirectoController extends Controller
{
    public function index()
    {
        $indirectos = Indirecto::latest()->get();
        return view('indirectos.index', compact('indirectos'));
    }

    public function create()
    {
        return view('indirectos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:indirectos,clave',
            'concepto' => 'required|string|max:150',
            'porcentaje' => 'required|numeric|min:0',
        ]);

        Indirecto::create($data);

        return redirect()->route('indirectos.index')->with('success', 'Indirecto creado correctamente.');
    }

    public function show($id)
    {
        $indirecto = Indirecto::findOrFail($id);
        return view('indirectos.show', compact('indirecto'));
    }

    public function edit($id)
    {
        $indirecto = Indirecto::findOrFail($id);
        return view('indirectos.edit', compact('indirecto'));
    }

    public function update(Request $request, $id)
    {
        $indirecto = Indirecto::findOrFail($id);

        $data = $request->validate([
            'clave' => 'required|string|max:50|unique:indirectos,clave,' . $indirecto->id,
            'concepto' => 'required|string|max:150',
            'porcentaje' => 'required|numeric|min:0',
        ]);

        $indirecto->update($data);

        return redirect()->route('indirectos.index')->with('success', 'Indirecto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $indirecto = Indirecto::findOrFail($id);
        $indirecto->delete();

        return redirect()->route('indirectos.index')->with('success', 'Indirecto eliminado correctamente.');
    }
}
