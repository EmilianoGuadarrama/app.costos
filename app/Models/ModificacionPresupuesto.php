<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModificacionPresupuesto extends Model
{
    protected $table = 'modificacion_presupuestos';

    protected $fillable = [
        'presupuesto_id',
        'tipo',
        'monto',
        'motivo',
        'fecha',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }
}
