<?php
namespace App\Http\Controllers;

use App\Models\ObraIniciada;
use App\Models\DatosDeObra;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Nivel;
use App\Models\TotalObra;
use App\Models\CajaGeneral;
use App\Models\DiaInhabil;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ObraController — gestiona obras_iniciadas + datos_de_obra
 * Reemplaza al antiguo ProyectoController.
 */
class ObraController extends Controller
{
    public function index()
    {
        $obras = ObraIniciada::with([
            'datosDeObra.direccion.estado',
            'encargado.persona',
            'totalObra',
            'cajaGeneral',
            'niveles',
        ])->latest()->get();

        return view('obras.index', compact('obras'));
    }

    public function create()
    {
        $empleados    = Empleado::with('persona')->get();
        $clientes     = Cliente::with('persona')->get();
        $diasInhabile = DiaInhabil::orderBy('fecha')->get();

        return view('obras.create', compact('empleados', 'clientes', 'diasInhabile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'               => 'required|string|max:255',
            'descripcion'          => 'nullable|string',
            'fecha_inicio'         => 'required|date',
            'duracion'             => 'nullable|string|max:100',
            'encargado_id_empleado'=> 'nullable|exists:empleados,id',
            'id_cliente'           => 'nullable|exists:clientes,id',
            'dimensiones_m2'       => 'nullable|numeric',
            'num_niveles'          => 'nullable|integer|min:1',
            'niveles'              => 'nullable|array',
            'niveles.*.descripcion'=> 'required|string|max:255',
            // Cliente nuevo inline
            'cliente_nuevo_nombre' => 'nullable|string|max:255',
            'cliente_nuevo_tel'    => 'nullable|string|max:20',
            'cliente_nuevo_email'  => 'nullable|email|max:255',
        ]);

        DB::beginTransaction();
        try {
            // 0. Cliente nuevo inline (si se proporcionó)
            $idCliente = $request->id_cliente;
            if (!$idCliente && $request->filled('cliente_nuevo_nombre')) {
                $persona = Persona::create([
                    'nombre'     => $request->cliente_nuevo_nombre,
                    'telefono_1' => $request->cliente_nuevo_tel ?? '',
                    'email'      => $request->cliente_nuevo_email ?? '',
                ]);
                $clienteNuevo = Cliente::create(['id_persona' => $persona->id]);
                $idCliente = $clienteNuevo->id;
            }

            // 1. datos_de_obra
            $datos = DatosDeObra::create([
                'nombre'        => $request->nombre,
                'descripcion'   => $request->descripcion,
                'dimensiones_m2'=> $request->dimensiones_m2,
                'num_niveles'   => $request->num_niveles ?? 1,
            ]);

            // 2. obras_iniciadas
            $obra = ObraIniciada::create([
                'id_datos_de_obra'        => $datos->id,
                'encargado_id_empleado'   => $request->encargado_id_empleado,
                'id_cliente'              => $idCliente,
                'fecha_inicio'            => $request->fecha_inicio,
                'duracion'                => $request->duracion,
                'precio_por_m2_estimado'  => $request->precio_por_m2_estimado,
                'total_de_obra_estimado'  => $request->total_de_obra_estimado,
            ]);

            // 3. Niveles
            $nivelesInput = $request->input('niveles', []);
            if (empty($nivelesInput)) {
                Nivel::create(['id_obra' => $obra->id, 'descripcion' => 'Planta Baja', 'm2' => null]);
            } else {
                foreach ($nivelesInput as $niv) {
                    if (!empty($niv['descripcion'])) {
                        Nivel::create([
                            'id_obra'     => $obra->id,
                            'descripcion' => $niv['descripcion'],
                            'm2'          => $niv['m2'] ?? null,
                        ]);
                    }
                }
            }

            // 4. Registros de totales y caja vacíos
            TotalObra::create(['id_obra' => $obra->id, 'total_inicial' => 0, 'total_iva' => 0, 'total_final' => 0]);
            CajaGeneral::create(['id_obra' => $obra->id, 'ingresos_totales' => 0, 'egresos_totales' => 0]);

            DB::commit();
            return redirect()->route('obras.show', $obra->id)
                ->with('success', 'Obra creada correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->withErrors(['general' => 'Error al crear la obra: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $obra = ObraIniciada::with([
            'datosDeObra.direccion.estado',
            'encargado.persona',
            'niveles',
            'totalObra',
            'cajaGeneral',
            'preProveedores.proveedor.persona',
            'preProveedores.area',
            'preMateriales.area',
            'egresos',
            'ingresos',
        ])->findOrFail($id);

        return view('obras.show', compact('obra'));
    }

    public function edit($id)
    {
        $obra      = ObraIniciada::with(['datosDeObra', 'encargado', 'niveles'])->findOrFail($id);
        $empleados = Empleado::with('persona')->get();

        return view('obras.edit', compact('obra', 'empleados'));
    }

    public function update(Request $request, $id)
    {
        $obra = ObraIniciada::with(['datosDeObra'])->findOrFail($id);

        $request->validate([
            'nombre'               => 'required|string|max:255',
            'fecha_inicio'         => 'required|date',
            'duracion'             => 'nullable|string|max:100',
            'encargado_id_empleado'=> 'nullable|exists:empleados,id',
            'dimensiones_m2'       => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            $obra->datosDeObra->update([
                'nombre'         => $request->nombre,
                'descripcion'    => $request->descripcion,
                'dimensiones_m2' => $request->dimensiones_m2,
            ]);

            $obra->update([
                'encargado_id_empleado' => $request->encargado_id_empleado,
                'fecha_inicio'          => $request->fecha_inicio,
                'duracion'              => $request->duracion,
                'precio_por_m2_estimado'=> $request->precio_por_m2_estimado,
                'total_de_obra_estimado'=> $request->total_de_obra_estimado,
            ]);

            DB::commit();
            return redirect()->route('obras.show', $obra->id)
                ->with('success', 'Obra actualizada correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $obra = ObraIniciada::findOrFail($id);
        $obra->delete();
        return redirect()->route('obras.index')->with('success', 'Obra eliminada.');
    }

    /**
     * Recalcula los totales de la obra desde asigna_conceptos + asigna_materiales + asigna_maquinaria
     */
    public static function recalcularTotales(int $obraId): void
    {
        $sub = \App\Models\AsignaConcepto::where('id_obra', $obraId)->sum('subtotal')
             + \App\Models\AsignaMaterial::where('id_obra', $obraId)->sum('subtotal')
             + \App\Models\AsignaMaquinaria::where('id_obra', $obraId)->sum('subtotal');

        $iva = \App\Models\AsignaConcepto::where('id_obra', $obraId)->sum('iva')
             + \App\Models\AsignaMaterial::where('id_obra', $obraId)->sum('iva')
             + \App\Models\AsignaMaquinaria::where('id_obra', $obraId)->sum('iva');

        $fin = $sub + $iva;

        TotalObra::updateOrCreate(
            ['id_obra' => $obraId],
            ['total_inicial' => $sub, 'total_iva' => $iva, 'total_final' => $fin]
        );

        ObraIniciada::where('id', $obraId)->update(['total_presupuestado' => $fin]);
    }
}
