<?php

namespace App\Http\Controllers;

use App\Models\AnalisisPu;
use App\Models\Concepto;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class AnalisisPuController extends Controller
{
    public function index()
    {
        $puItems = AnalisisPu::with([
            'concepto.unidadMedida',
            'materiales',
            'manoObras',
            'maquinarias',
            'indirectos'
        ])->latest()->get();

        return view('pu.index', compact('puItems'));
    }

    public function create()
    {
        $conceptos = Concepto::with('unidadMedida')->orderBy('descripcion')->get();
        $proyectos = Proyecto::orderBy('nombre')->get();
        return view('pu.create', compact('conceptos', 'proyectos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'concepto_id' => 'required|exists:conceptos,id',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        AnalisisPu::create($data);

        return redirect()->route('analisis_pu.index')->with('success', 'Análisis P.U creado correctamente.');
    }

    public function show($id)
    {
        $puItem = AnalisisPu::with([
            'concepto.unidadMedida',
            'materiales.material',
            'manoObras.manoObra',
            'maquinarias.maquinariaEquipo',
            'indirectos.indirecto'
        ])->findOrFail($id);

        return view('pu.show', compact('puItem'));
    }

    public function edit($id)
    {
        $puItem = AnalisisPu::findOrFail($id);
        $conceptos = Concepto::with('unidadMedida')->orderBy('descripcion')->get();
        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('pu.edit', compact('puItem', 'conceptos', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $puItem = AnalisisPu::findOrFail($id);

        $data = $request->validate([
            'concepto_id' => 'required|exists:conceptos,id',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        $puItem->update($data);

        return redirect()->route('analisis_pu.index')->with('success', 'Análisis P.U actualizado correctamente.');
    }

    public function destroy($id)
    {
        $puItem = AnalisisPu::findOrFail($id);
        $puItem->delete();

        return redirect()->route('analisis_pu.index')->with('success', 'Análisis P.U eliminado correctamente.');
    }
}
