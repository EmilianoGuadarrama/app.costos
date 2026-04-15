<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AnalisisPu;
use App\Models\AnalisisPuMaquinaria;
use App\Models\MaquinariaEquipo;
use Illuminate\Http\Request;

class AnalisisPuMaquinariaController extends Controller
{
    public function index()
    {
        $registros = AnalisisPuMaquinaria::with(['analisisPu', 'maquinariaEquipo'])->latest()->get();
        return view('analisis_pu_maquinaria.index', compact('registros'));
    }

    public function create()
    {
        $analisis = AnalisisPu::all();
        $maquinarias = MaquinariaEquipo::orderBy('equipo')->get();
        return view('analisis_pu_maquinaria.create', compact('analisis', 'maquinarias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'analisis_pu_id' => 'required|exists:analisis_pu,id',
            'maquinaria_equipo_id' => 'required|exists:maquinaria_equipos,id',
            'cantidad' => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
        ]);

        AnalisisPuMaquinaria::create($data);

        return redirect()->route('analisis_pu_maquinaria.index')->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        $registro = AnalisisPuMaquinaria::with(['analisisPu', 'maquinariaEquipo'])->findOrFail($id);
        return view('analisis_pu_maquinaria.show', compact('registro'));
    }

    public function edit($id)
    {
        $registro = AnalisisPuMaquinaria::findOrFail($id);
        $analisis = AnalisisPu::all();
        $maquinarias = MaquinariaEquipo::orderBy('equipo')->get();

        return view('analisis_pu_maquinaria.edit', compact('registro', 'analisis', 'maquinarias'));
    }

    public function update(Request $request, $id)
    {
        $registro = AnalisisPuMaquinaria::findOrFail($id);

        $data = $request->validate([
            'analisis_pu_id' => 'required|exists:analisis_pu,id',
            'maquinaria_equipo_id' => 'required|exists:maquinaria_equipos,id',
            'cantidad' => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
        ]);

        $registro->update($data);

        return redirect()->route('analisis_pu_maquinaria.index')->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $registro = AnalisisPuMaquinaria::findOrFail($id);
        $registro->delete();

        return redirect()->route('analisis_pu_maquinaria.index')->with('success', 'Registro eliminado correctamente.');
    }
}
