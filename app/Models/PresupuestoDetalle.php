<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoDetalle extends Model
{
    use HasFactory;

    protected $table = 'presupuesto_detalles';

    protected $fillable = [
        'presupuesto_id',
        'concepto_id',
        'cantidad',
        'precio_unitario',
        'importe',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
    }
}
