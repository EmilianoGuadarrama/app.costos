<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\Direccion;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('persona')->orderBy('nombre_o_razon_social')->paginate(25);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $estados = Estado::orderBy('nombre')->get();
        return view('clientes.create', compact('estados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'apellido_paterno'  => 'nullable|string|max:255',
            'email'             => 'required|email|max:255|unique:personas,email',
            'telefono_1'        => 'nullable|string|max:20|unique:personas,telefono_1',
            'rfc'               => 'nullable|string|max:13|unique:personas,rfc',
            'nombre_o_razon_social' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $persona = Persona::create([
                'nombre'           => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'email'            => $request->email,
                'telefono_1'       => $request->telefono_1,
                'telefono_2'       => $request->telefono_2,
                'rfc'              => $request->rfc,
            ]);

            Cliente::create([
                'id_persona'          => $persona->id,
                'nombre_o_razon_social'=> $request->nombre_o_razon_social,
                'cuenta_catastral'    => $request->cuenta_catastral,
                'uso_suelo'           => $request->uso_suelo,
            ]);

            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente creado.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $cliente = Cliente::with(['persona.direccion.estado','direccionFiscal.estado'])->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::with('persona')->findOrFail($id);
        $estados = Estado::orderBy('nombre')->get();
        return view('clientes.edit', compact('cliente','estados'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::with('persona')->findOrFail($id);
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'nullable|email|max:255|unique:personas,email,'.($cliente->id_persona ?? 'NULL'),
            'rfc'      => 'nullable|string|max:13|unique:personas,rfc,'.($cliente->id_persona ?? 'NULL'),
            'telefono_1' => 'nullable|string|max:20|unique:personas,telefono_1,'.($cliente->id_persona ?? 'NULL'),
        ]);
        DB::beginTransaction();
        try {
            // Actualizar datos de la persona
            if ($cliente->persona) {
                $cliente->persona->update([
                    'nombre'           => $request->nombre,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'email'            => $request->email,
                    'telefono_1'       => $request->telefono_1,
                    'rfc'              => $request->rfc,
                ]);
            }
            // Actualizar datos del cliente
            $cliente->update([
                'nombre_o_razon_social' => $request->nombre_o_razon_social,
                'cuenta_catastral'      => $request->cuenta_catastral,
                'uso_suelo'             => $request->uso_suelo,
            ]);
            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }

    /** GET /api/clientes/buscar?q=... — autocompletado */
    public function buscar(Request $request)
    {
        $q = $request->input('q', '');
        $clientes = Cliente::with('persona')
            ->whereHas('persona', fn($query) =>
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('apellido_paterno', 'like', "%{$q}%")
            )
            ->orWhere('nombre_o_razon_social', 'like', "%{$q}%")
            ->limit(15)->get()
            ->map(fn($c) => [
                'id'    => $c->id,
                'texto' => trim(($c->persona?->nombre ?? '') . ' ' . ($c->persona?->apellido_paterno ?? ''))
                           ?: ($c->nombre_o_razon_social ?? "Cliente #{$c->id}"),
                'tel'   => $c->persona?->telefono_1 ?? '',
            ]);
        return response()->json($clientes);
    }
}

