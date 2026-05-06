<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaChica extends Model
{
    protected $table = 'cajas_chicas';

    protected $fillable = [
        'proyecto_id',
        'nombre',
        'responsable',
        'monto_inicial',
        'fecha_apertura',
    ];

    protected $casts = [
        'monto_inicial' => 'float',
        'fecha_apertura' => 'date',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoCajaChica::class);
    }

    /**
     * Saldo calculado dinámicamente desde los movimientos.
     * No existe columna saldo_actual en la BD real.
     */
    public function getSaldoActualAttribute(): float
    {
        $entradas = $this->movimientos->where('tipo', 'ENTRADA')->sum('monto');
        $salidas  = $this->movimientos->where('tipo', 'SALIDA')->sum('monto');
        return round($this->monto_inicial + $entradas - $salidas, 2);
    }
}
