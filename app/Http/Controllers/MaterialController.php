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
        $compras = \App\Models\EgresoTotal::with('material.unidadMedida', 'obra.datosDeObra', 'preProveedor.proveedor')
            ->whereNotNull('id_material')
            ->orderBy('fecha', 'desc')
            ->get();
            
        return view('materiales.index', compact('materiales', 'compras'));
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
            'id_unidad_medida'  => 'required|exists:unidades_medida,id',
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
            'id_unidad_medida' => 'required|exists:unidades_medida,id',
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

    /** POST /api/materiales/rapida */
    public function storeRapida(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'id_unidad_medida' => 'nullable|exists:unidades_medida,id',
            'precio_x_unidad'  => 'required|numeric|min:0',
        ]);
        $material = Material::create([
            'nombre'           => trim($request->nombre),
            'descripcion'      => trim($request->descripcion ?? ''),
            'marca'            => trim($request->marca ?? ''),
            'id_unidad_medida' => $request->id_unidad_medida ?: null,
            'precio_x_unidad'  => $request->precio_x_unidad,
        ]);
        $material->load('unidadMedida');
        return response()->json([
            'id'      => $material->id,
            'texto'   => $material->nombre,
            'pu'      => (float) $material->precio_x_unidad,
            'uni'     => $material->id_unidad_medida,
            'uniTxt'  => $material->unidadMedida?->abreviatura ?? '',
        ]);
    }
}