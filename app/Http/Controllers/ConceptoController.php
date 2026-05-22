<?php
namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\ConceptoComposicion;
use App\Models\Area;
use App\Models\Material;
use App\Models\Maquinaria;
use App\Models\ManoObra;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConceptoController extends Controller
{
    public function index()
    {
        $conceptos = Concepto::with('area', 'unidadMedida', 'composicion')
            ->orderBy('descripcion')->paginate(50);
        return view('conceptos.index', compact('conceptos'));
    }

    public function create()
    {
        $areas      = Area::orderBy('abreviatura')->get();
        $unidades   = UnidadMedida::orderBy('abreviatura')->get();
        $materiales = Material::orderBy('nombre')->get();
        $maquinaria = Maquinaria::orderBy('nombre')->get();
        $manoObra   = ManoObra::orderBy('categoria')->get();
        return view('conceptos.create', compact('areas', 'unidades', 'materiales', 'maquinaria', 'manoObra'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_area'          => 'required|exists:areas,id',
            'descripcion'      => 'required|string',
            'p_u'              => 'required|numeric|min:0',
            'duracion_en_dias' => 'nullable|integer|min:0',
            'composicion'      => 'nullable|array',
            'composicion.*.tipo'          => 'required|in:material,maquinaria,mano_obra',
            'composicion.*.referencia_id' => 'required|integer|min:1',
            'composicion.*.cantidad'      => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Si viene id_unidad_medida como "nuevo", crear primero
            $umId = $this->resolverUnidadMedida($request);

            $concepto = Concepto::create([
                'id_area'          => $request->id_area,
                'descripcion'      => $request->descripcion,
                'p_u'              => $request->p_u,
                'duracion_en_dias' => $request->duracion_en_dias,
                'id_unidad_medida' => $umId,
            ]);

            $this->guardarComposicion($concepto->id, $request->input('composicion', []));

            DB::commit();
            return redirect()->route('conceptos.index')->with('success', 'Concepto creado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $concepto = Concepto::with('area', 'composicion')->findOrFail($id);
        return view('conceptos.show', compact('concepto'));
    }

    public function edit($id)
    {
        $concepto   = Concepto::with('composicion')->findOrFail($id);
        $areas      = Area::orderBy('abreviatura')->get();
        $unidades   = UnidadMedida::orderBy('abreviatura')->get();
        $materiales = Material::orderBy('nombre')->get();
        $maquinaria = Maquinaria::orderBy('nombre')->get();
        $manoObra   = ManoObra::orderBy('categoria')->get();
        return view('conceptos.edit', compact('concepto', 'areas', 'unidades', 'materiales', 'maquinaria', 'manoObra'));
    }

    public function update(Request $request, $id)
    {
        $concepto = Concepto::findOrFail($id);
        $request->validate([
            'id_area'          => 'required|exists:areas,id',
            'descripcion'      => 'required|string',
            'p_u'              => 'required|numeric|min:0',
            'composicion'      => 'nullable|array',
            'composicion.*.tipo'          => 'required|in:material,maquinaria,mano_obra',
            'composicion.*.referencia_id' => 'required|integer|min:1',
            'composicion.*.cantidad'      => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $umId = $this->resolverUnidadMedida($request);

            $concepto->update([
                'id_area'          => $request->id_area,
                'descripcion'      => $request->descripcion,
                'p_u'              => $request->p_u,
                'duracion_en_dias' => $request->duracion_en_dias,
                'id_unidad_medida' => $umId,
            ]);

            // Borrar composición anterior y reemplazar
            $concepto->composicion()->delete();
            $this->guardarComposicion($concepto->id, $request->input('composicion', []));

            DB::commit();
            return redirect()->route('conceptos.index')->with('success', 'Concepto actualizado.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        Concepto::findOrFail($id)->delete();
        return redirect()->route('conceptos.index')->with('success', 'Concepto eliminado.');
    }

    // ────────────────────────────────────────────────
    // API — autocompletado para el formulario unificado
    // ────────────────────────────────────────────────

    /** GET /conceptos/buscar?q=... → JSON */
    public function buscar(Request $request)
    {
        $q = $request->input('q', '');
        $conceptos = Concepto::with('unidadMedida', 'composicion')
            ->where('descripcion', 'like', "%{$q}%")
            ->orderBy('descripcion')
            ->limit(20)
            ->get()
            ->map(fn($c) => [
                'id'          => $c->id,
                'texto'       => $c->descripcion,
                'pu'          => (float) $c->p_u,
                'um'          => $c->unidadMedida?->abreviatura ?? '',
                'um_id'       => $c->id_unidad_medida,
                'composicion' => $c->composicion->map(fn($comp) => [
                    'tipo'        => $comp->tipo,
                    'descripcion' => $comp->descripcion_referencia,
                    'cantidad'    => $comp->cantidad,
                    'unidad'      => $comp->unidad,
                ]),
            ]);
        return response()->json($conceptos);
    }

    // ────────────────────────────────────────────────
    // HELPERS
    // ────────────────────────────────────────────────

    private function resolverUnidadMedida(Request $request): ?int
    {
        if ($request->filled('nueva_um_abreviatura')) {
            $um = UnidadMedida::firstOrCreate(
                ['abreviatura' => strtoupper(trim($request->nueva_um_abreviatura))],
                ['nombre'      => trim($request->nueva_um_nombre ?? $request->nueva_um_abreviatura)]
            );
            return $um->id;
        }
        return $request->id_unidad_medida ?: null;
    }

    private function guardarComposicion(int $conceptoId, array $filas): void
    {
        foreach ($filas as $fila) {
            if (empty($fila['tipo']) || empty($fila['referencia_id'])) continue;

            // Obtener nombre del insumo para guardarlo como snapshot
            $desc = match($fila['tipo']) {
                'material'   => Material::find($fila['referencia_id'])?->nombre ?? 'Material',
                'maquinaria' => Maquinaria::find($fila['referencia_id'])?->nombre ?? 'Maquinaria',
                'mano_obra'  => ManoObra::find($fila['referencia_id'])?->categoria ?? 'Mano de Obra',
                default      => '—',
            };

            $unidad = match($fila['tipo']) {
                'material'   => Material::find($fila['referencia_id'])?->unidadMedida?->abreviatura ?? '',
                'maquinaria' => Maquinaria::find($fila['referencia_id'])?->unidadMedida?->abreviatura ?? '',
                'mano_obra'  => ManoObra::find($fila['referencia_id'])?->unidadMedida?->abreviatura ?? '',
                default      => '',
            };

            ConceptoComposicion::create([
                'concepto_id'           => $conceptoId,
                'tipo'                  => $fila['tipo'],
                'referencia_id'         => $fila['referencia_id'],
                'descripcion_referencia'=> $desc,
                'cantidad'              => $fila['cantidad'] ?? 1,
                'unidad'                => $unidad,
            ]);
        }
    }
}
