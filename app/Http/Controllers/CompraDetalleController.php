<?php

namespace App\Http\Controllers;

use App\Models\CompraDetalle;
use App\Models\Compra;
use App\Models\Concepto;
use Illuminate\Http\Request;

class CompraDetalleController extends Controller
{
    public function index()
    {
        $detalles = CompraDetalle::with(['compra', 'concepto'])->latest()->get();
        return view('compra_detalles.index', compact('detalles'));
    }

    public function create()
    {
        $compras = Compra::orderBy('id')->get();
        $conceptos = Concepto::orderBy('descripcion')->get();
        return view('compra_detalles.create', compact('compras', 'conceptos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'compra_id' => 'required|exists:compras,id',
            'concepto_id' => 'required|exists:conceptos,id',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0',
        ]);
        
        $data['subtotal'] = $data['cantidad'] * $data['precio_unitario'];

        CompraDetalle::create($data);

        return redirect()->route('compra_detalles.index')->with('success', 'Detalle de compra creado correctamente.');
    }

    public function show(CompraDetalle $compra_detalle)
    {
        $compra_detalle->load(['compra', 'concepto']);
        return view('compra_detalles.show', compact('compra_detalle'));
    }

    public function edit(CompraDetalle $compra_detalle)
    {
        $compras = Compra::orderBy('id')->get();
        $conceptos = Concepto::orderBy('descripcion')->get();
        return view('compra_detalles.edit', compact('compra_detalle', 'compras', 'conceptos'));
    }

    public function update(Request $request, CompraDetalle $compra_detalle)
    {
        $data = $request->validate([
            'compra_id' => 'required|exists:compras,id',
            'concepto_id' => 'required|exists:conceptos,id',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0',
        ]);
        
        $data['subtotal'] = $data['cantidad'] * $data['precio_unitario'];

        $compra_detalle->update($data);

        return redirect()->route('compra_detalles.index')->with('success', 'Detalle de compra actualizado correctamente.');
    }

    public function destroy(CompraDetalle $compra_detalle)
    {
        $compra_detalle->delete();
        return redirect()->route('compra_detalles.index')->with('success', 'Detalle de compra eliminado correctamente.');
    }
}
