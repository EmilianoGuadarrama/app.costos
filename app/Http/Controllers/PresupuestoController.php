<?php
namespace App\Http\Controllers;

use App\Models\ObraIniciada;
use App\Models\AsignaConcepto;
use App\Models\AsignaMaterial;
use App\Models\AsignaMaquinaria;
use App\Models\Concepto;
use App\Models\Material;
use App\Models\Maquinaria;
use App\Models\Bloque;
use App\Models\Nivel;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresupuestoController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // SHOW — Vista del presupuesto completo por bloques
    // ──────────────────────────────────────────────────────────────
    public function show($obraId)
    {
        $obra = ObraIniciada::with([
            'datosDeObra', 'encargado.persona', 'niveles', 'totalObra',
        ])->findOrFail($obraId);

        $conceptos  = AsignaConcepto::with(['bloque','area','concepto.unidadMedida','concepto.composicion','nivel'])
            ->where('id_obra', $obraId)->get();
        $materiales = AsignaMaterial::with(['bloque','area','material.unidadMedida','nivel'])
            ->where('id_obra', $obraId)->get();
        $maquinaria = AsignaMaquinaria::with(['bloque','area','maquinaria.unidadMedida','nivel'])
            ->where('id_obra', $obraId)->get();

        $totalesPorBloque = \App\Models\TotalBloque::with('bloque')
            ->where('id_obra', $obraId)->get()->keyBy('id_bloque');

        $bloques = Bloque::orderBy('id')->get();  // Para el orden correcto en vista

        return view('obras.presupuesto', compact(
            'obra','conceptos','materiales','maquinaria','totalesPorBloque','bloques'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    // CONCEPTOS — Formulario + Store + Destroy
    // ──────────────────────────────────────────────────────────────
    public function create($obraId)
    {
        $obra      = ObraIniciada::with(['datosDeObra','niveles'])->findOrFail($obraId);
        $bloques   = Bloque::orderBy('id')->get();
        $areas     = Area::orderBy('abreviatura')->get();
        $conceptos = Concepto::with('area', 'unidadMedida')->orderBy('descripcion')->get();

        return view('obras.presupuesto_form', compact('obra','bloques','areas','conceptos'));
    }

    public function storeConceptos(Request $request, $obraId)
    {
        ObraIniciada::findOrFail($obraId);

        $request->validate([
            'filas'                   => 'required|array|min:1',
            'filas.*.id_bloque'       => 'required|exists:bloques,id',
            'filas.*.id_area'         => 'required|exists:areas,id',
            'filas.*.id_nivel'        => 'nullable|exists:niveles,id',
            'filas.*.cantidad'        => 'required|numeric|min:0',
            'filas.*.precio_unitario' => 'required|numeric|min:0',
            'filas.*.porcentaje_iva'  => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->filas as $fila) {
                // Si viene un concepto nuevo escrito a mano, crearlo primero
                $idConcepto = $fila['id_concepto'] ?? null;

                if (empty($idConcepto) && !empty($fila['descripcion_nueva'])) {
                    $concepto = \App\Models\Concepto::firstOrCreate(
                        ['descripcion' => trim($fila['descripcion_nueva'])],
                        [
                            'id_area'     => $fila['id_area'],
                            'p_u'         => $fila['precio_unitario'],
                        ]
                    );
                    $idConcepto = $concepto->id;
                }

                if (empty($idConcepto)) continue; // fila vacía, saltar

                $sub = round($fila['cantidad'] * $fila['precio_unitario'], 4);
                $iva = round($sub * ($fila['porcentaje_iva'] / 100), 4);
                AsignaConcepto::create([
                    'id_obra'         => $obraId,
                    'id_nivel'        => $fila['id_nivel'] ?: null,
                    'id_bloque'       => $fila['id_bloque'],
                    'id_area'         => $fila['id_area'],
                    'id_concepto'     => $idConcepto,
                    'cantidad'        => $fila['cantidad'],
                    'precio_unitario' => $fila['precio_unitario'],
                    'subtotal'        => $sub,
                    'porcentaje_iva'  => $fila['porcentaje_iva'],
                    'iva'             => $iva,
                    'total_final'     => $sub + $iva,
                ]);
            }
            $this->recalcularTotalesBloque($obraId);
            ObraController::recalcularTotales($obraId);
            DB::commit();
            return redirect()->route('obras.presupuesto', $obraId)
                ->with('success', 'Conceptos agregados al presupuesto.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function destroyConcepto($obraId, $id)
    {
        AsignaConcepto::where('id_obra', $obraId)->findOrFail($id)->delete();
        $this->recalcularTotalesBloque($obraId);
        ObraController::recalcularTotales($obraId);
        return back()->with('success', 'Renglón eliminado.');
    }

    // ──────────────────────────────────────────────────────────────
    // MATERIALES — Formulario + Store + Destroy
    // ──────────────────────────────────────────────────────────────
    public function createMateriales($obraId)
    {
        $obra       = ObraIniciada::with(['datosDeObra','niveles'])->findOrFail($obraId);
        $bloques    = Bloque::orderBy('id')->get();
        $areas      = Area::orderBy('abreviatura')->get();
        $materiales = Material::with('unidadMedida')->orderBy('nombre')->get();

        return view('obras.presupuesto_materiales_form', compact('obra','bloques','areas','materiales'));
    }

    // ──────────────────────────────────────────────────────────────
    // UNIFICADO — Conceptos + Materiales + Maquinaria en una sola vista
    // ──────────────────────────────────────────────────────────────
    public function createUnificado($obraId)
    {
        $obra       = ObraIniciada::with(['datosDeObra','niveles'])->findOrFail($obraId);
        $bloques    = Bloque::orderBy('id')->get();
        $areas      = Area::orderBy('abreviatura')->get();
        $conceptos  = Concepto::with('area', 'unidadMedida', 'composicion')->orderBy('descripcion')->get();
        $materiales = Material::with('unidadMedida')->orderBy('nombre')->get();
        $maquinaria = Maquinaria::with('unidadMedida')->orderBy('nombre')->get();

        return view('obras.presupuesto_unificado', compact(
            'obra','bloques','areas','conceptos','materiales','maquinaria'
        ));
    }

    public function storeUnificado(Request $request, $obraId)
    {
        ObraIniciada::findOrFail($obraId);

        $request->validate([
            'filas'                   => 'array',
            'filas.*.tipo'            => 'required|in:concepto,material,maquinaria',
            'filas.*.id_bloque'       => 'required|exists:bloques,id',
            'filas.*.id_area'         => 'required|exists:areas,id',
            'filas.*.id_nivel'        => 'nullable|exists:niveles,id',
            'filas.*.cantidad'        => 'required|numeric|min:0',
            'filas.*.precio_unitario' => 'required|numeric|min:0',
            'filas.*.porcentaje_iva'  => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            foreach (($request->filas ?? []) as $fila) {
                $tipo = $fila['tipo'];
                $sub  = round($fila['cantidad'] * $fila['precio_unitario'], 4);
                $iva  = round($sub * ($fila['porcentaje_iva'] / 100), 4);
                $base = [
                    'id_obra'         => $obraId,
                    'id_nivel'        => $fila['id_nivel'] ?: null,
                    'id_bloque'       => $fila['id_bloque'],
                    'id_area'         => $fila['id_area'],
                    'cantidad'        => $fila['cantidad'],
                    'precio_unitario' => $fila['precio_unitario'],
                    'subtotal'        => $sub,
                    'porcentaje_iva'  => $fila['porcentaje_iva'],
                    'iva'             => $iva,
                    'total_final'     => $sub + $iva,
                ];

                if ($tipo === 'concepto') {
                    $idConcepto = $fila['id_concepto'] ?? null;
                    if (empty($idConcepto) && !empty($fila['nombre_nuevo'])) {
                        $c = Concepto::firstOrCreate(
                            ['descripcion' => trim($fila['nombre_nuevo'])],
                            [
                                'id_area'          => !empty($fila['nuevo_id_area']) ? $fila['nuevo_id_area'] : $fila['id_area'],
                                'p_u'              => !empty($fila['nuevo_pu']) ? $fila['nuevo_pu'] : $fila['precio_unitario'],
                                'id_unidad_medida' => $fila['nuevo_id_um'] ?? null,
                            ]
                        );
                        $idConcepto = $c->id;
                    }
                    if (empty($idConcepto)) continue;
                    AsignaConcepto::create($base + ['id_concepto' => $idConcepto]);

                } elseif ($tipo === 'material') {
                    $idMaterial = $fila['id_material'] ?? null;
                    if (empty($idMaterial) && !empty($fila['nombre_nuevo'])) {
                        $m = Material::firstOrCreate(
                            ['nombre' => trim($fila['nombre_nuevo'])],
                            [
                                'descripcion'      => $fila['nuevo_desc'] ?? null,
                                'id_unidad_medida' => $fila['nuevo_id_um'] ?? null,
                                'precio_x_unidad'  => !empty($fila['nuevo_precio']) ? $fila['nuevo_precio'] : $fila['precio_unitario'],
                            ]
                        );
                        $idMaterial = $m->id;
                    }
                    if (empty($idMaterial)) continue;
                    AsignaMaterial::create($base + ['id_material' => $idMaterial]);

                } elseif ($tipo === 'maquinaria') {
                    $idMaq = $fila['id_maquinaria'] ?? null;
                    if (empty($idMaq) && !empty($fila['nombre_nuevo'])) {
                        $maq = Maquinaria::firstOrCreate(
                            ['nombre' => trim($fila['nombre_nuevo'])],
                            [
                                'descripcion'      => $fila['nuevo_desc'] ?? null,
                                'id_unidad_medida' => $fila['nuevo_id_um'] ?? null,
                                'precio_x_unidad'  => !empty($fila['nuevo_precio']) ? $fila['nuevo_precio'] : $fila['precio_unitario'],
                            ]
                        );
                        $idMaq = $maq->id;
                    }
                    if (empty($idMaq)) continue;
                    AsignaMaquinaria::create($base + ['id_maquinaria' => $idMaq]);
                }
            }

            $this->recalcularTotalesBloque($obraId);
            ObraController::recalcularTotales($obraId);
            DB::commit();
            return redirect()->route('obras.presupuesto', $obraId)
                ->with('success', 'Renglones guardados correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function storeMateriales(Request $request, $obraId)
    {
        ObraIniciada::findOrFail($obraId);

        $request->validate([
            'filas'                   => 'required|array|min:1',
            'filas.*.id_material'     => 'required|exists:materiales,id',
            'filas.*.id_bloque'       => 'required|exists:bloques,id',
            'filas.*.id_area'         => 'required|exists:areas,id',
            'filas.*.id_nivel'        => 'nullable|exists:niveles,id',
            'filas.*.cantidad'        => 'required|numeric|min:0',
            'filas.*.precio_unitario' => 'required|numeric|min:0',
            'filas.*.porcentaje_iva'  => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->filas as $fila) {
                $sub = round($fila['cantidad'] * $fila['precio_unitario'], 4);
                $iva = round($sub * ($fila['porcentaje_iva'] / 100), 4);
                AsignaMaterial::create([
                    'id_obra'         => $obraId,
                    'id_nivel'        => $fila['id_nivel'] ?: null,
                    'id_bloque'       => $fila['id_bloque'],
                    'id_area'         => $fila['id_area'],
                    'id_material'     => $fila['id_material'],
                    'cantidad'        => $fila['cantidad'],
                    'precio_unitario' => $fila['precio_unitario'],
                    'subtotal'        => $sub,
                    'porcentaje_iva'  => $fila['porcentaje_iva'],
                    'iva'             => $iva,
                    'total_final'     => $sub + $iva,
                ]);
            }
            $this->recalcularTotalesBloque($obraId);
            ObraController::recalcularTotales($obraId);
            DB::commit();
            return redirect()->route('obras.presupuesto', $obraId)
                ->with('success', count($request->filas) . ' materiales agregados al presupuesto.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function destroyMaterial($obraId, $id)
    {
        AsignaMaterial::where('id_obra', $obraId)->findOrFail($id)->delete();
        $this->recalcularTotalesBloque($obraId);
        ObraController::recalcularTotales($obraId);
        return back()->with('success', 'Material eliminado.');
    }

    // ──────────────────────────────────────────────────────────────
    // UPDATE ALL — Guardar edición en línea del presupuesto
    // ──────────────────────────────────────────────────────────────
    public function updateAll(Request $request, $obraId)
    {
        ObraIniciada::findOrFail($obraId);

        $items = $request->input('items', []);

        DB::beginTransaction();
        try {
            foreach ($items as $tipo => $filas) {
                foreach ($filas as $id => $datos) {
                    $pu = floatval($datos['pu'] ?? 0);
                    $cant = floatval($datos['cantidad'] ?? 0);
                    
                    if ($tipo === 'concepto') {
                        $model = AsignaConcepto::where('id_obra', $obraId)->find($id);
                    } elseif ($tipo === 'material') {
                        $model = AsignaMaterial::where('id_obra', $obraId)->find($id);
                    } elseif ($tipo === 'maquinaria') {
                        $model = AsignaMaquinaria::where('id_obra', $obraId)->find($id);
                    } else {
                        continue;
                    }

                    if ($model) {
                        $porcIva = $model->porcentaje_iva;
                        $sub = round($cant * $pu, 4);
                        $iva = round($sub * ($porcIva / 100), 4);
                        $model->update([
                            'cantidad' => $cant,
                            'precio_unitario' => $pu,
                            'subtotal' => $sub,
                            'iva' => $iva,
                            'total_final' => $sub + $iva,
                        ]);
                    }
                }
            }

            $this->recalcularTotalesBloque($obraId);
            ObraController::recalcularTotales($obraId);
            DB::commit();

            return redirect()->route('obras.presupuesto', $obraId)
                ->with('success', 'Presupuesto actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['general' => $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────────
    private function recalcularTotalesBloque(int $obraId): void
    {
        $bloques = Bloque::all();
        foreach ($bloques as $bloque) {
            $sub = AsignaConcepto::where('id_obra', $obraId)->where('id_bloque', $bloque->id)->sum('subtotal')
                 + AsignaMaterial::where('id_obra', $obraId)->where('id_bloque', $bloque->id)->sum('subtotal')
                 + AsignaMaquinaria::where('id_obra', $obraId)->where('id_bloque', $bloque->id)->sum('subtotal');
            $iva = AsignaConcepto::where('id_obra', $obraId)->where('id_bloque', $bloque->id)->sum('iva')
                 + AsignaMaterial::where('id_obra', $obraId)->where('id_bloque', $bloque->id)->sum('iva')
                 + AsignaMaquinaria::where('id_obra', $obraId)->where('id_bloque', $bloque->id)->sum('iva');
            if ($sub > 0 || $iva > 0) {
                \App\Models\TotalBloque::updateOrCreate(
                    ['id_obra' => $obraId, 'id_bloque' => $bloque->id],
                    ['total' => $sub, 'iva' => $iva, 'total_final' => $sub + $iva]
                );
            }
        }
    }
}