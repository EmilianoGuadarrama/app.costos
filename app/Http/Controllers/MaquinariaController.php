<?php
namespace App\Http\Controllers;

use App\Models\Maquinaria;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class MaquinariaController extends Controller
{
    public function index()
    {
        $maquinarias = Maquinaria::with('unidadMedida')->orderBy('nombre')->paginate(50);
        return view('maquinaria.index', compact('maquinarias'));
    }

    public function create()
    {
        $unidades = UnidadMedida::orderBy('abreviatura')->get();
        return view('maquinaria.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'id_unidad_medida' => 'required|exists:unidad_medida,id',
            'precio_x_unidad'  => 'required|numeric|min:0',
        ]);
        Maquinaria::create($request->only('nombre','descripcion','id_unidad_medida','precio_x_unidad'));
        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria creada.');
    }

    public function edit($id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        $unidades   = UnidadMedida::orderBy('abreviatura')->get();
        return view('maquinaria.edit', compact('maquinaria','unidades'));
    }

    public function update(Request $request, $id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'id_unidad_medida' => 'required|exists:unidad_medida,id',
            'precio_x_unidad'  => 'required|numeric|min:0',
        ]);
        $maquinaria->update($request->only('nombre','descripcion','id_unidad_medida','precio_x_unidad'));
        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria actualizada.');
    }

    public function destroy($id)
    {
        Maquinaria::findOrFail($id)->delete();
        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria eliminada.');
    }
}
