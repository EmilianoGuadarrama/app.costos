<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObraIniciada;
use App\Models\EgresoTotal;
use App\Models\Material;
use App\Models\Area;
use App\Models\Persona;

class ObraMaterialController extends Controller
{
    public function index($id_obra)
    {
        $obra = ObraIniciada::with([
            'datosDeObra', 
            'obraConceptos.materiales.material.unidadMedida',
            'preProveedores.proveedor',
        ])->findOrFail($id_obra);

        // Fetch material purchases for this obra
        $egresosMateriales = EgresoTotal::with('material.unidadMedida', 'area', 'preProveedor.proveedor')
            ->where('id_obra', $obra->id)
            ->whereNotNull('id_material')
            ->orderBy('fecha', 'desc')
            ->get();
            
        $compradas = [];
        foreach($egresosMateriales as $eg) {
            $matId = $eg->id_material;
            if(!isset($compradas[$matId])) {
                $compradas[$matId] = ['cant' => 0, 'gastado' => 0];
            }
            $compradas[$matId]['cant'] += $eg->cantidad_material;
            $compradas[$matId]['gastado'] += $eg->monto_material;
        }

        // Compute budgeted materials grouped
        $materialesPorNivelArea = [];
        foreach ($obra->obraConceptos as $oc) {
            if ($oc->materiales->isEmpty()) continue;
            
            $nivelId = $oc->id_nivel ?: 0;
            $areaId = $oc->id_area ?: 0;

            if (!isset($materialesPorNivelArea[$nivelId])) {
                $materialesPorNivelArea[$nivelId] = [
                    'nombre' => $oc->nivel ? $oc->nivel->descripcion : 'GENERAL / SIN NIVEL',
                    'areas' => []
                ];
            }
            if (!isset($materialesPorNivelArea[$nivelId]['areas'][$areaId])) {
                $materialesPorNivelArea[$nivelId]['areas'][$areaId] = [
                    'nombre' => $oc->area ? $oc->area->descripcion : 'Sin Área',
                    'materiales' => []
                ];
            }

            foreach ($oc->materiales as $mat) {
                if (!$mat->material) continue;
                $matId = $mat->id_material;
                if (!isset($materialesPorNivelArea[$nivelId]['areas'][$areaId]['materiales'][$matId])) {
                    $materialesPorNivelArea[$nivelId]['areas'][$areaId]['materiales'][$matId] = [
                        'material' => $mat->material,
                        'cantidad_total' => 0,
                        'costo_total' => 0,
                        'cantidad_comprada' => 0,
                        'gastado' => 0,
                    ];
                }
                $cant_req = $mat->cantidad * $oc->cantidad;
                $costo    = $mat->precio_unitario * $cant_req;

                $materialesPorNivelArea[$nivelId]['areas'][$areaId]['materiales'][$matId]['cantidad_total'] += $cant_req;
                $materialesPorNivelArea[$nivelId]['areas'][$areaId]['materiales'][$matId]['costo_total']    += $costo;
            }
        }

        // Allocate purchases
        foreach ($materialesPorNivelArea as &$nivel) {
            foreach ($nivel['areas'] as &$area) {
                foreach ($area['materiales'] as $matId => &$matData) {
                    if (isset($compradas[$matId]) && $compradas[$matId]['cant'] > 0) {
                        $alloc = min($matData['cantidad_total'], $compradas[$matId]['cant']);
                        
                        if ($compradas[$matId]['cant'] > 0) {
                            $pricePerUnit = $compradas[$matId]['gastado'] / $compradas[$matId]['cant'];
                            $allocMoney = $alloc * $pricePerUnit;
                        } else {
                            $allocMoney = 0;
                        }

                        $matData['cantidad_comprada'] += $alloc;
                        $matData['gastado'] += $allocMoney;

                        $compradas[$matId]['cant'] -= $alloc;
                        $compradas[$matId]['gastado'] -= $allocMoney;
                    }
                }
            }
        }

        // Extras
        $extras = [];
        foreach ($compradas as $matId => $leftover) {
            if ($leftover['cant'] > 0.001) {
                $material = \App\Models\Material::with('unidadMedida')->find($matId);
                if ($material) {
                    $extras[$matId] = [
                        'material' => $material,
                        'cantidad_total' => 0,
                        'costo_total' => 0,
                        'cantidad_comprada' => $leftover['cant'],
                        'gastado' => $leftover['gastado'],
                    ];
                }
            }
        }
        
        if (!empty($extras)) {
            $materialesPorNivelArea[-1] = [
                'nombre' => 'COMPRAS EXTRAS / FUERA DE PRESUPUESTO',
                'areas' => [
                    -1 => [
                        'nombre' => 'Materiales Adicionales',
                        'materiales' => $extras
                    ]
                ]
            ];
        }
        
        // Separate pending and completed materials
        $materialesPendientes = [];
        $materialesCompletados = [];

        foreach ($materialesPorNivelArea as $nivelId => $nivel) {
            foreach ($nivel['areas'] as $areaId => $area) {
                foreach ($area['materiales'] as $matId => $matData) {
                    $faltante = max(0, $matData['cantidad_total'] - $matData['cantidad_comprada']);
                    
                    if ($faltante > 0) {
                        if (!isset($materialesPendientes[$nivelId])) {
                            $materialesPendientes[$nivelId] = ['nombre' => $nivel['nombre'], 'areas' => []];
                        }
                        if (!isset($materialesPendientes[$nivelId]['areas'][$areaId])) {
                            $materialesPendientes[$nivelId]['areas'][$areaId] = ['nombre' => $area['nombre'], 'materiales' => []];
                        }
                        $materialesPendientes[$nivelId]['areas'][$areaId]['materiales'][$matId] = $matData;
                    } else {
                        if (!isset($materialesCompletados[$nivelId])) {
                            $materialesCompletados[$nivelId] = ['nombre' => $nivel['nombre'], 'areas' => []];
                        }
                        if (!isset($materialesCompletados[$nivelId]['areas'][$areaId])) {
                            $materialesCompletados[$nivelId]['areas'][$areaId] = ['nombre' => $area['nombre'], 'materiales' => []];
                        }
                        $materialesCompletados[$nivelId]['areas'][$areaId]['materiales'][$matId] = $matData;
                    }
                }
            }
        }

        $areas = Area::orderBy('abreviatura')->get();
        $personas = Persona::orderBy('nombre')->get();
        
        // Proveedores con presupuesto en esta obra
        $proveedoresAprobados = $obra->preProveedores->where('estado', 'aprobado');

        return view('obras_materiales.index', compact('obra', 'materialesPendientes', 'materialesCompletados', 'egresosMateriales', 'areas', 'personas', 'proveedoresAprobados'));
    }

