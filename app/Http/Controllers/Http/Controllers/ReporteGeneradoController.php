<?php

namespace App\Http\Controllers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto;
use App\Models\ReporteGenerado;
use Illuminate\Http\Request;

class ReporteGeneradoController extends Controller
{
    public function index()
    {
        $reportes = ReporteGenerado::with('presupuesto')->latest()->get();
        return view('reportes.index', compact('reportes'));
    }

    public function create()
    {
        $presupuestos = Presupuesto::orderBy('nombre')->get();
        return view('reportes.create', compact('presupuestos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'presupuesto_id' => 'required|exists:presupuestos,id',
            'nombre' => 'required|string|max:150',
            'tipo_salida' => 'required|in:pdf,excel,vista',
            'ruta_archivo' => 'nullable|string|max:255',
            'fecha_generacion' => 'required|date',
        ]);

        ReporteGenerado::create($data);

        return redirect()->route('reportes.index')->with('success', 'Reporte creado correctamente.');
    }

    public function show($id)
    {
        $reporte = ReporteGenerado::with('presupuesto')->findOrFail($id);
        return view('reportes.show', compact('reporte'));
    }

    public function edit($id)
    {
        $reporte = ReporteGenerado::findOrFail($id);
        $presupuestos = Presupuesto::orderBy('nombre')->get();

        return view('reportes.edit', compact('reporte', 'presupuestos'));
    }

    public function update(Request $request, $id)
    {
        $reporte = ReporteGenerado::findOrFail($id);

        $data = $request->validate([
            'presupuesto_id' => 'required|exists:presupuestos,id',
            'nombre' => 'required|string|max:150',
            'tipo_salida' => 'required|in:pdf,excel,vista',
            'ruta_archivo' => 'nullable|string|max:255',
            'fecha_generacion' => 'required|date',
        ]);

        $reporte->update($data);

        return redirect()->route('reportes.index')->with('success', 'Reporte actualizado correctamente.');
    }

    public function destroy($id)
    {
        $reporte = ReporteGenerado::findOrFail($id);
        $reporte->delete();

        return redirect()->route('reportes.index')->with('success', 'Reporte eliminado correctamente.');
    }
}
