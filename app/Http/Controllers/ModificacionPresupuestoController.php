<?php

namespace App\Http\Controllers;

use App\Models\ModificacionPresupuesto;
use App\Models\Presupuesto;
use Illuminate\Http\Request;

class ModificacionPresupuestoController extends Controller
{
    public function index($presupuesto_id)
    {
        $presupuesto = Presupuesto::findOrFail($presupuesto_id);
        $modificaciones = $presupuesto->modificaciones()->orderBy('fecha', 'desc')->get();

        return view('modificaciones.index', compact('presupuesto', 'modificaciones'));
    }

    public function create($presupuesto_id)
    {
        $presupuesto = Presupuesto::findOrFail($presupuesto_id);
        return view('modificaciones.create', compact('presupuesto'));
    }

    public function store(Request $request, $presupuesto_id)
    {
        $presupuesto = Presupuesto::findOrFail($presupuesto_id);

        $request->validate([
            'tipo' => 'required|in:aditiva,deductiva',
            'monto' => 'required|numeric|min:0.01',
            'motivo' => 'required|string',
            'fecha' => 'required|date',
            'estado' => 'required|string'
        ]);

        $presupuesto->modificaciones()->create($request->all());

        return redirect()->route('modificaciones.index', $presupuesto->id)
                         ->with('success', 'Modificación agregada correctamente.');
    }

    public function destroy($id)
    {
        $modificacion = ModificacionPresupuesto::findOrFail($id);
        $presupuesto_id = $modificacion->presupuesto_id;
        $modificacion->delete();

        return redirect()->route('modificaciones.index', $presupuesto_id)
                         ->with('success', 'Modificación eliminada.');
    }
}
