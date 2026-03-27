<?php

namespace App\Http\Controllers;

use App\Models\ResponsableTecnico;
use App\Models\Empresa;
use Illuminate\Http\Request;

class ResponsableTecnicoController extends Controller
{
    public function index()
    {
        $responsables = ResponsableTecnico::with('empresa')->latest()->get();
        return view('responsables_tecnicos.index', compact('responsables'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        return view('responsables_tecnicos.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'nombre' => 'required|string|max:150',
            'cargo' => 'required|string|max:120',
            'firma_path' => 'nullable|string|max:255',
        ]);

        ResponsableTecnico::create($data);

        return redirect()->route('responsables_tecnicos.index')->with('success', 'Responsable técnico creado correctamente.');
    }

    public function show($id)
    {
        $responsable = ResponsableTecnico::with('empresa')->findOrFail($id);
        return view('responsables_tecnicos.show', compact('responsable'));
    }

    public function edit($id)
    {
        $responsable = ResponsableTecnico::findOrFail($id);
        $empresas = Empresa::orderBy('nombre')->get();

        return view('responsables_tecnicos.edit', compact('responsable', 'empresas'));
    }

    public function update(Request $request, $id)
    {
        $responsable = ResponsableTecnico::findOrFail($id);

        $data = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'nombre' => 'required|string|max:150',
            'cargo' => 'required|string|max:120',
            'firma_path' => 'nullable|string|max:255',
        ]);

        $responsable->update($data);

        return redirect()->route('responsables_tecnicos.index')->with('success', 'Responsable técnico actualizado correctamente.');
    }

    public function destroy($id)
    {
        $responsable = ResponsableTecnico::findOrFail($id);
        $responsable->delete();

        return redirect()->route('responsables_tecnicos.index')->with('success', 'Responsable técnico eliminado correctamente.');
    }
}
