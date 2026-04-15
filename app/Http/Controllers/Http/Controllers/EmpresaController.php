<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::latest()->get();
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:200',
            'logo_path' => 'nullable|string|max:255',
        ]);

        Empresa::create($data);

        return redirect()->route('empresas.index')->with('success', 'Empresa creada correctamente.');
    }

    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('empresas.show', compact('empresa'));
    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:200',
            'logo_path' => 'nullable|string|max:255',
        ]);

        $empresa->update($data);

        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada correctamente.');
    }
}
