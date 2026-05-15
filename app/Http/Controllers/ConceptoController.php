<?php
namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\Area;
use Illuminate\Http\Request;

class ConceptoController extends Controller
{
    public function index()
    {
        $conceptos = Concepto::with('area', 'unidadMedida')->orderBy('descripcion')->paginate(50);
        return view('conceptos.index', compact('conceptos'));
    }

    public function create()
    {
        $areas = Area::orderBy('abreviatura')->get();
        $unidades = \App\Models\UnidadMedida::orderBy('abreviatura')->get();
        return view('conceptos.create', compact('areas', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_area'       => 'required|exists:areas,id',
            'id_unidad_medida' => 'required|exists:unidad_medida,id',
            'descripcion'   => 'required|string',
            'p_u'           => 'required|numeric|min:0',
            'duracion_en_dias' => 'nullable|integer|min:0',
        ]);
        Concepto::create($request->only('id_area','id_unidad_medida','descripcion','p_u','duracion_en_dias'));
        return redirect()->route('conceptos.index')->with('success', 'Concepto creado.');
    }

    public function show($id)
    {
        $concepto = Concepto::with('area')->findOrFail($id);
        return view('conceptos.show', compact('concepto'));
    }

    public function edit($id)
    {
        $concepto = Concepto::findOrFail($id);
        $areas    = Area::orderBy('abreviatura')->get();
        $unidades = \App\Models\UnidadMedida::orderBy('abreviatura')->get();
        return view('conceptos.edit', compact('concepto','areas','unidades'));
    }

    public function update(Request $request, $id)
    {
        $concepto = Concepto::findOrFail($id);
        $request->validate([
            'id_area'    => 'required|exists:areas,id',
            'id_unidad_medida' => 'required|exists:unidad_medida,id',
            'descripcion'=> 'required|string',
            'p_u'        => 'required|numeric|min:0',
        ]);
        $concepto->update($request->only('id_area','id_unidad_medida','descripcion','p_u','duracion_en_dias'));
        return redirect()->route('conceptos.index')->with('success', 'Concepto actualizado.');
    }

    public function destroy($id)
    {
        Concepto::findOrFail($id)->delete();
        return redirect()->route('conceptos.index')->with('success', 'Concepto eliminado.');
    }
}
