<?php

namespace App\Http\Controllers;

use App\Models\CajaChica;
use App\Models\Proyecto;
use App\Models\ResponsableTecnico;
use Illuminate\Http\Request;

class CajaChicaController extends Controller
{
    public function index()
    {
        $cajas = CajaChica::with(['proyecto', 'responsable'])->latest()->get();
        return view('cajas_chicas.index', compact('cajas'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $responsables = ResponsableTecnico::orderBy('nombre')->get();
        return view('cajas_chicas.create', compact('proyectos', 'responsables'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'monto_inicial' => 'required|numeric|min:0',
            'responsable_id' => 'required|exists:responsable_tecnicos,id',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);
        
        $data['saldo_actual'] = $data['monto_inicial'];

        CajaChica::create($data);

        return redirect()->route('cajas_chicas.index')->with('success', 'Caja chica creada correctamente.');
    }

    public function show(CajaChica $cajas_chica)
    {
        $cajas_chica->load(['proyecto', 'responsable']);
        return view('cajas_chicas.show', compact('cajas_chica'));
    }

    public function edit(CajaChica $cajas_chica)
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $responsables = ResponsableTecnico::orderBy('nombre')->get();
        return view('cajas_chicas.edit', compact('cajas_chica', 'proyectos', 'responsables'));
    }

    public function update(Request $request, CajaChica $cajas_chica)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'responsable_id' => 'required|exists:responsable_tecnicos,id',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        $cajas_chica->update($data);

        return redirect()->route('cajas_chicas.index')->with('success', 'Caja chica actualizada correctamente.');
    }

    public function destroy(CajaChica $cajas_chica)
    {
        $cajas_chica->delete();
        return redirect()->route('cajas_chicas.index')->with('success', 'Caja chica eliminada correctamente.');
    }
}
