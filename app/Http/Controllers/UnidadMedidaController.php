<?php
namespace App\Http\Controllers;

use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class UnidadMedidaController extends Controller
{
    public function index()
    {
        $unidades = UnidadMedida::orderBy('abreviatura')->paginate(50);
        return view('unidad_medida.index', compact('unidades'));
    }

    public function create() { return view('unidad_medida.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'abreviatura' => 'required|string|max:50',
            'nombre'      => 'required|string|max:255',
        ]);
        UnidadMedida::create($request->only('abreviatura','nombre'));
        return redirect()->route('unidad_medida.index')->with('success', 'Unidad creada.');
    }

    public function edit($id)
    {
        $unidad = UnidadMedida::findOrFail($id);
        return view('unidad_medida.edit', compact('unidad'));
    }

    public function update(Request $request, $id)
    {
        $unidad = UnidadMedida::findOrFail($id);
        $request->validate([
            'abreviatura' => 'required|string|max:50',
            'nombre'      => 'required|string|max:255',
        ]);
        $unidad->update($request->only('abreviatura','nombre'));
        return redirect()->route('unidad_medida.index')->with('success', 'Unidad actualizada.');
    }

    public function destroy($id)
    {
        UnidadMedida::findOrFail($id)->delete();
        return redirect()->route('unidad_medida.index')->with('success', 'Unidad eliminada.');
    }
}
