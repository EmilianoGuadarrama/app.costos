<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::latest()->get();
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'contacto' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:120',
            'direccion' => 'nullable|string|max:255',
            'tipo' => 'nullable|string|max:50',
        ]);

        Proveedor::create($data);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }

    public function show(Proveedor $proveedore)
    {
        return view('proveedores.show', compact('proveedore'));
    }

    public function edit(Proveedor $proveedore)
    {
        return view('proveedores.edit', compact('proveedore'));
    }

    public function update(Request $request, Proveedor $proveedore)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'contacto' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:120',
            'direccion' => 'nullable|string|max:255',
            'tipo' => 'nullable|string|max:50',
        ]);

        $proveedore->update($data);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedore)
    {
        $proveedore->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}
