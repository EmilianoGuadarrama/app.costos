<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Generador;

class GeneradorController extends Controller
{
    public function index()
    {
        $generadores = Generador::all();
        return view('generadores.index', compact('generadores'));
    }

    public function create()
    {
        return view('generadores.create');
    }

    public function store(Request $request)
    {
        Generador::create($request->all());
        return redirect()->route('generadores.index');
    }

    public function edit($id)
    {
        $generador = Generador::findOrFail($id);
        return view('generadores.edit', compact('generador'));
    }

    public function update(Request $request, $id)
    {
        $generador = Generador::findOrFail($id);
        $generador->update($request->all());

        return redirect()->route('generadores.index');
    }

    public function destroy($id)
    {
        $generador = Generador::findOrFail($id);
        $generador->delete();

        return redirect()->route('generadores.index');
    }
}
