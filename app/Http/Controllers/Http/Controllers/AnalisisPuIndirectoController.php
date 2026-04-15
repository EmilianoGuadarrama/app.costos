<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AnalisisPu;
use App\Models\AnalisisPuIndirecto;
use App\Models\Indirecto;
use Illuminate\Http\Request;

class AnalisisPuIndirectoController extends Controller
{
    public function index()
    {
        $registros = AnalisisPuIndirecto::with(['analisisPu', 'indirecto'])->latest()->get();
        return view('analisis_pu_indirectos.index', compact('registros'));
    }

    public function create()
    {
        $analisis = AnalisisPu::all();
        $indirectos = Indirecto::orderBy('concepto')->get();
        return view('analisis_pu_indirectos.create', compact('analisis', 'indirectos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'analisis_pu_id' => 'required|exists:analisis_pu,id',
            'indirecto_id' => 'required|exists:indirectos,id',
            'porcentaje_aplicado' => 'required|numeric|min:0',
        ]);

        AnalisisPuIndirecto::create($data);

        return redirect()->route('analisis_pu_indirectos.index')->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        $registro = AnalisisPuIndirecto::with(['analisisPu', 'indirecto'])->findOrFail($id);
        return view('analisis_pu_indirectos.show', compact('registro'));
    }

    public function edit($id)
    {
        $registro = AnalisisPuIndirecto::findOrFail($id);
        $analisis = AnalisisPu::all();
        $indirectos = Indirecto::orderBy('concepto')->get();

        return view('analisis_pu_indirectos.edit', compact('registro', 'analisis', 'indirectos'));
    }

    public function update(Request $request, $id)
    {
        $registro = AnalisisPuIndirecto::findOrFail($id);

        $data = $request->validate([
            'analisis_pu_id' => 'required|exists:analisis_pu,id',
            'indirecto_id' => 'required|exists:indirectos,id',
            'porcentaje_aplicado' => 'required|numeric|min:0',
        ]);

        $registro->update($data);

        return redirect()->route('analisis_pu_indirectos.index')->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $registro = AnalisisPuIndirecto::findOrFail($id);
        $registro->delete();

        return redirect()->route('analisis_pu_indirectos.index')->with('success', 'Registro eliminado correctamente.');
    }
}
