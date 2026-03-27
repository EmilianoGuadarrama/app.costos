<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::latest()->get();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_persona' => 'required|in:fisica,moral',
            'nombre' => 'required|string|max:150',
            'razon_social' => 'nullable|string|max:150',
            'rfc' => 'nullable|string|max:20|unique:clientes,rfc',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:120|unique:clientes,correo',
        ]);

        Cliente::create($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $data = $request->validate([
            'tipo_persona' => 'required|in:fisica,moral',
            'nombre' => 'required|string|max:150',
            'razon_social' => 'nullable|string|max:150',
            'rfc' => 'nullable|string|max:20|unique:clientes,rfc,' . $cliente->id,
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:120|unique:clientes,correo,' . $cliente->id,
        ]);

        $cliente->update($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
