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
use App\Models\Estado;
use App\Models\Direccion;
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
            'obraProceso'
        ])->latest()->get();

        $obrasPresupuesto = $obras->whereNull('obraProceso');
        $obrasAprobadas = $obras->whereNotNull('obraProceso');

        $trashedCount = ObraIniciada::onlyTrashed()->count();

        return view('obras.index', compact('obrasPresupuesto', 'obrasAprobadas', 'trashedCount'));
    }

    public function create()
    {
        $empleados    = Empleado::with('persona')->get();
        $clientes     = Cliente::with('persona')->get();
        $diasInhabile = DiaInhabil::orderBy('fecha')->get();
        $estados      = Estado::orderBy('nombre')->get();

        return view('obras.create', compact('empleados', 'clientes', 'diasInhabile', 'estados'));
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
            'cliente_nuevo_rfc'    => 'required_with:cliente_nuevo_nombre|max:13',
            'cliente_nuevo_uso'    => 'required_with:cliente_nuevo_nombre|max:255',
            'cliente_nuevo_catastral' => 'required_with:cliente_nuevo_nombre|max:255',
            'cliente_calle'        => 'required_with:cliente_nuevo_nombre|max:255',
            'cliente_colonia'      => 'required_with:cliente_nuevo_nombre|max:255',
            'cliente_del'          => 'required_with:cliente_nuevo_nombre|max:255',
            'cliente_cp'           => 'required_with:cliente_nuevo_nombre|numeric',
            'cliente_estado'       => 'required_with:cliente_nuevo_nombre|exists:estados,id',
            // Direccion de Obra
            'obra_calle'           => 'nullable|string|max:255',
            'obra_colonia'         => 'nullable|string|max:255',
            'obra_del'             => 'nullable|string|max:255',
            'obra_cp'              => 'nullable|numeric',
            'obra_estado'          => 'nullable|exists:estados,id',
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'max'      => 'El campo :attribute no debe superar los :max caracteres.',
            'date'     => 'El campo :attribute debe ser una fecha válida.',
            'numeric'  => 'El campo :attribute debe ser un número.',
            'integer'  => 'El campo :attribute debe ser un número entero.',
            'email'    => 'El campo :attribute debe ser un correo electrónico válido.',
            'exists'   => 'El valor seleccionado para :attribute no es válido.',
            'required_with' => 'El campo :attribute es obligatorio cuando se registra un cliente nuevo.',
        ], [
            'nombre' => 'Nombre de la obra',
            'fecha_inicio' => 'Fecha de Inicio',
            'cliente_nuevo_rfc' => 'RFC',
            'cliente_nuevo_uso' => 'Uso de suelo',
            'cliente_nuevo_catastral' => 'Cuenta catastral',
            'cliente_calle' => 'Calle y número (Fiscal)',
            'cliente_colonia' => 'Colonia (Fiscal)',
            'cliente_del' => 'Delegación/Municipio (Fiscal)',
            'cliente_cp' => 'Código Postal (Fiscal)',
            'cliente_estado' => 'Estado (Fiscal)',
        ]);

        DB::beginTransaction();
        try {
            // 0. Cliente nuevo inline (si se proporcionó)
            $idCliente = $request->id_cliente;
            if (!$idCliente && $request->filled('cliente_nuevo_nombre')) {
                // Direccion del cliente
                $idDirFiscal = null;
                if ($request->filled('cliente_calle') || $request->filled('cliente_colonia')) {
                    $dir = Direccion::create([
                        'calle_y_numero' => $request->cliente_calle,
                        'colonia'        => $request->cliente_colonia,
                        'delegacion'     => $request->cliente_del,
                        'codigo_postal'  => $request->cliente_cp,
                        'id_estado'      => $request->cliente_estado,
                    ]);
                    $idDirFiscal = $dir->id;
                }

                $persona = Persona::create([
                    'nombre'       => $request->cliente_nuevo_nombre,
                    'telefono_1'   => $request->cliente_nuevo_tel ?? '',
                    'email'        => $request->cliente_nuevo_email ?? '',
                    'rfc'          => $request->cliente_nuevo_rfc,
                    'id_direccion' => $idDirFiscal,
                ]);

                $clienteNuevo = Cliente::create([
                    'id_persona'          => $persona->id,
                    'id_direccion_fiscal' => $idDirFiscal,
                    'uso_suelo'           => $request->cliente_nuevo_uso,
                    'cuenta_catastral'    => $request->cliente_nuevo_catastral,
                ]);
                $idCliente = $clienteNuevo->id;
            }

            // 1. datos_de_obra y su direccion
            $idDirObra = null;
            if ($request->filled('obra_calle') || $request->filled('obra_colonia')) {
                $dirObra = Direccion::create([
                    'calle_y_numero' => $request->obra_calle,
                    'colonia'        => $request->obra_colonia,
                    'delegacion'     => $request->obra_del,
                    'codigo_postal'  => $request->obra_cp,
                    'id_estado'      => $request->obra_estado,
                ]);
                $idDirObra = $dirObra->id;
            }

            $datos = DatosDeObra::create([
                'nombre'        => $request->nombre,
                'descripcion'   => $request->descripcion,
                'dimensiones_m2'=> $request->dimensiones_m2,
                'num_niveles'   => $request->num_niveles ?? 1,
                'id_direccion'  => $idDirObra,
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
        return redirect()->route('obras.index')->with('success', 'Obra movida a la papelera de reciclaje.');
    }



    public function papelera()
    {
        $obras = ObraIniciada::onlyTrashed()->with(['datosDeObra', 'cliente.persona'])->get();
        return view('obras.papelera', compact('obras'));
    }

    public function restaurar($id)
    {
        $obra = ObraIniciada::onlyTrashed()->findOrFail($id);
        $obra->restore();
        return back()->with('success', 'La obra ha sido restaurada exitosamente.');
    }

    public function forceDelete($id)
    {
        $obra = ObraIniciada::onlyTrashed()->findOrFail($id);
        // Aquí opcionalmente podrías borrar tablas relacionadas si no tienen cascada
        $obra->forceDelete();
        return back()->with('success', 'Obra eliminada permanentemente.');
    }

    /**
     * Recalcula los totales de la obra desde asigna_conceptos + asigna_materiales + asigna_maquinaria
     */
    public static function recalcularTotales(int $obraId): void
    {
        $versionActiva = \App\Models\VersionPresupuesto::where('id_obra', $obraId)->where('es_activa', true)->first();
        $ver = $versionActiva ? $versionActiva->numero_version : 1;

        $sub = \App\Models\ObraConcepto::where('id_obra', $obraId)->where('version', $ver)->sum('subtotal');
        $iva = \App\Models\ObraConcepto::where('id_obra', $obraId)->where('version', $ver)->sum('iva');
        $fin = $sub + $iva;

        TotalObra::updateOrCreate(
            ['id_obra' => $obraId],
            ['total_inicial' => $sub, 'total_iva' => $iva, 'total_final' => $fin]
        );

        ObraIniciada::where('id', $obraId)->update(['total_presupuestado' => $fin]);
    }
}
