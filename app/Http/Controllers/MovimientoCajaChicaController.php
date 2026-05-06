<?php

namespace App\Http\Controllers;

use App\Models\MovimientoCajaChica;
use App\Models\CajaChica;
use Illuminate\Http\Request;

class MovimientoCajaChicaController extends Controller
{
    public function index()
    {
        $movimientos = MovimientoCajaChica::with('cajaChica.proyecto')
            ->latest()
            ->get();

        return view('movimientos_caja_chica.index', compact('movimientos'));
    }

    public function create()
    {
        $cajas = CajaChica::with('proyecto')->orderBy('nombre')->get();
        return view('movimientos_caja_chica.create', compact('cajas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caja_chica_id' => 'required|exists:cajas_chicas,id',
            'tipo'          => 'required|in:ENTRADA,SALIDA',
            'monto'         => 'required|numeric|min:0.01',
            'concepto'      => 'required|string|max:200',
            'responsable'   => 'nullable|string|max:150',
            'categoria'     => 'nullable|string|max:100',
            'fecha'         => 'required|date',
        ], [
            'tipo.in'    => 'El tipo debe ser ENTRADA o SALIDA.',
            'monto.min'  => 'El monto debe ser mayor a cero.',
        ]);

        // ✅ Validar saldo suficiente para salidas
        if ($data['tipo'] === MovimientoCajaChica::TIPO_SALIDA) {
            $caja         = CajaChica::with('movimientos')->findOrFail($data['caja_chica_id']);
            $saldoActual  = $caja->saldo_actual; // calculado dinámicamente

            if ($data['monto'] > $saldoActual) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'monto' => sprintf(
                            'Saldo insuficiente. Saldo disponible: $%s — Monto solicitado: $%s',
                            number_format($saldoActual, 2),
                            number_format($data['monto'], 2)
                        ),
                    ]);
            }
        }

        MovimientoCajaChica::create($data);

        return redirect()
            ->route('movimientos_caja_chica.index')
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function show(MovimientoCajaChica $movimientos_caja_chica)
    {
        $movimientos_caja_chica->load('cajaChica.proyecto');
        return view('movimientos_caja_chica.show', compact('movimientos_caja_chica'));
    }

    public function edit(MovimientoCajaChica $movimientos_caja_chica)
    {
        $cajas = CajaChica::with('proyecto')->orderBy('nombre')->get();
        return view('movimientos_caja_chica.edit', compact('movimientos_caja_chica', 'cajas'));
    }

    public function update(Request $request, MovimientoCajaChica $movimientos_caja_chica)
    {
        $data = $request->validate([
            'caja_chica_id' => 'required|exists:cajas_chicas,id',
            'tipo'          => 'required|in:ENTRADA,SALIDA',
            'monto'         => 'required|numeric|min:0.01',
            'concepto'      => 'required|string|max:200',
            'responsable'   => 'nullable|string|max:150',
            'categoria'     => 'nullable|string|max:100',
            'fecha'         => 'required|date',
        ]);

        // Validar saldo: revertir el movimiento actual y verificar con el nuevo
        if ($data['tipo'] === MovimientoCajaChica::TIPO_SALIDA) {
            $caja        = CajaChica::with('movimientos')->findOrFail($data['caja_chica_id']);
            // Saldo excluyendo el movimiento que estamos editando
            $saldoBase   = $caja->saldo_actual;
            if ($movimientos_caja_chica->tipo === MovimientoCajaChica::TIPO_SALIDA) {
                $saldoBase += $movimientos_caja_chica->monto; // devolver la salida anterior
            } else {
                $saldoBase -= $movimientos_caja_chica->monto; // quitar la entrada anterior
            }

            if ($data['monto'] > $saldoBase) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'monto' => sprintf(
                            'Saldo insuficiente. Saldo disponible: $%s',
                            number_format($saldoBase, 2)
                        ),
                    ]);
            }
        }

        $movimientos_caja_chica->update($data);

        return redirect()
            ->route('movimientos_caja_chica.index')
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(MovimientoCajaChica $movimientos_caja_chica)
    {
        $movimientos_caja_chica->delete();

        return redirect()
            ->route('movimientos_caja_chica.index')
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}
