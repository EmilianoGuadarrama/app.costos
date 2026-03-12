<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concepto;

class ConceptoController extends Controller
{

    public function index()
    {
        $conceptos = Concepto::all();
        return view('conceptos.index', compact('conceptos'));
    }

    public function create()
    {
        return view('conceptos.create');
    }

    public function store(Request $request)
    {
        Concepto::create($request->only([
            'codigo','partida','subpartida','descripcion','unidad','cantidad','pu','importe'
        ]));
    }

    public function show($id)
    {
        $concepto = Concepto::findOrFail($id);
        return view('conceptos.show', compact('concepto'));
    }

    public function edit($id)
    {
        $concepto = Concepto::findOrFail($id);
        return view('conceptos.edit', compact('concepto'));
    }

    public function update(Request $request, $id)
    {
        $concepto = Concepto::findOrFail($id);
        $concepto->update($request->all());

        return redirect()->route('conceptos.index');
    }

    public function destroy($id)
    {
        $concepto = Concepto::findOrFail($id);
        $concepto->delete();

        return redirect()->route('conceptos.index');
    }
}
