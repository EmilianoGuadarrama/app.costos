<?php
namespace App\Http\Controllers;

use App\Models\PreProveedor;
use App\Models\Proveedor;
use App\Models\Area;
use App\Models\ObraIniciada;
use App\Models\EgresoTotal;
use Illuminate\Http\Request;

class PreProveedorController extends Controller
{
    public function index()
    {
        $pendientes = PreProveedor::with(['proveedor', 'area', 'obra.datosDeObra'])->where('estado', 'pendiente')->latest()->get();
        $aprobados = PreProveedor::with(['proveedor', 'area', 'obra.datosDeObra'])->where('estado', 'aprobado')->where('saldo', '>', 0)->latest()->get();
        $finalizados = PreProveedor::with(['proveedor', 'area', 'obra.datosDeObra'])->where('estado', 'aprobado')->where('saldo', '<=', 0)->latest()->get();
        $papelera = PreProveedor::onlyTrashed()->with(['proveedor', 'area', 'obra.datosDeObra'])->latest()->get();

        $proveedores = Proveedor::orderBy('empresa')->get();
        $areas = Area::orderBy('abreviatura')->get();
        $obras = ObraIniciada::whereHas('obraProceso')->with('datosDeObra')->get();
        
        return view('pre_proveedores.index', compact('pendientes', 'aprobados', 'finalizados', 'papelera', 'proveedores', 'areas', 'obras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id',
            'id_area'      => 'required|exists:areas,id',
            'id_obra'      => 'nullable|exists:obras_iniciadas,id',
            'presupuesto'  => 'required|numeric|min:0',
            'extras'       => 'nullable|numeric|min:0',
        ]);

        $presupuesto = $request->presupuesto;
        $extras = $request->extras ?? 0;
        $total = $presupuesto + $extras;

        PreProveedor::create([
            'id_proveedor' => $request->id_proveedor,
            'id_area'      => $request->id_area,
            'id_obra'      => $request->id_obra,
            'presupuesto'  => $presupuesto,
            'extras'       => $extras,
            'total'        => $total,
            'saldo'        => $total,
            'pagado'       => 0,
            'estado'       => 'pendiente',
        ]);

        return redirect()->to(route('pre_proveedores.index') . '#pendientes')->with('success', 'Presupuesto de proveedor registrado correctamente.');
    }

    public function aprobar($id)
    {
        $preProveedor = PreProveedor::findOrFail($id);
        $preProveedor->estado = 'aprobado';
        $preProveedor->save();

        return redirect()->to(route('pre_proveedores.index') . '#aprobados')->with('success', 'Presupuesto aprobado correctamente.');
    }

    public function registrarPago(Request $request, $id)
    {
        $request->validate([
            'monto_pago' => 'required|numeric|min:0.01',
        ]);

        $preProveedor = PreProveedor::with('proveedor')->findOrFail($id);

        if ($request->monto_pago > $preProveedor->saldo) {
            return redirect()->back()->withErrors(['monto_pago' => 'El pago no puede superar el saldo pendiente.']);
        }

        // 1. Actualizar el saldo y pagado del proveedor
        $preProveedor->pagado += $request->monto_pago;
        $preProveedor->saldo -= $request->monto_pago;
        $preProveedor->save();

        // 2. Registrar el egreso en la Caja General
        EgresoTotal::create([
            'id_obra'    => $preProveedor->id_obra, // Null si es egreso general
            'id_area'    => $preProveedor->id_area,
            'id_persona' => $preProveedor->proveedor->id_persona ?? null,
            'fecha'      => now(),
            'concepto'   => 'Pago a proveedor: ' . ($preProveedor->proveedor->empresa ?? 'S/N'),
            'pago'       => $request->monto_pago,
            'categoria'  => 'Pago a Proveedor',
        ]);

        $url = url()->previous();
        if (str_contains($url, 'proveedores/presupuestos')) {
            return redirect()->to(route('pre_proveedores.index') . '#aprobados')->with('success', 'Pago registrado correctamente y añadido a Egresos.');
        }
        return redirect()->back()->with('success', 'Pago registrado correctamente y añadido a Egresos.');
    }

    public function updateExtras(Request $request, $id)
    {
        $request->validate(['extras' => 'required|numeric|min:0']);
        $preProveedor = PreProveedor::findOrFail($id);

        $diferenciaExtras = $request->extras - $preProveedor->extras;
        $preProveedor->extras = $request->extras;
        $preProveedor->total += $diferenciaExtras;
        $preProveedor->saldo += $diferenciaExtras;
        $preProveedor->save();

        $estado = $preProveedor->estado;
        $tab = $estado == 'pendiente' ? '#pendientes' : ($estado == 'aprobado' ? ($preProveedor->saldo <= 0 ? '#finalizados' : '#aprobados') : '#papelera');
        
        $url = url()->previous();
        if (str_contains($url, 'proveedores/presupuestos')) {
            return redirect()->to(route('pre_proveedores.index') . $tab)->with('success', 'Extras actualizados correctamente.');
        }
        return redirect()->back()->with('success', 'Extras actualizados correctamente.');
    }

    public function destroy($id)
    {
        $p = PreProveedor::findOrFail($id);
        $tab = $p->estado == 'pendiente' ? '#pendientes' : ($p->saldo <= 0 ? '#finalizados' : '#aprobados');
        $p->delete();
        return redirect()->to(route('pre_proveedores.index') . $tab)->with('success', 'Presupuesto movido a la papelera.');
    }

    public function restore($id)
    {
        PreProveedor::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->to(route('pre_proveedores.index') . '#papelera')->with('success', 'Presupuesto restaurado.');
    }
}
