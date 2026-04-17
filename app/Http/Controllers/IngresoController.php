<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Proyecto;
use App\Models\Cliente;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    public function index()
    {
        $ingresos = Ingreso::with(['proyecto', 'cliente'])->latest()->get();
        return view('ingresos.index', compact('ingresos'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        return view('ingresos.create', compact('proyectos', 'clientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'comprobante' => 'nullable|string|max:255',
        ]);

        Ingreso::create($data);

        return redirect()->route('ingresos.index')->with('success', 'Ingreso creado correctamente.');
    }

    public function show(Ingreso $ingreso)
    {
        $ingreso->load(['proyecto', 'cliente']);
        return view('ingresos.show', compact('ingreso'));
    }

    public function edit(Ingreso $ingreso)
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        return view('ingresos.edit', compact('ingreso', 'proyectos', 'clientes'));
    }

    public function update(Request $request, Ingreso $ingreso)
    {
        $data = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'comprobante' => 'nullable|string|max:255',
        ]);

        $ingreso->update($data);

        return redirect()->route('ingresos.index')->with('success', 'Ingreso actualizado correctamente.');
    }

    public function destroy(Ingreso $ingreso)
    {
        $ingreso->delete();
        return redirect()->route('ingresos.index')->with('success', 'Ingreso eliminado correctamente.');
    }
}
