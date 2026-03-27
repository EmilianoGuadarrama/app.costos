<?php

namespace App\Http\Controllers;

use App\Models\AnalisisPuManoObra;
use App\Models\AnalisisPu;
use App\Models\ManoObra;
use Illuminate\Http\Request;

class AnalisisPuManoObraController extends Controller
{
    public function index()
    {
        $registros = AnalisisPuManoObra::with(['analisisPu', 'manoObra'])->latest()->get();
        return view('analisis_pu_mano_obra.index', compact('registros'));
    }

    public function create()
    {
        $analisis = AnalisisPu::all();
        $manoObras = ManoObra::orderBy('categoria')->get();
        return view('analisis_pu_mano_obra.create', compact('analisis', 'manoObras'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'analisis_pu_id' => 'required|exists:analisis_pu,id',
            'mano_obra_id' => 'required|exists:mano_obra,id',
            'cantidad' => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
        ]);

        AnalisisPuManoObra::create($data);

        return redirect()->route('analisis_pu_mano_obra.index')->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        $registro = AnalisisPuManoObra::with(['analisisPu', 'manoObra'])->findOrFail($id);
        return view('analisis_pu_mano_obra.show', compact('registro'));
    }

    public function edit($id)
    {
        $registro = AnalisisPuManoObra::findOrFail($id);
        $analisis = AnalisisPu::all();
        $manoObras = ManoObra::orderBy('categoria')->get();

        return view('analisis_pu_mano_obra.edit', compact('registro', 'analisis', 'manoObras'));
    }

    public function update(Request $request, $id)
    {
        $registro = AnalisisPuManoObra::findOrFail($id);

        $data = $request->validate([
            'analisis_pu_id' => 'required|exists:analisis_pu,id',
            'mano_obra_id' => 'required|exists:mano_obra,id',
            'cantidad' => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
        ]);

        $registro->update($data);

        return redirect()->route('analisis_pu_mano_obra.index')->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $registro = AnalisisPuManoObra::findOrFail($id);
        $registro->delete();

        return redirect()->route('analisis_pu_mano_obra.index')->with('success', 'Registro eliminado correctamente.');
    }
}
