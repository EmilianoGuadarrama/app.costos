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

    /** GET /api/unidad_medida/lista  — para autocompletado */
    public function lista(Request $request)
    {
        $q = $request->input('q', '');
        $unidades = UnidadMedida::when($q, fn($query) => $query->where('abreviatura','like',"%{$q}%")->orWhere('nombre','like',"%{$q}%"))
            ->orderBy('abreviatura')->limit(30)->get()
            ->map(fn($u) => ['id' => $u->id, 'texto' => $u->abreviatura.' — '.$u->nombre, 'abreviatura' => $u->abreviatura]);
        return response()->json($unidades);
    }

    /** POST /api/unidad_medida/rapida  — crea una unidad rápida desde el formulario */
    public function storeRapida(Request $request)
    {
        $request->validate([
            'abreviatura' => 'required|string|max:50',
            'nombre'      => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $um = UnidadMedida::firstOrCreate(
            ['abreviatura' => strtoupper(trim($request->abreviatura))],
            [
                'nombre'      => trim($request->nombre ?? $request->abreviatura),
                'descripcion' => $request->descripcion ? trim($request->descripcion) : null
            ]
        );
        return response()->json(['id' => $um->id, 'abreviatura' => $um->abreviatura, 'texto' => $um->abreviatura.' — '.$um->nombre]);
    }
}