    public function storeCompra(Request $request, $id_obra)
    {
        $obra = ObraIniciada::findOrFail($id_obra);

        $request->validate([
            'id_material'       => 'required', 
            'cantidad_material' => 'required|numeric|min:0.01',
            'pago'              => 'required|numeric|min:0',
            'fecha'             => 'required|date',
            'id_pre_proveedor'  => 'nullable|exists:pre_proveedores,id',
            'id_persona'        => 'required_without:id_pre_proveedor|nullable|exists:personas,id',
        ]);

        $es_por_proveedor = !empty($request->id_pre_proveedor);
        $pago_financiero = $es_por_proveedor ? 0 : $request->pago;
        
        $idPersona = $request->id_persona;
        if ($es_por_proveedor) {
            $provObj = \App\Models\PreProveedor::with('proveedor')->find($request->id_pre_proveedor);
            $idPersona = $provObj->proveedor->id_persona ?? null;
        }

        $materialObj = Material::findOrFail($request->id_material);
        $concepto_compra = 'Compra de material: ' . $materialObj->nombre;

        EgresoTotal::create([
            'id_obra'           => $obra->id,
            'id_persona'        => $idPersona,
            'id_material'       => $request->id_material,
            'cantidad_material' => $request->cantidad_material,
            'pago'              => $pago_financiero,
            'monto_material'    => $request->pago,
            'fecha'             => $request->fecha,
            'id_pre_proveedor'  => $request->id_pre_proveedor,
            'categoria'         => 'Materiales',
            'concepto'          => $concepto_compra,
        ]);

        // Redirect back to the previous page so it works from both views
        return redirect()->back()->with('success', 'Compra de material registrada correctamente.');
    }

    public function destroyCompra($id_egreso)
    {
        $egreso = EgresoTotal::findOrFail($id_egreso);
        $id_obra = $egreso->id_obra;
        $egreso->delete();

        return redirect()->back()->with('success', 'Registro de compra eliminado.');
    }

    public function apiPendientes($id_obra)
    {
        $obra = ObraIniciada::with([
            'obraConceptos.materiales.material.unidadMedida',
        ])->find($id_obra);

        if (!$obra) {
            return response()->json(['success' => false, 'message' => 'Obra no encontrada']);
        }

        $egresosMateriales = EgresoTotal::where('id_obra', $obra->id)
            ->whereNotNull('id_material')
            ->get();
            
        $compradas = [];
        foreach($egresosMateriales as $eg) {
            $matId = $eg->id_material;
            if(!isset($compradas[$matId])) {
                $compradas[$matId] = 0;
            }
            $compradas[$matId] += $eg->cantidad_material;
        }

        $materiales = [];
        foreach ($obra->obraConceptos as $oc) {
            if ($oc->materiales->isEmpty()) continue;
            foreach ($oc->materiales as $mat) {
                if (!$mat->material) continue;
                $matId = $mat->id_material;
                if (!isset($materiales[$matId])) {
                    $materiales[$matId] = [
                        'id_material' => $matId,
                        'nombre' => $mat->material->nombre . ($mat->material->marca ? ' ('.$mat->material->marca.')' : ''),
                        'unidad' => $mat->material->unidadMedida?->abreviatura ?? '',
                        'cantidad_total' => 0,
                    ];
                }
                $materiales[$matId]['cantidad_total'] += ($mat->cantidad * $oc->cantidad);
            }
        }
        
        $pendientes = [];
        foreach($materiales as $matId => $data) {
            $comprada = $compradas[$matId] ?? 0;
            $faltante = max(0, $data['cantidad_total'] - $comprada);
            if ($faltante > 0) {
                $data['faltante'] = round($faltante, 2);
                $pendientes[] = $data;
            }
        }
        
        // Sort alphabetically
        usort($pendientes, function($a, $b) {
            return strcmp($a['nombre'], $b['nombre']);
        });

        return response()->json(['success' => true, 'materiales' => $pendientes]);
    }
}
