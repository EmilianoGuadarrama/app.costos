<?php

namespace App\Http\Controllers;

use App\Models\MovimientoCajaChica;
use App\Models\CajaChica;
use Illuminate\Http\Request;

class MovimientoCajaChicaController extends Controller
{
    public function index()
    {
        $movimientos = MovimientoCajaChica::with('cajaChica')->latest()->get();
        return view('movimientos_caja_chica.index', compact('movimientos'));
    }

    public function create()
    {
        $cajas = CajaChica::orderBy('nombre')->get();
        return view('movimientos_caja_chica.create', compact('cajas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caja_chica_id' => 'required|exists:cajas_chicas,id',
            'tipo' => 'required|in:ingreso,egreso',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'fecha' => 'required|date',
            'comprobante' => 'nullable|string|max:255',
        ]);

        $movimiento = MovimientoCajaChica::create($data);
        
        // Actualizar saldo de caja chica
        $caja = CajaChica::find($data['caja_chica_id']);
        if ($data['tipo'] === 'ingreso') {
            $caja->saldo_actual += $data['monto'];
        } else {
            $caja->saldo_actual -= $data['monto'];
        }
        $caja->save();

        return redirect()->route('movimientos_caja_chica.index')->with('success', 'Movimiento registrado correctamente.');
    }

    public function show(MovimientoCajaChica $movimientos_caja_chica)
    {
        $movimientos_caja_chica->load('cajaChica');
        return view('movimientos_caja_chica.show', compact('movimientos_caja_chica'));
    }

    public function edit(MovimientoCajaChica $movimientos_caja_chica)
    {
        $cajas = CajaChica::orderBy('nombre')->get();
        return view('movimientos_caja_chica.edit', compact('movimientos_caja_chica', 'cajas'));
    }

    public function update(Request $request, MovimientoCajaChica $movimientos_caja_chica)
    {
        $data = $request->validate([
            'caja_chica_id' => 'required|exists:cajas_chicas,id',
            'tipo' => 'required|in:ingreso,egreso',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'fecha' => 'required|date',
            'comprobante' => 'nullable|string|max:255',
        ]);

        // Revertir efecto anterior y aplicar nuevo
        $caja = CajaChica::find($movimientos_caja_chica->caja_chica_id);
        if ($movimientos_caja_chica->tipo === 'ingreso') {
            $caja->saldo_actual -= $movimientos_caja_chica->monto;
        } else {
            $caja->saldo_actual += $movimientos_caja_chica->monto;
        }
        
        if ($data['tipo'] === 'ingreso') {
            $caja->saldo_actual += $data['monto'];
        } else {
            $caja->saldo_actual -= $data['monto'];
        }
        $caja->save();

        $movimientos_caja_chica->update($data);

        return redirect()->route('movimientos_caja_chica.index')->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(MovimientoCajaChica $movimientos_caja_chica)
    {
        $caja = CajaChica::find($movimientos_caja_chica->caja_chica_id);
        if ($movimientos_caja_chica->tipo === 'ingreso') {
            $caja->saldo_actual -= $movimientos_caja_chica->monto;
        } else {
            $caja->saldo_actual += $movimientos_caja_chica->monto;
        }
        $caja->save();
        
        $movimientos_caja_chica->delete();
        
        return redirect()->route('movimientos_caja_chica.index')->with('success', 'Movimiento eliminado correctamente.');
    }
}
