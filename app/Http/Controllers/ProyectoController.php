<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\ResponsableTecnico;
use App\Models\EstadoProyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::with(['cliente', 'responsableTecnico', 'estado'])
            ->latest()
            ->get();

        return view('proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        $estados = EstadoProyecto::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();

        return view('proyectos.create', compact('estados'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        DB::beginTransaction();

        try {
            $cliente = $this->resolverCliente($data);

            $empresaData = [
                'nombre' => $data['empresa_nombre'],
                'direccion' => $data['empresa_direccion'] ?? null,
            ];

            if ($request->hasFile('empresa_logo')) {
                $empresaData['logo_path'] = $request->file('empresa_logo')->store('empresas/logos', 'public');
            }

            $empresa = Empresa::create($empresaData);

            $responsableData = [
                'empresa_id' => $empresa->id,
                'nombre' => $data['empresa_responsable'],
                'cargo' => $data['empresa_cargo'],
            ];

            if ($request->hasFile('firma_digital')) {
                $responsableData['firma_path'] = $request->file('firma_digital')->store('responsables/firmas', 'public');
            }

            $responsable = ResponsableTecnico::create($responsableData);

            Proyecto::create([
                'cliente_id' => $cliente->id,
                'responsable_tecnico_id' => $responsable->id,
                'estado_proyecto_id' => $data['estado_proyecto_id'],
                'nombre' => $data['obra_nombre'],
                'ubicacion' => $data['obra_ubicacion'] ?? null,
                'tipo_obra' => $data['obra_tipo'] ?? null,
                'superficie_terreno' => $data['obra_superficie'] ?? null,
                'tipo_uso' => $data['obra_uso'] ?? null,
                'fecha_inicio' => $data['obra_fecha_inicio'] ?? null,
                'duracion_estimada' => $data['obra_duracion'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('proyectos.index')
                ->with('success', 'Proyecto creado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'general' => 'Ocurrió un error al guardar el proyecto. Inténtalo nuevamente.',
                ]);
        }
    }

    public function show($id)
    {
        $proyecto = Proyecto::with(['cliente', 'responsableTecnico', 'estado'])->findOrFail($id);
        return view('proyectos.show', compact('proyecto'));
    }

    public function edit($id)
    {
        $proyecto = Proyecto::with(['cliente', 'responsableTecnico.empresa', 'estado'])->findOrFail($id);

        $estados = EstadoProyecto::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();

        return view('proyectos.edit', compact('proyecto', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::with(['cliente', 'responsableTecnico'])->findOrFail($id);

        $data = $request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        DB::beginTransaction();

        try {
            $cliente = $proyecto->cliente;

            if ($cliente) {
                $cliente->update([
                    'tipo_persona' => $this->normalizarTipoPersona($data['tipo_persona']),
                    'nombre' => $data['cliente_nombre'],
                    'contacto_principal' => $data['cliente_nombre'],
                    'razon_social' => $data['cliente_razon_social'] ?? null,
                    'rfc' => $data['cliente_rfc'] ?? null,
                    'direccion' => $data['cliente_direccion'] ?? null,
                    'telefono' => $data['cliente_telefono'] ?? null,
                    'correo' => $data['cliente_correo'] ?? null,
                ]);
            } else {
                $cliente = $this->resolverCliente($data);
            }

            $responsable = $proyecto->responsableTecnico;
            $empresa = null;

            if ($responsable) {
                $empresa = Empresa::find($responsable->empresa_id);
            }

            if (!$empresa) {
                $empresaData = [
                    'nombre' => $data['empresa_nombre'],
                    'direccion' => $data['empresa_direccion'] ?? null,
                ];

                if ($request->hasFile('empresa_logo')) {
                    $empresaData['logo_path'] = $request->file('empresa_logo')->store('empresas/logos', 'public');
                }

                $empresa = Empresa::create($empresaData);
            } else {
                $empresaUpdate = [
                    'nombre' => $data['empresa_nombre'],
                    'direccion' => $data['empresa_direccion'] ?? null,
                ];

                if ($request->hasFile('empresa_logo')) {
                    $empresaUpdate['logo_path'] = $request->file('empresa_logo')->store('empresas/logos', 'public');
                }

                $empresa->update($empresaUpdate);
            }

            if (!$responsable) {
                $responsableData = [
                    'empresa_id' => $empresa->id,
                    'nombre' => $data['empresa_responsable'],
                    'cargo' => $data['empresa_cargo'],
                ];

                if ($request->hasFile('firma_digital')) {
                    $responsableData['firma_path'] = $request->file('firma_digital')->store('responsables/firmas', 'public');
                }

                $responsable = ResponsableTecnico::create($responsableData);
            } else {
                $responsableUpdate = [
                    'empresa_id' => $empresa->id,
                    'nombre' => $data['empresa_responsable'],
                    'cargo' => $data['empresa_cargo'],
                ];

                if ($request->hasFile('firma_digital')) {
                    $responsableUpdate['firma_path'] = $request->file('firma_digital')->store('responsables/firmas', 'public');
                }

                $responsable->update($responsableUpdate);
            }

            $proyecto->update([
                'cliente_id' => $cliente->id,
                'responsable_tecnico_id' => $responsable->id,
                'estado_proyecto_id' => $data['estado_proyecto_id'],
                'nombre' => $data['obra_nombre'],
                'ubicacion' => $data['obra_ubicacion'] ?? null,
                'tipo_obra' => $data['obra_tipo'] ?? null,
                'superficie_terreno' => $data['obra_superficie'] ?? null,
                'tipo_uso' => $data['obra_uso'] ?? null,
                'fecha_inicio' => $data['obra_fecha_inicio'] ?? null,
                'duracion_estimada' => $data['obra_duracion'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('proyectos.index')
                ->with('success', 'Proyecto actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'general' => 'Ocurrió un error al actualizar el proyecto. Inténtalo nuevamente.',
                ]);
        }
    }

    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado correctamente.');
    }

    private function rules(): array
    {
        return [
            'cliente_nombre' => 'required|string|max:150',
            'cliente_razon_social' => 'nullable|string|max:150',
            'cliente_direccion' => 'nullable|string|max:200',
            'cliente_telefono' => 'nullable|string|max:20',
            'cliente_correo' => 'nullable|email|max:120',
            'cliente_rfc' => 'nullable|string|max:20',
            'tipo_persona' => 'required|in:Física,Fisica,Moral,fisica,moral',

            'obra_nombre' => 'required|string|max:150',
            'obra_ubicacion' => 'nullable|string|max:200',
            'obra_tipo' => 'nullable|string|max:120',
            'obra_superficie' => 'nullable|numeric',
            'obra_uso' => 'nullable|string|max:120',
            'obra_fecha_inicio' => 'nullable|date',
            'obra_duracion' => 'nullable|string|max:100',
            'estado_proyecto_id' => 'required|exists:estados_proyecto,id',

            'empresa_nombre' => 'required|string|max:150',
            'empresa_logo' => 'nullable|image|max:2048',
            'empresa_direccion' => 'nullable|string|max:200',
            'empresa_responsable' => 'required|string|max:150',
            'empresa_cargo' => 'required|string|max:120',
            'firma_digital' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    private function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto válido.',
            'email' => 'El campo :attribute debe ser un correo electrónico válido.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'max.string' => 'El campo :attribute no debe exceder de :max caracteres.',
            'image' => 'El campo :attribute debe ser una imagen válida.',
            'file' => 'El campo :attribute debe ser un archivo válido.',
            'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
            'in' => 'El valor seleccionado en :attribute no es válido.',
            'exists' => 'El valor seleccionado en :attribute no es válido.',
        ];
    }

    private function attributes(): array
    {
        return [
            'cliente_nombre' => 'nombre del cliente',
            'cliente_razon_social' => 'razón social',
            'cliente_direccion' => 'dirección del cliente',
            'cliente_telefono' => 'teléfono del cliente',
            'cliente_correo' => 'correo del cliente',
            'cliente_rfc' => 'RFC del cliente',
            'tipo_persona' => 'tipo de persona',

            'obra_nombre' => 'nombre del proyecto',
            'obra_ubicacion' => 'ubicación del proyecto',
            'obra_tipo' => 'tipo de obra',
            'obra_superficie' => 'superficie del terreno',
            'obra_uso' => 'tipo de uso',
            'obra_fecha_inicio' => 'fecha de inicio',
            'obra_duracion' => 'duración estimada',
            'estado_proyecto_id' => 'estado del proyecto',

            'empresa_nombre' => 'nombre de la empresa',
            'empresa_logo' => 'logo de la empresa',
            'empresa_direccion' => 'dirección de la empresa',
            'empresa_responsable' => 'responsable técnico',
            'empresa_cargo' => 'cargo',
            'firma_digital' => 'firma digital',
        ];
    }

    private function resolverCliente(array $data): Cliente
    {
        $clienteData = [
            'tipo_persona' => $this->normalizarTipoPersona($data['tipo_persona']),
            'nombre' => $data['cliente_nombre'],
            'contacto_principal' => $data['cliente_nombre'],
            'razon_social' => $data['cliente_razon_social'] ?? null,
            'rfc' => $data['cliente_rfc'] ?? null,
            'direccion' => $data['cliente_direccion'] ?? null,
            'telefono' => $data['cliente_telefono'] ?? null,
            'correo' => $data['cliente_correo'] ?? null,
        ];

        if (!empty($clienteData['rfc'])) {
            return Cliente::updateOrCreate(
                ['rfc' => $clienteData['rfc']],
                $clienteData
            );
        }

        if (!empty($clienteData['correo'])) {
            return Cliente::updateOrCreate(
                ['correo' => $clienteData['correo']],
                $clienteData
            );
        }

        return Cliente::create($clienteData);
    }

    private function normalizarTipoPersona(string $tipo): string
    {
        $tipo = mb_strtolower(trim($tipo));

        return in_array($tipo, ['moral']) ? 'moral' : 'fisica';
    }
}