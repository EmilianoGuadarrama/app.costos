<?php

namespace App\Http\Controllers;

use App\Models\ProveedorAreaProyecto;
use App\Models\Proveedor;
use App\Models\Area;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProveedorAreaProyectoController extends Controller
{
    public function index()
    {
        $asignaciones = ProveedorAreaProyecto::with(['proveedor', 'area', 'proyecto'])->latest()->get();
        return view('proveedor_area_proyecto.index', compact('asignaciones'));
    }

    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        $areas = Area::orderBy('nombre')->get();
        $proyectos = Proyecto::orderBy('nombre')->get();
        return view('proveedor_area_proyecto.create', compact('proveedores', 'areas', 'proyectos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'area_id' => 'required|exists:areas,id',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        ProveedorAreaProyecto::create($data);

        return redirect()->route('proveedor_area_proyecto.index')->with('success', 'Asignación creada correctamente.');
    }

    public function show($id)
    {
        $asignacion = ProveedorAreaProyecto::with(['proveedor', 'area', 'proyecto'])->findOrFail($id);
        return view('proveedor_area_proyecto.show', compact('asignacion'));
    }

    public function edit($id)
    {
        $asignacion = ProveedorAreaProyecto::findOrFail($id);
        $proveedores = Proveedor::orderBy('nombre')->get();
        $areas = Area::orderBy('nombre')->get();
        $proyectos = Proyecto::orderBy('nombre')->get();
        
        return view('proveedor_area_proyecto.edit', compact('asignacion', 'proveedores', 'areas', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $asignacion = ProveedorAreaProyecto::findOrFail($id);

        $data = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'area_id' => 'required|exists:areas,id',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        $asignacion->update($data);

        return redirect()->route('proveedor_area_proyecto.index')->with('success', 'Asignación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $asignacion = ProveedorAreaProyecto::findOrFail($id);
        $asignacion->delete();
        
        return redirect()->route('proveedor_area_proyecto.index')->with('success', 'Asignación eliminada correctamente.');
    }
}
