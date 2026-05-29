<?php
namespace App\Http\Controllers;

use App\Models\EgresoTotal;
use App\Models\ObraIniciada;
use App\Models\Area;
use App\Models\Persona;
use Illuminate\Http\Request;

class EgresoController extends Controller
{
    public function index()
    {
        $egresosList = EgresoTotal::with(['obra.datosDeObra','area','persona'])
            ->orderBy('fecha', 'desc')->get();
            
        $egresos = $egresosList->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->fecha)->isoFormat('MMMM YYYY');
        });

        return view('egresos.index', compact('egresos'));
    }

    public function create()
    {
        $obras     = ObraIniciada::with('datosDeObra')->get();
        $areas     = Area::orderBy('abreviatura')->get();
        $personas  = Persona::orderBy('nombre')->get();
        return view('egresos.create', compact('obras','areas','personas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_obra'   => 'required|exists:obras_iniciadas,id',
            'fecha'     => 'required|date',
            'pago'      => 'required|numeric|min:0',
            'concepto'  => 'nullable|string',
        ]);
        EgresoTotal::create($request->only(
            'id_area','id_persona','fecha','concepto','pago','id_obra','categoria'
        ));
        return redirect()->route('egresos.index')->with('success', 'Egreso registrado.');
    }

    public function destroy($id)
    {
        EgresoTotal::findOrFail($id)->delete();
        return redirect()->route('egresos.index')->with('success', 'Egreso eliminado.');
    }
}
