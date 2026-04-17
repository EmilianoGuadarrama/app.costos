<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proyecto;
use App\Models\Proveedor;
use App\Models\Area;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with(['proyecto', 'proveedor', 'area'])->latest()->get();
        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();
        $areas = Area::orderBy('nombre')->get();
        return view('compras.create', compact('proyectos', 'proveedores', 'areas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'area_id' => 'required|exists:areas,id',
            'fecha_compra' => 'required|date',
            'estado' => 'required|string|max:50',
            'factura' => 'nullable|string|max:100',
        ]);
        
        $data['total'] = 0; // Se calcula con los detalles después

        Compra::create($data);

        return redirect()->route('compras.index')->with('success', 'Compra creada correctamente.');
    }

    public function show(Compra $compra)
    {
        $compra->load(['proyecto', 'proveedor', 'area']);
        return view('compras.show', compact('compra'));
    }

    public function edit(Compra $compra)
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();
        $areas = Area::orderBy('nombre')->get();
        return view('compras.edit', compact('compra', 'proyectos', 'proveedores', 'areas'));
    }

    public function update(Request $request, Compra $compra)
    {
        $data = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'area_id' => 'required|exists:areas,id',
            'fecha_compra' => 'required|date',
            'estado' => 'required|string|max:50',
            'factura' => 'nullable|string|max:100',
        ]);

        $compra->update($data);

        return redirect()->route('compras.index')->with('success', 'Compra actualizada correctamente.');
    }

    public function destroy(Compra $compra)
    {
        $compra->delete();
        return redirect()->route('compras.index')->with('success', 'Compra eliminada correctamente.');
    }
}
