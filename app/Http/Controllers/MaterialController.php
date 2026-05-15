<?php
namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materiales = Material::with('unidadMedida')->orderBy('nombre')->paginate(50);
        return view('materiales.index', compact('materiales'));
    }

    public function create()
    {
        $unidades = UnidadMedida::orderBy('abreviatura')->get();
        return view('materiales.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'id_unidad_medida'  => 'required|exists:unidad_medida,id',
            'precio_x_unidad'   => 'required|numeric|min:0',
        ]);
        Material::create($request->only(
            'nombre','descripcion','marca','id_unidad_medida','cantidad_contenida','precio_x_unidad'
        ));
        return redirect()->route('materiales.index')->with('success', 'Material creado.');
    }

    public function show($id)
    {
        $material = Material::with('unidadMedida')->findOrFail($id);
        return view('materiales.show', compact('material'));
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        $unidades = UnidadMedida::orderBy('abreviatura')->get();
        return view('materiales.edit', compact('material','unidades'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'id_unidad_medida' => 'required|exists:unidad_medida,id',
            'precio_x_unidad'  => 'required|numeric|min:0',
        ]);
        $material->update($request->only(
            'nombre','descripcion','marca','id_unidad_medida','cantidad_contenida','precio_x_unidad'
        ));
        return redirect()->route('materiales.index')->with('success', 'Material actualizado.');
    }

    public function destroy($id)
    {
        Material::findOrFail($id)->delete();
        return redirect()->route('materiales.index')->with('success', 'Material eliminado.');
    }
}