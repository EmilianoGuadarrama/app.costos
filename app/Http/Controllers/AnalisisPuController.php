<?php

namespace App\Http\Controllers;

use App\Models\AnalisisPu;
use App\Models\AnalisisPuMaterial;
use App\Models\AnalisisPuManoObra;
use App\Models\AnalisisPuMaquinaria;
use App\Models\AnalisisPuIndirecto;
use App\Models\Concepto;
use App\Models\Material;
use App\Models\ManoObra;
use App\Models\MaquinariaEquipo;
use App\Models\Indirecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalisisPuController extends Controller
{
    public function index()
    {
        $analisis = AnalisisPu::with(['concepto.area', 'concepto.unidadMedida'])
            ->latest()
            ->get();

        return view('pu.index', compact('analisis'));
    }

    public function create()
    {
        // Solo conceptos que AÚN no tienen APU asignado
        $conceptosUsados = AnalisisPu::pluck('concepto_id');
        $conceptos = Concepto::with('unidadMedida')
            ->whereNotIn('id', $conceptosUsados)
            ->orderBy('clave')
            ->get();

        $materiales  = Material::orderBy('clave')->get();
        $manoObra    = ManoObra::orderBy('clave')->get();
        $maquinaria  = MaquinariaEquipo::orderBy('clave')->get();
        $indirectos  = Indirecto::orderBy('clave')->get();

        return view('pu.create', compact('conceptos', 'materiales', 'manoObra', 'maquinaria', 'indirectos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'concepto_id'                    => 'required|exists:conceptos,id|unique:analisis_pu,concepto_id',
            'observaciones'                  => 'nullable|string|max:500',
            // Materiales
            'materiales.*.material_id'       => 'required|exists:materiales,id',
            'materiales.*.cantidad'          => 'required|numeric|min:0',
            'materiales.*.costo_unitario'    => 'required|numeric|min:0',
            // Mano de obra
            'mano_obra.*.mano_obra_id'       => 'required|exists:mano_obra,id',
            'mano_obra.*.cantidad'           => 'required|numeric|min:0',
            'mano_obra.*.costo_unitario'     => 'required|numeric|min:0',
            // Maquinaria
            'maquinaria.*.maquinaria_equipo_id' => 'required|exists:maquinaria_equipos,id',
            'maquinaria.*.cantidad'          => 'required|numeric|min:0',
            'maquinaria.*.costo_unitario'    => 'required|numeric|min:0',
            // Indirectos
            'indirectos.*.indirecto_id'      => 'required|exists:indirectos,id',
            'indirectos.*.porcentaje_aplicado' => 'required|numeric|min:0|max:100',
        ], [
            'concepto_id.required' => 'El concepto es obligatorio.',
            'concepto_id.unique'   => 'Este concepto ya tiene un análisis P.U. asignado.',
        ]);

        DB::beginTransaction();
        try {
            $analisis = AnalisisPu::create([
                'concepto_id'   => $request->concepto_id,
                'observaciones' => $request->observaciones,
            ]);

            // Guardar materiales
            foreach ($request->input('materiales', []) as $row) {
                if (!empty($row['material_id'])) {
                    AnalisisPuMaterial::create([
                        'analisis_pu_id' => $analisis->id,
                        'material_id'    => $row['material_id'],
                        'cantidad'       => $row['cantidad'],
                        'costo_unitario' => $row['costo_unitario'],
                    ]);
                }
            }

            // Guardar mano de obra
            foreach ($request->input('mano_obra', []) as $row) {
                if (!empty($row['mano_obra_id'])) {
                    AnalisisPuManoObra::create([
                        'analisis_pu_id' => $analisis->id,
                        'mano_obra_id'   => $row['mano_obra_id'],
                        'cantidad'       => $row['cantidad'],
                        'costo_unitario' => $row['costo_unitario'],
                    ]);
                }
            }

            // Guardar maquinaria
            foreach ($request->input('maquinaria', []) as $row) {
                if (!empty($row['maquinaria_equipo_id'])) {
                    AnalisisPuMaquinaria::create([
                        'analisis_pu_id'       => $analisis->id,
                        'maquinaria_equipo_id' => $row['maquinaria_equipo_id'],
                        'cantidad'             => $row['cantidad'],
                        'costo_unitario'       => $row['costo_unitario'],
                    ]);
                }
            }

            // Guardar indirectos
            foreach ($request->input('indirectos', []) as $row) {
                if (!empty($row['indirecto_id'])) {
                    AnalisisPuIndirecto::create([
                        'analisis_pu_id'      => $analisis->id,
                        'indirecto_id'        => $row['indirecto_id'],
                        'porcentaje_aplicado' => $row['porcentaje_aplicado'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('analisis_pu.show', $analisis->id)
                ->with('success', 'Análisis P.U. creado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->withErrors(['general' => 'Error al guardar el análisis.']);
        }
    }

    public function show($id)
    {
        $analisis = AnalisisPu::with([
            'concepto.unidadMedida',
            'concepto.area',
            'materiales.material.unidadMedida',
            'manoObra.manoObra.unidadMedida',
            'maquinaria.maquinariaEquipo.unidadMedida',
            'indirectos.indirecto',
        ])->findOrFail($id);

        return view('pu.show', compact('analisis'));
    }

    public function edit($id)
    {
        $analisis = AnalisisPu::with([
            'materiales.material',
            'manoObra.manoObra',
            'maquinaria.maquinariaEquipo',
            'indirectos.indirecto',
        ])->findOrFail($id);

        $conceptos  = Concepto::with('unidadMedida')->orderBy('clave')->get();
        $materiales = Material::orderBy('clave')->get();
        $manoObra   = ManoObra::orderBy('clave')->get();
        $maquinaria = MaquinariaEquipo::orderBy('clave')->get();
        $indirectos = Indirecto::orderBy('clave')->get();

        return view('pu.edit', compact('analisis', 'conceptos', 'materiales', 'manoObra', 'maquinaria', 'indirectos'));
    }

    public function update(Request $request, $id)
    {
        $analisis = AnalisisPu::findOrFail($id);

        $request->validate([
            'concepto_id'  => 'required|exists:conceptos,id|unique:analisis_pu,concepto_id,' . $id,
            'observaciones' => 'nullable|string|max:500',
            'materiales.*.material_id'          => 'required|exists:materiales,id',
            'materiales.*.cantidad'             => 'required|numeric|min:0',
            'materiales.*.costo_unitario'       => 'required|numeric|min:0',
            'mano_obra.*.mano_obra_id'          => 'required|exists:mano_obra,id',
            'mano_obra.*.cantidad'              => 'required|numeric|min:0',
            'mano_obra.*.costo_unitario'        => 'required|numeric|min:0',
            'maquinaria.*.maquinaria_equipo_id' => 'required|exists:maquinaria_equipos,id',
            'maquinaria.*.cantidad'             => 'required|numeric|min:0',
            'maquinaria.*.costo_unitario'       => 'required|numeric|min:0',
            'indirectos.*.indirecto_id'         => 'required|exists:indirectos,id',
            'indirectos.*.porcentaje_aplicado'  => 'required|numeric|min:0|max:100',
        ], [
            'concepto_id.unique' => 'Este concepto ya tiene otro análisis P.U. asignado.',
        ]);

        DB::beginTransaction();
        try {
            $analisis->update([
                'concepto_id'   => $request->concepto_id,
                'observaciones' => $request->observaciones,
            ]);

            // Reemplazar todos los insumos
            $analisis->materiales()->delete();
            $analisis->manoObra()->delete();
            $analisis->maquinaria()->delete();
            $analisis->indirectos()->delete();

            foreach ($request->input('materiales', []) as $row) {
                if (!empty($row['material_id'])) {
                    AnalisisPuMaterial::create([
                        'analisis_pu_id' => $analisis->id,
                        'material_id'    => $row['material_id'],
                        'cantidad'       => $row['cantidad'],
                        'costo_unitario' => $row['costo_unitario'],
                    ]);
                }
            }
            foreach ($request->input('mano_obra', []) as $row) {
                if (!empty($row['mano_obra_id'])) {
                    AnalisisPuManoObra::create([
                        'analisis_pu_id' => $analisis->id,
                        'mano_obra_id'   => $row['mano_obra_id'],
                        'cantidad'       => $row['cantidad'],
                        'costo_unitario' => $row['costo_unitario'],
                    ]);
                }
            }
            foreach ($request->input('maquinaria', []) as $row) {
                if (!empty($row['maquinaria_equipo_id'])) {
                    AnalisisPuMaquinaria::create([
                        'analisis_pu_id'       => $analisis->id,
                        'maquinaria_equipo_id' => $row['maquinaria_equipo_id'],
                        'cantidad'             => $row['cantidad'],
                        'costo_unitario'       => $row['costo_unitario'],
                    ]);
                }
            }
            foreach ($request->input('indirectos', []) as $row) {
                if (!empty($row['indirecto_id'])) {
                    AnalisisPuIndirecto::create([
                        'analisis_pu_id'      => $analisis->id,
                        'indirecto_id'        => $row['indirecto_id'],
                        'porcentaje_aplicado' => $row['porcentaje_aplicado'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('analisis_pu.show', $analisis->id)
                ->with('success', 'Análisis P.U. actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->withErrors(['general' => 'Error al actualizar el análisis.']);
        }
    }

    public function destroy($id)
    {
        $analisis = AnalisisPu::findOrFail($id);
        $analisis->delete();

        return redirect()->route('analisis_pu.index')
            ->with('success', 'Análisis P.U. eliminado correctamente.');
    }
}