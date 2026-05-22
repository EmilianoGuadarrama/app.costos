<?php
namespace App\Http\Controllers;

use App\Models\ObraIniciada;
use App\Models\ObraConcepto;
use App\Models\AsignaMaterial;
use App\Models\AsignaMaquinaria;
use App\Models\AsignaManoObra;
use App\Models\Concepto;
use App\Models\Material;
use App\Models\Maquinaria;
use App\Models\ManoObra;
use App\Models\Bloque;
use App\Models\Nivel;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PresupuestoExport;

class PresupuestoController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // SHOW — Vista del presupuesto completo (Padre-Hijo)
    // ──────────────────────────────────────────────────────────────
    public function show($obraId)
    {
        $obra = ObraIniciada::with([
            'datosDeObra', 'encargado.persona', 'niveles', 'totalObra',
            'obraConceptos.bloque', 'obraConceptos.area', 'obraConceptos.nivel', 'obraConceptos.concepto.unidadMedida',
            'obraConceptos.materiales.material.unidadMedida',
            'obraConceptos.maquinaria.maquinaria.unidadMedida',
            'obraConceptos.manoObra.manoObra.unidadMedida'
        ])->findOrFail($obraId);

        $totalesPorBloque = \App\Models\TotalBloque::with('bloque')
            ->where('id_obra', $obraId)->get()->keyBy('id_bloque');

        $bloques = Bloque::orderBy('id')->get();

        return view('obras.presupuesto', compact('obra', 'totalesPorBloque', 'bloques'));
    }

    public function create($obraId)
    {
        return redirect()->route('obras.presupuesto.unificado.create', $obraId);
    }

    // ──────────────────────────────────────────────────────────────
    // UNIFICADO — Carga la vista para agregar renglones
    // ──────────────────────────────────────────────────────────────
    public function createUnificado($obraId)
    {
        $obra = ObraIniciada::with([
            'datosDeObra', 'niveles',
            'obraConceptos.concepto.unidadMedida',
            'obraConceptos.materiales.material',
            'obraConceptos.maquinaria.maquinaria',
            'obraConceptos.manoObra.manoObra'
        ])->findOrFail($obraId);

        $bloques    = Bloque::orderBy('id')->get();
        $areas      = Area::orderBy('abreviatura')->get();
        $conceptos  = Concepto::with('area', 'unidadMedida', 'composicion')->orderBy('descripcion')->get();
        $materiales = Material::with('unidadMedida')->orderBy('nombre')->get();
        $maquinaria = Maquinaria::with('unidadMedida')->orderBy('nombre')->get();
        $mano_obra  = ManoObra::with('unidadMedida')->orderBy('nombre')->get();
        $unidades   = \App\Models\UnidadMedida::orderBy('abreviatura')->get();
        $niveles    = $obra->niveles;

        return view('obras.presupuesto_unificado', compact(
            'obra', 'bloques', 'areas', 'conceptos', 'materiales', 'maquinaria', 'mano_obra', 'unidades', 'niveles'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    // GUARDAR CONCEPTOS Y SUS INSUMOS DE FORMA JERÁRQUICA
    // ──────────────────────────────────────────────────────────────
    public function storeUnificado(Request $request, $obraId)
    {
        ObraIniciada::findOrFail($obraId);

        // Se espera un payload JSON o form array con esta estructura:
        // 'conceptos' => [
        //    [ 'id_concepto', 'nombre_nuevo', 'id_bloque', 'id_area', 'cantidad', ... 
        //      'materiales' => [ [ 'id_material', 'cantidad', ... ] ],
        //      'maquinaria' => [ [ 'id_maquinaria', 'cantidad', ... ] ],
        //      'mano_obra'  => [ [ 'id_mano_obra', 'cantidad', ... ] ],
        //    ]
        // ]

        DB::beginTransaction();
        try {
            foreach (($request->conceptos ?? []) as $cData) {
                // 1. Resolver/Crear el Concepto Base
                $idConcepto = $cData['id_concepto'] ?? null;
                if (empty($idConcepto) && !empty($cData['nombre_nuevo'])) {
                    $c = Concepto::firstOrCreate(
                        ['descripcion' => trim($cData['nombre_nuevo'])],
                        [
                            'id_area'          => $cData['id_area'] ?? null,
                            'p_u'              => $cData['precio_unitario'] ?? 0,
                            'id_unidad_medida' => $cData['id_unidad_medida'] ?? null,
                        ]
                    );
                    $idConcepto = $c->id;
                }
                
                if (empty($idConcepto)) continue;

                $cantConcepto = floatval($cData['cantidad'] ?? 1);
                
                // 2. Crear el ObraConcepto (Padre)
                // El precio unitario lo calcularemos a partir de sus hijos, pero si el usuario puso uno manual, lo respetamos inicialmente.
                // En un presupuesto de matrices (PU), el P.U. del concepto es la suma de los (P.U. de insumos * su rendimiento).
                // Pero por ahora lo mantendremos simple: suma plana de hijos o P.U. manual.
                $obraConcepto = ObraConcepto::create([
                    'id_obra'         => $obraId,
                    'id_nivel'        => $cData['id_nivel'] ?? null,
                    'id_bloque'       => $cData['id_bloque'] ?? null,
                    'id_area'         => $cData['id_area'] ?? null,
                    'id_concepto'     => $idConcepto,
                    'cantidad'        => $cantConcepto,
                    'precio_unitario' => $cData['precio_unitario'] ?? 0,
                    'subtotal'        => 0,
                    'porcentaje_iva'  => $cData['porcentaje_iva'] ?? 16,
                    'iva'             => 0,
                    'total_final'     => 0,
                ]);

                $totalSubtotalConcepto = 0;

                // 3. Agregar Materiales
                foreach (($cData['materiales'] ?? []) as $mData) {
                    $idMat = $mData['id_material'] ?? null;
                    if (empty($idMat) && !empty($mData['nombre_nuevo'])) {
                        $m = Material::firstOrCreate(
                            ['nombre' => trim($mData['nombre_nuevo'])],
                            [
                                'id_unidad_medida' => $mData['id_unidad_medida'] ?? null,
                                'precio_x_unidad'  => $mData['precio_unitario'] ?? 0,
                            ]
                        );
                        $idMat = $m->id;
                    }
                    if (empty($idMat)) continue;

                    $sub = round(($mData['cantidad'] ?? 0) * ($mData['precio_unitario'] ?? 0), 4);
                    $iva = round($sub * (($mData['porcentaje_iva'] ?? 16) / 100), 4);
                    
                    AsignaMaterial::create([
                        'id_obra_concepto' => $obraConcepto->id,
                        'id_material'      => $idMat,
                        'cantidad'         => $mData['cantidad'] ?? 0,
                        'precio_unitario'  => $mData['precio_unitario'] ?? 0,
                        'subtotal'         => $sub,
                        'porcentaje_iva'   => $mData['porcentaje_iva'] ?? 16,
                        'iva'              => $iva,
                        'total_final'      => $sub + $iva,
                    ]);
                    $totalSubtotalConcepto += $sub;
                }

                // 4. Agregar Maquinaria
                foreach (($cData['maquinaria'] ?? []) as $mData) {
                    $idMaq = $mData['id_maquinaria'] ?? null;
                    if (empty($idMaq) && !empty($mData['nombre_nuevo'])) {
                        $maq = Maquinaria::firstOrCreate(
                            ['nombre' => trim($mData['nombre_nuevo'])],
                            [
                                'id_unidad_medida' => $mData['id_unidad_medida'] ?? null,
                                'precio_x_unidad'  => $mData['precio_unitario'] ?? 0,
                            ]
                        );
                        $idMaq = $maq->id;
                    }
                    if (empty($idMaq)) continue;

                    $sub = round(($mData['cantidad'] ?? 0) * ($mData['precio_unitario'] ?? 0), 4);
                    $iva = round($sub * (($mData['porcentaje_iva'] ?? 16) / 100), 4);
                    
                    AsignaMaquinaria::create([
                        'id_obra_concepto' => $obraConcepto->id,
                        'id_maquinaria'    => $idMaq,
                        'cantidad'         => $mData['cantidad'] ?? 0,
                        'precio_unitario'  => $mData['precio_unitario'] ?? 0,
                        'subtotal'         => $sub,
                        'porcentaje_iva'   => $mData['porcentaje_iva'] ?? 16,
                        'iva'              => $iva,
                        'total_final'      => $sub + $iva,
                    ]);
                    $totalSubtotalConcepto += $sub;
                }

                // 5. Agregar Mano de Obra
                foreach (($cData['mano_obra'] ?? []) as $mData) {
                    $idMo = $mData['id_mano_obra'] ?? null;
                    if (empty($idMo) && !empty($mData['nombre_nuevo'])) {
                        $mo = ManoObra::firstOrCreate(
                            ['nombre' => trim($mData['nombre_nuevo'])],
                            [
                                'id_unidad_medida' => $mData['id_unidad_medida'] ?? null,
                                'precio_x_unidad'  => $mData['precio_unitario'] ?? 0,
                            ]
                        );
                        $idMo = $mo->id;
                    }
                    if (empty($idMo)) continue;

                    $sub = round(($mData['cantidad'] ?? 0) * ($mData['precio_unitario'] ?? 0), 4);
                    $iva = round($sub * (($mData['porcentaje_iva'] ?? 16) / 100), 4);
                    
                    AsignaManoObra::create([
                        'id_obra_concepto' => $obraConcepto->id,
                        'id_mano_obra'     => $idMo,
                        'cantidad'         => $mData['cantidad'] ?? 0,
                        'precio_unitario'  => $mData['precio_unitario'] ?? 0,
                        'subtotal'         => $sub,
                        'porcentaje_iva'   => $mData['porcentaje_iva'] ?? 16,
                        'iva'              => $iva,
                        'total_final'      => $sub + $iva,
                    ]);
                    $totalSubtotalConcepto += $sub;
                }

                // 6. Actualizar Totales del ObraConcepto si se le asignaron hijos
                // Si no hay hijos, mantenemos el P.U. manual que escribió el usuario.
                // Si hay hijos, el subtotal del Concepto es el Costo Unitario de la matriz * la cantidad del concepto.
                if ($totalSubtotalConcepto > 0) {
                    $nuevoPU = $totalSubtotalConcepto;
                    $subtotalFinalConcepto = round($nuevoPU * $cantConcepto, 4);
                    $ivaConcepto = round($subtotalFinalConcepto * ($obraConcepto->porcentaje_iva / 100), 4);

                    $obraConcepto->update([
                        'precio_unitario' => $nuevoPU,
                        'subtotal'        => $subtotalFinalConcepto,
                        'iva'             => $ivaConcepto,
                        'total_final'     => $subtotalFinalConcepto + $ivaConcepto,
                    ]);
                } else {
                    $sub = round($cantConcepto * $obraConcepto->precio_unitario, 4);
                    $iva = round($sub * ($obraConcepto->porcentaje_iva / 100), 4);
                    $obraConcepto->update([
                        'subtotal'    => $sub,
                        'iva'         => $iva,
                        'total_final' => $sub + $iva,
                    ]);
                }
            }

            $this->recalcularTotalesBloque($obraId);
            ObraController::recalcularTotales($obraId);
            DB::commit();
            return response()->json(['success' => true, 'redirect' => route('obras.presupuesto', $obraId)]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Error storeUnificado: ' . $e->getMessage() . ' - L: ' . $e->getLine());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // UPDATE ALL — Guardar edición en línea del presupuesto
    // ──────────────────────────────────────────────────────────────
    public function updateAll(Request $request, $obraId)
    {
        // Se puede implementar edición rápida más adelante
        return back()->with('success', 'Edición rápida en desarrollo para el nuevo modelo jerárquico.');
    }

    public function destroyConcepto($obraId, $id)
    {
        ObraConcepto::where('id_obra', $obraId)->findOrFail($id)->delete();
        $this->recalcularTotalesBloque($obraId);
        ObraController::recalcularTotales($obraId);
        return back()->with('success', 'Concepto y sus insumos eliminados.');
    }

    public function destroyMaterial($obraId, $id) { /* Implementar si se borra un insumo específico */ }
    public function destroyMaquinaria($obraId, $id) { /* Implementar si se borra un insumo específico */ }
    public function destroyManoObra($obraId, $id) { /* Implementar si se borra un insumo específico */ }

    // ──────────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────────
    private function recalcularTotalesBloque(int $obraId): void
    {
        // El total del bloque ahora se calcula sumando solo los subtotales de ObraConcepto, 
        // ya que los insumos están dentro de los Conceptos y sus costos se suman hacia arriba.
        $bloques = Bloque::all();
        foreach ($bloques as $bloque) {
            $sub = ObraConcepto::where('id_obra', $obraId)->where('id_bloque', $bloque->id)->sum('subtotal');
            $iva = ObraConcepto::where('id_obra', $obraId)->where('id_bloque', $bloque->id)->sum('iva');
            
            if ($sub > 0 || $iva > 0) {
                \App\Models\TotalBloque::updateOrCreate(
                    ['id_obra' => $obraId, 'id_bloque' => $bloque->id],
                    ['total' => $sub, 'iva' => $iva, 'total_final' => $sub + $iva]
                );
            } else {
                \App\Models\TotalBloque::where('id_obra', $obraId)->where('id_bloque', $bloque->id)->delete();
            }
        }
    }

    // ──────────────────────────────────────────────────────────────
    // EXPORTACIÓN
    // ──────────────────────────────────────────────────────────────
    public function exportExcel($obraId)
    {
        $obra = ObraIniciada::with(['datosDeObra', 'niveles'])->findOrFail($obraId);
        $fileName = 'Presupuesto_' . str_replace(' ', '_', $obra->datosDeObra?->nombre ?? 'Obra') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PresupuestoExport($obraId), 
            $fileName
        );
    }

    public function exportPdf($obraId)
    {
        $obra = ObraIniciada::with(['datosDeObra', 'niveles', 'obraConceptos.concepto.unidadMedida', 'totalBloque'])->findOrFail($obraId);
        $bloques = \App\Models\Bloque::orderBy('id')->get();
        $totalesPorBloque = $obra->totalBloque->keyBy('id_bloque');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('obras.presupuesto_export', compact('obra', 'bloques', 'totalesPorBloque'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Presupuesto_' . str_replace(' ', '_', $obra->datosDeObra?->nombre ?? 'Obra') . '.pdf');
    }
}