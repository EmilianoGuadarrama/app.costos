<?php
namespace App\Http\Controllers;

use App\Models\IngresoTotal;
use App\Models\ObraIniciada;
use App\Models\Empleado;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    public function index()
    {
        $ingresosList = IngresoTotal::with(['obra.datosDeObra','empleado.persona'])
            ->orderBy('fecha', 'desc')->get();
            
        $ingresos = $ingresosList->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->fecha)->isoFormat('MMMM YYYY');
        });

        return view('ingresos.index', compact('ingresos'));
    }

    public function create()
    {
        $obras     = ObraIniciada::with(['datosDeObra', 'obraProceso'])->get();
        $empleados = Empleado::with('persona')->get();
        return view('ingresos.create', compact('obras','empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_obra'     => 'required|exists:obras_iniciadas,id',
            'id_empleado' => 'nullable|exists:empleados,id',
            'fecha'       => 'required|date',
            'monto_dado'  => 'required|numeric|min:0',
            'concepto'    => 'nullable|string',
        ]);
        IngresoTotal::create($request->only(
            'concepto','id_empleado','id_obra','fecha',
            'id_total_obra_o_presupuesto','monto_dado','saldo_cubierto','porcentaje_cubierto'
        ));
        return redirect()->route('ingresos.index')->with('success', 'Ingreso registrado.');
    }

    public function destroy($id)
    {
        IngresoTotal::findOrFail($id)->delete();
        return redirect()->route('ingresos.index')->with('success', 'Ingreso eliminado.');
    }
}
