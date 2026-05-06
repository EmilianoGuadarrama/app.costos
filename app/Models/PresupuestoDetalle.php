<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoDetalle extends Model
{
    protected $table = 'presupuesto_detalles';

    protected $fillable = [
        'presupuesto_id',
        'concepto_id',
        'cantidad',
        'pu_unitario_snapshot',  // precio unitario al momento de crear el detalle
    ];

    protected $casts = [
        'cantidad'            => 'float',
        'pu_unitario_snapshot' => 'float',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class)->with('unidadMedida');
    }

    /**
     * Importe de este renglón = cantidad × PU
     */
    public function getImporteAttribute(): float
    {
        return round($this->cantidad * ($this->pu_unitario_snapshot ?? 0), 2);
    }
}
