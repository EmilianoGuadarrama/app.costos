<?php
namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::with('persona')->orderBy('empresa')->paginate(25);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create() { return view('proveedores.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string|max:255',
            'email'   => 'required|email|unique:personas,email',
            'empresa' => 'nullable|string|max:255',
        ]);
        DB::beginTransaction();
        try {
            $persona = Persona::create([
                'nombre'      => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'email'       => $request->email,
                'telefono_1'  => $request->telefono_1,
                'rfc'         => $request->rfc,
            ]);
            Proveedor::create(['id_persona' => $persona->id, 'empresa' => $request->empresa]);
            DB::commit();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor creado.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $proveedor = Proveedor::with('persona')->findOrFail($id);
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit($id)
    {
        $proveedor = Proveedor::with('persona')->findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::with('persona')->findOrFail($id);
        DB::beginTransaction();
        try {
            $proveedor->persona->update([
                'nombre'     => $request->nombre,
                'email'      => $request->email,
                'telefono_1' => $request->telefono_1,
            ]);
            $proveedor->update(['empresa' => $request->empresa]);
            DB::commit();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        Proveedor::findOrFail($id)->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }
}
