<?php
namespace App\Http\Controllers;

use App\Models\DiaInhabil;
use Illuminate\Http\Request;

class DiasInhabilesController extends Controller
{
    public function index()
    {
        return response()->json(
            DiaInhabil::orderBy('fecha')
                ->get()
                ->map(fn($d) => [
                    'id'          => $d->id,
                    'fecha'       => $d->fecha->format('Y-m-d'),
                    'descripcion' => $d->descripcion ?? '',
                ])
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'descripcion' => 'nullable|string|max:150',
        ]);
        $dia = DiaInhabil::firstOrCreate(
            ['fecha' => $request->fecha],
            ['descripcion' => $request->descripcion]
        );
        return response()->json(['id' => $dia->id, 'fecha' => $dia->fecha->format('Y-m-d'), 'descripcion' => $dia->descripcion]);
    }

    public function destroy($id)
    {
        DiaInhabil::findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }
}
