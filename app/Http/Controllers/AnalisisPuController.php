<?php

namespace App\Http\Controllers;

use App\Models\AnalisisPu;
use App\Models\Proyecto;
use App\Models\Concepto;
use Illuminate\Http\Request;

class AnalisisPuController extends Controller
{
    public function index()
    {
        $analisis = AnalisisPu::with(['proyecto', 'concepto'])->latest()->get();
        return view('pu.index', compact('analisis'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $conceptos = Concepto::orderBy('clave')->get();

        return view('pu.create', compact('proyectos', 'conceptos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'concepto_id' => 'required|exists:conceptos,id',
        ], [
            'proyecto_id.required' => 'El proyecto es obligatorio.',
            'proyecto_id.exists' => 'El proyecto seleccionado no es válido.',
            'concepto_id.required' => 'El concepto es obligatorio.',
            'concepto_id.exists' => 'El concepto seleccionado no es válido.',
        ]);

        AnalisisPu::create([
            'proyecto_id' => $request->proyecto_id,
            'concepto_id' => $request->concepto_id,
        ]);

        return redirect()->route('analisis_pu.index')->with('success', 'Análisis P.U. creado correctamente.');
    }

    public function show($id)
    {
        $analisis = AnalisisPu::with(['proyecto', 'concepto'])->findOrFail($id);
        return view('pu.show', compact('analisis'));
    }

    public function edit($id)
    {
        $analisis = AnalisisPu::findOrFail($id);
        $proyectos = Proyecto::orderBy('nombre')->get();
        $conceptos = Concepto::orderBy('clave')->get();

        return view('pu.edit', compact('analisis', 'proyectos', 'conceptos'));
    }

    public function update(Request $request, $id)
    {
        $analisis = AnalisisPu::findOrFail($id);

        $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'concepto_id' => 'required|exists:conceptos,id',
        ], [
            'proyecto_id.required' => 'El proyecto es obligatorio.',
            'proyecto_id.exists' => 'El proyecto seleccionado no es válido.',
            'concepto_id.required' => 'El concepto es obligatorio.',
            'concepto_id.exists' => 'El concepto seleccionado no es válido.',
        ]);

        $analisis->update([
            'proyecto_id' => $request->proyecto_id,
            'concepto_id' => $request->concepto_id,
        ]);

        return redirect()->route('analisis_pu.index')->with('success', 'Análisis P.U. actualizado correctamente.');
    }

    public function destroy($id)
    {
        $analisis = AnalisisPu::findOrFail($id);
        $analisis->delete();

        return redirect()->route('analisis_pu.index')->with('success', 'Análisis P.U. eliminado correctamente.');
    }
}