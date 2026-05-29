<?php
namespace App\Http\Controllers;

use App\Models\CajaGeneral;
use App\Models\ObraIniciada;
use Illuminate\Http\Request;

class CajaGeneralController extends Controller
{
    public function index()
    {
        $cajas = CajaGeneral::whereHas('obra.obraProceso')
            ->with(['obra.datosDeObra', 'obra.ingresos', 'obra.egresos'])
            ->get();
        return view('caja_general.index', compact('cajas'));
    }

    public function show($id)
    {
        $caja = CajaGeneral::with([
            'obra.datosDeObra',
            'obra.ingresos.empleado.persona',
            'obra.egresos.area',
        ])->findOrFail($id);
        return view('caja_general.show', compact('caja'));
    }
}
