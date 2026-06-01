<?php
namespace App\Http\Controllers;

use App\Models\EgresoTotal;
use App\Models\ObraIniciada;
use App\Models\Area;
use App\Models\Persona;
use App\Models\Material;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EgresoController extends Controller
{
    public function index()
    {
        $egresosList = EgresoTotal::with(['obra.datosDeObra','area','persona','material'])
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
        $materiales = Material::orderBy('nombre')->get();
        return view('egresos.create', compact('obras','areas','personas','materiales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_obra'           => 'required|exists:obras_iniciadas,id',
            'fecha'             => 'required|date',
            'pago'              => 'required|numeric|min:0',
            'concepto'          => 'nullable|string',
            'id_material'       => 'nullable|exists:materiales,id',
            'cantidad_material' => 'nullable|numeric|min:0'
        ]);
        EgresoTotal::create($request->only(
            'id_area','id_persona','fecha','concepto','pago','id_obra','categoria','id_material','cantidad_material'
        ));
        return redirect()->route('egresos.index')->with('success', 'Egreso registrado.');
    }

    public function destroy($id)
    {
        EgresoTotal::findOrFail($id)->delete();
        return redirect()->route('egresos.index')->with('success', 'Egreso eliminado.');
    }

    /**
     * Genera PDF de egresos del mes indicado.
     * Ruta: GET /egresos/pdf/{anio}/{mes}
     */
    public function pdfMes(int $anio, int $mes)
    {
        $egresos = EgresoTotal::with(['obra.datosDeObra', 'area', 'persona', 'material'])
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->orderBy('fecha', 'asc')
            ->get();

        $mesNombre = \Carbon\Carbon::createFromDate($anio, $mes, 1)
            ->isoFormat('MMMM [de] YYYY');

        $pdf = Pdf::loadView('egresos.pdf_mes', compact('egresos', 'mesNombre'))
            ->setPaper('letter', 'landscape');

        $nombreArchivo = 'egresos_' . $anio . '_' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}
