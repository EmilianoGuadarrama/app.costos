<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\PresupuestoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresupuestoVersionController extends Controller
{
    public function clone(Request $request, $id)
    {
        $presupuestoOriginal = Presupuesto::with('detalles')->findOrFail($id);

        try {
            DB::beginTransaction();

            // Marcar el presupuesto original como NO actual
            $presupuestoOriginal->es_version_actual = false;
            $presupuestoOriginal->save();

            // Determinar nueva versión
            $nuevaVersion = $presupuestoOriginal->version + 1;

            // Clonar la cabecera
            $nuevoPresupuesto = $presupuestoOriginal->replicate();
            $nuevoPresupuesto->version = $nuevaVersion;
            $nuevoPresupuesto->presupuesto_padre_id = $presupuestoOriginal->id;
            $nuevoPresupuesto->es_version_actual = true;
            $nuevoPresupuesto->fecha = now();
            // Mantener el nombre o agregarle sufijo (opcional)
            $nuevoPresupuesto->nombre = $presupuestoOriginal->nombre . ' (v' . $nuevaVersion . ')';
            $nuevoPresupuesto->save();

            // Clonar los detalles
            foreach ($presupuestoOriginal->detalles as $detalle) {
                $nuevoDetalle = $detalle->replicate();
                $nuevoDetalle->presupuesto_id = $nuevoPresupuesto->id;
                $nuevoDetalle->save();
            }

            // (Opcional) Las modificaciones del original NO se copian como detalles, 
            // ya que el total base del v2 es igual al total base del v1. 
            // Si las aditivas alteraron cantidades, deberíamos integrarlas en los detalles.
            // Para simplicidad en este sistema, empezamos la v2 "limpia" de aditivas.

            DB::commit();

            return redirect()->route('presupuestos.index')->with('success', 'Presupuesto clonado a la versión ' . $nuevaVersion . ' exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al clonar el presupuesto: ' . $e->getMessage());
        }
    }
}
