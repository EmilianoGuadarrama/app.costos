<?php
namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with('persona')->get();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('empleados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellido_paterno' => 'nullable|string|max:255',
            'email'            => 'nullable|email|max:255|unique:personas,email',
            'telefono_1'       => 'nullable|string|max:20|unique:personas,telefono_1',
            'rol'              => 'nullable|string|max:255',
            'salario_base'     => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $persona = Persona::create([
                'nombre'           => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'email'            => $request->email,
                'telefono_1'       => $request->telefono_1,
            ]);

            Empleado::create([
                'id_persona'   => $persona->id,
                'rol'          => $request->rol,
                'salario_base' => $request->salario_base,
            ]);

            DB::commit();
            return redirect()->route('empleados.index')->with('success', 'Empleado creado exitosamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $empleado = Empleado::with('persona')->findOrFail($id);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::with('persona')->findOrFail($id);

        $request->validate([
            'nombre'           => 'required|string|max:255',
            'email'            => 'nullable|email|max:255|unique:personas,email,'.$empleado->id_persona,
        ]);

        DB::beginTransaction();
        try {
            $empleado->persona->update([
                'nombre'           => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'email'            => $request->email,
                'telefono_1'       => $request->telefono_1,
            ]);

            $empleado->update([
                'rol'          => $request->rol,
                'salario_base' => $request->salario_base,
            ]);

            DB::commit();
            return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        Empleado::findOrFail($id)->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }
}
