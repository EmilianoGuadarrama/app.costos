<?php

namespace App\Http\Controllers;

use App\Models\AnalisisPuMaterial;
use App\Models\AnalisisPu;
use App\Models\Material;
use Illuminate\Http\Request;

class AnalisisPuMaterialController extends Controller
{
    public function index()
    {
        $registros = AnalisisPuMaterial::with(['analisisPu', 'material'])->latest()->get();
        return view('analisis_pu_materiales.index', compact('registros'));
    }

    public function create()
    {
        $analisis = AnalisisPu::all();
        $materiales = Material::orderBy('nombre')->get();
        return view('analisis_pu_materiales.create', compact('analisis', 'materiales'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'analisis_pu_id' => 'required|exists:analisis_pu,id',
            'material_id' => 'required|exists:materiales,id',
            'cantidad' => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
        ]);

        AnalisisPuMaterial::create($data);

        return redirect()->route('analisis_pu_materiales.index')->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        $registro = AnalisisPuMaterial::with(['analisisPu', 'material'])->findOrFail($id);
        return view('analisis_pu_materiales.show', compact('registro'));
    }

    public function edit($id)
    {
        $registro = AnalisisPuMaterial::findOrFail($id);
        $analisis = AnalisisPu::all();
        $materiales = Material::orderBy('nombre')->get();

        return view('analisis_pu_materiales.edit', compact('registro', 'analisis', 'materiales'));
    }

    public function update(Request $request, $id)
    {
        $registro = AnalisisPuMaterial::findOrFail($id);

        $data = $request->validate([
            'analisis_pu_id' => 'required|exists:analisis_pu,id',
            'material_id' => 'required|exists:materiales,id',
            'cantidad' => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
        ]);

        $registro->update($data);

        return redirect()->route('analisis_pu_materiales.index')->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $registro = AnalisisPuMaterial::findOrFail($id);
        $registro->delete();

        return redirect()->route('analisis_pu_materiales.index')->with('success', 'Registro eliminado correctamente.');
    }
}
