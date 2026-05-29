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
            'datosDeObra.direccion', 'encargado.persona', 'niveles', 'totalObra',
            'cliente.direccionFiscal',
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
            // El front envía JSON puro; lo parsemos correctamente
            $data = $request->json()->all();
            $conceptosList = $data['conceptos'] ?? [];
            foreach ($conceptosList as $cData) {
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
                // CREAR BLOQUE SI ES NUEVO
                $idBloque = $cData['id_bloque'] ?? null;
                if (empty($idBloque) && !empty($cData['bloque_nuevo'])) {
                    $b = Bloque::firstOrCreate(['descripcion' => trim($cData['bloque_nuevo'])]);
                    $idBloque = $b->id;
                }

                // CREAR AREA SI ES NUEVA
                $idArea = $cData['id_area'] ?? null;
                if (empty($idArea) && !empty($cData['area_nuevo'])) {
                    $a = Area::firstOrCreate([
                        'abreviatura' => trim($cData['area_nuevo']),
                        'descripcion' => trim($cData['area_nuevo'])
                    ]);
                    $idArea = $a->id;
                }

                $obraConcepto = ObraConcepto::create([
                    'id_obra'         => $obraId,
                    'id_nivel'        => $cData['id_nivel'] ?? null,
                    'id_bloque'       => $idBloque,
                    'id_area'         => $idArea,
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
        $concepto = ObraConcepto::where('id_obra', $obraId)->findOrFail($id);
        $concepto->delete();
        $this->recalcularTotalesBloque($obraId);
        ObraController::recalcularTotales($obraId);
        return back()->with('success', 'Renglón eliminado correctamente.');
    }

    // ──────────────────────────────────────────────────────────────
    // GET datos completos de un ObraConcepto para el panel de edición
    // ──────────────────────────────────────────────────────────────
    public function editConcepto($id)
    {
        $oc = ObraConcepto::with([
            'concepto.unidadMedida',
            'materiales.material.unidadMedida',
            'maquinaria.maquinaria.unidadMedida',
            'manoObra.manoObra.unidadMedida',
        ])->findOrFail($id);

        return response()->json([
            'id'               => $oc->id,
            'id_concepto'      => $oc->id_concepto,
            'descripcion'      => $oc->concepto?->descripcion ?? '',
            'cantidad'         => $oc->cantidad,
            'precio_unitario'  => $oc->precio_unitario,
            'porcentaje_iva'   => $oc->porcentaje_iva ?? 16,
            'subtotal'         => $oc->subtotal,
            'total_final'      => $oc->total_final,
            'materiales' => $oc->materiales->map(fn($m) => [
                'id'          => $m->id,
                'id_material' => $m->id_material,
                'nombre'      => $m->material?->nombre ?? '',
                'cantidad'    => $m->cantidad,
                'precio_unitario' => $m->precio_unitario,
                'id_unidad_medida'=> $m->id_unidad_medida,
                'uni_txt'     => $m->material?->unidadMedida?->abreviatura ?? '',
            ])->values(),
            'maquinaria' => $oc->maquinaria->map(fn($m) => [
                'id'             => $m->id,
                'id_maquinaria'  => $m->id_maquinaria,
                'nombre'         => $m->maquinaria?->nombre ?? '',
                'cantidad'       => $m->cantidad,
                'precio_unitario'=> $m->precio_unitario,
                'id_unidad_medida'=> $m->id_unidad_medida,
                'uni_txt'        => $m->maquinaria?->unidadMedida?->abreviatura ?? '',
            ])->values(),
            'mano_obra' => $oc->manoObra->map(fn($m) => [
                'id'          => $m->id,
                'id_mano_obra'=> $m->id_mano_obra,
                'nombre'      => $m->manoObra?->nombre ?? '',
                'cantidad'    => $m->cantidad,
                'precio_unitario'=> $m->precio_unitario,
                'id_unidad_medida'=> $m->id_unidad_medida,
                'uni_txt'     => $m->manoObra?->unidadMedida?->abreviatura ?? '',
            ])->values(),
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // PUT — Guardar edición completa de un ObraConcepto
    // ──────────────────────────────────────────────────────────────
    public function updateConcepto(Request $request, $id)
    {
        $oc = ObraConcepto::findOrFail($id);
        $data = $request->json()->all();

        DB::beginTransaction();
        try {
            // 1. Actualizar o crear concepto si vino nombre nuevo
            $idConcepto = $data['id_concepto'] ?? $oc->id_concepto;
            if (empty($idConcepto) && !empty($data['nombre_nuevo'])) {
                $c = Concepto::firstOrCreate(
                    ['descripcion' => trim($data['nombre_nuevo'])],
                    ['p_u' => $data['precio_unitario'] ?? 0]
                );
                $idConcepto = $c->id;
            }

            // 2. Calcular totales del concepto
            $cant  = floatval($data['cantidad']       ?? $oc->cantidad);
            $pu    = floatval($data['precio_unitario'] ?? $oc->precio_unitario);
            $pIva  = floatval($data['porcentaje_iva']  ?? $oc->porcentaje_iva ?? 16);
            $sub   = round($cant * $pu, 4);
            $iva   = round($sub * ($pIva / 100), 4);
            $total = $sub + $iva;

            $oc->update([
                'id_concepto'     => $idConcepto,
                'cantidad'        => $cant,
                'precio_unitario' => $pu,
                'porcentaje_iva'  => $pIva,
                'subtotal'        => $sub,
                'iva'             => $iva,
                'total_final'     => $total,
            ]);

            // 3. Reemplazar materiales
            $oc->materiales()->delete();
            foreach ($data['materiales'] ?? [] as $mData) {
                $idMat = $mData['id_material'] ?? null;
                if (empty($idMat) && !empty($mData['nombre_nuevo'])) {
                    $m = Material::firstOrCreate(
                        ['nombre' => trim($mData['nombre_nuevo'])],
                        ['precio_x_unidad' => $mData['precio_unitario'] ?? 0, 'id_unidad_medida' => $mData['id_unidad_medida'] ?? null]
                    );
                    $idMat = $m->id;
                }
                if (empty($idMat)) continue;
                $s2 = round(($mData['cantidad'] ?? 0) * ($mData['precio_unitario'] ?? 0), 4);
                AsignaMaterial::create([
                    'id_obra_concepto' => $oc->id,
                    'id_material'      => $idMat,
                    'cantidad'         => $mData['cantidad'] ?? 0,
                    'precio_unitario'  => $mData['precio_unitario'] ?? 0,
                    'subtotal'         => $s2,
                    'porcentaje_iva'   => 0,
                    'iva'              => 0,
                    'total_final'      => $s2,
                ]);
            }

            // 4. Reemplazar maquinaria
            $oc->maquinaria()->delete();
            foreach ($data['maquinaria'] ?? [] as $mData) {
                $idMaq = $mData['id_maquinaria'] ?? null;
                if (empty($idMaq) && !empty($mData['nombre_nuevo'])) {
                    $m = Maquinaria::firstOrCreate(
                        ['nombre' => trim($mData['nombre_nuevo'])],
                        ['precio_x_unidad' => $mData['precio_unitario'] ?? 0, 'id_unidad_medida' => $mData['id_unidad_medida'] ?? null]
                    );
                    $idMaq = $m->id;
                }
                if (empty($idMaq)) continue;
                $s2 = round(($mData['cantidad'] ?? 0) * ($mData['precio_unitario'] ?? 0), 4);
                AsignaMaquinaria::create([
                    'id_obra_concepto' => $oc->id,
                    'id_maquinaria'    => $idMaq,
                    'cantidad'         => $mData['cantidad'] ?? 0,
                    'precio_unitario'  => $mData['precio_unitario'] ?? 0,
                    'subtotal'         => $s2,
                    'porcentaje_iva'   => 0,
                    'iva'              => 0,
                    'total_final'      => $s2,
                ]);
            }

            // 5. Reemplazar mano de obra
            $oc->manoObra()->delete();
            foreach ($data['mano_obra'] ?? [] as $mData) {
                $idMo = $mData['id_mano_obra'] ?? null;
                if (empty($idMo) && !empty($mData['nombre_nuevo'])) {
                    $m = ManoObra::firstOrCreate(
                        ['nombre' => trim($mData['nombre_nuevo'])],
                        ['precio_x_unidad' => $mData['precio_unitario'] ?? 0, 'id_unidad_medida' => $mData['id_unidad_medida'] ?? null]
                    );
                    $idMo = $m->id;
                }
                if (empty($idMo)) continue;
                $s2 = round(($mData['cantidad'] ?? 0) * ($mData['precio_unitario'] ?? 0), 4);
                AsignaManoObra::create([
                    'id_obra_concepto' => $oc->id,
                    'id_mano_obra'     => $idMo,
                    'cantidad'         => $mData['cantidad'] ?? 0,
                    'precio_unitario'  => $mData['precio_unitario'] ?? 0,
                    'subtotal'         => $s2,
                    'porcentaje_iva'   => 0,
                    'iva'              => 0,
                    'total_final'      => $s2,
                ]);
            }

            $this->recalcularTotalesBloque($oc->id_obra);
            ObraController::recalcularTotales($oc->id_obra);

            DB::commit();
            return response()->json(['success' => true, 'subtotal' => $sub, 'iva' => $iva, 'total_final' => $total]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('updateConcepto error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyMaterial($obraId, $id) { /* futuro */ }
    public function destroyMaquinaria($obraId, $id) { /* futuro */ }
    public function destroyManoObra($obraId, $id) { /* futuro */ }

    /** POST /api/bloques/rapida — crea un bloque desde el formulario de presupuesto */
    public function storeBloqueRapido(Request $request)
    {
        $request->validate(['descripcion' => 'required|string|max:255']);
        $bloque = Bloque::firstOrCreate(['descripcion' => trim($request->descripcion)]);
        return response()->json(['id' => $bloque->id, 'descripcion' => $bloque->descripcion, 'texto' => $bloque->descripcion]);
    }

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

    // ──────────────────────────────────────────────────────────────
    // PDF 1: Presupuesto Formal (tipo carta/cotización)
    // ──────────────────────────────────────────────────────────────
    public function generarPresupuestoPdf($obraId)
    {
        $obra = ObraIniciada::with([
            'datosDeObra.direccion',
            'cliente.direccionFiscal',
            'obraConceptos.concepto.unidadMedida',
            'totalBloque',
        ])->findOrFail($obraId);

        $bloques          = \App\Models\Bloque::orderBy('id')->get();
        $totalesPorBloque = $obra->totalBloque->keyBy('id_bloque');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'pdf.presupuesto',
            compact('obra', 'bloques', 'totalesPorBloque')
        );
        $pdf->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'Presupuesto_' . str_replace(' ', '_', $obra->datosDeObra?->nombre ?? 'Obra') . '.pdf';
        return $pdf->download($nombreArchivo);
    }

    // ──────────────────────────────────────────────────────────────
    // PDF 2: Catálogo de Conceptos (técnico, tabular, horizontal)
    // ──────────────────────────────────────────────────────────────
    public function generarCatalogoConceptosPdf($obraId)
    {
        $obra = ObraIniciada::with([
            'datosDeObra.direccion',
            'cliente.direccionFiscal',
            'obraConceptos.concepto.unidadMedida',
            'obraConceptos.area',
            'obraConceptos.materiales.material.unidadMedida',
            'obraConceptos.maquinaria.maquinaria.unidadMedida',
            'obraConceptos.manoObra.manoObra.unidadMedida',
            'totalBloque',
        ])->findOrFail($obraId);

        $bloques          = \App\Models\Bloque::orderBy('id')->get();
        $totalesPorBloque = $obra->totalBloque->keyBy('id_bloque');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'pdf.catalogo-conceptos',
            compact('obra', 'bloques', 'totalesPorBloque')
        );
        $pdf->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
        $pdf->setPaper('letter', 'landscape');

        $nombreArchivo = 'Catalogo_Conceptos_' . str_replace(' ', '_', $obra->datosDeObra?->nombre ?? 'Obra') . '.pdf';
        return $pdf->download($nombreArchivo);
    }
    // =======================================================
    // 5. APROBAR PRESUPUESTO -> PASAR A OBRA EN PROCESO
    // =======================================================
    public function aprobar(Request $request, $obraId)
    {
        $obra = \App\Models\ObraIniciada::findOrFail($obraId);
        
        if (\App\Models\ObraProceso::where('id_obra', $obraId)->exists()) {
            return redirect()->back()->with('error', 'El presupuesto ya fue aprobado.');
        }

        $conIva = $request->input('con_iva') ? true : false;
        
        $totalPresupuesto = $obra->total_presupuestado ?? 0;
        
        // Al aprobar, la fecha de inicio es HOY
        $obra->fecha_inicio = now();
        $obra->save();
        
        $duracion = (int) ($obra->duracion ?? 30);
        $estimacion = $obra->fecha_inicio->copy()->addDays($duracion);

        \App\Models\ObraProceso::create([
            'id_obra' => $obraId,
            'estado' => 'en_curso',
            'con_iva' => $conIva,
            'dias_transcurridos' => 0,
            'porcentaje_avanzado' => 0,
            'presupuesto_cubierto' => 0,
            'presupuesto_restante' => $totalPresupuesto,
            'porcentaje_restante' => 100,
            'estimacion_de_entrega' => $estimacion,
            'nivel_actual' => 'Inicio',
        ]);
        return redirect()->route('obras_proceso.fechas', $obraId)->with('success', 'Presupuesto aprobado. Por favor confirma la fecha de inicio y los días inhábiles.');
    }

    // =======================================================
    // 6. CANCELAR APROBACIÓN DE PRESUPUESTO
    // =======================================================
    public function cancelarAprobacion(Request $request, $obraId)
    {
        $obra = \App\Models\ObraIniciada::findOrFail($obraId);
        
        $proceso = \App\Models\ObraProceso::where('id_obra', $obraId)->first();
        if ($proceso) {
            $proceso->delete();
            return redirect()->route('obras.index')->with('success', 'Aprobación del presupuesto cancelada.');
        }

        return redirect()->route('obras.index')->with('error', 'El presupuesto no estaba aprobado.');
    }
}