<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCajaChica extends Model
{
    protected $table = 'movimientos_caja_chica';

    // Tipos reales en BD: ENTRADA / SALIDA (mayúsculas)
    const TIPO_ENTRADA = 'ENTRADA';
    const TIPO_SALIDA  = 'SALIDA';

    protected $fillable = [
        'caja_chica_id',
        'fecha',
        'responsable',
        'concepto',
        'categoria',
        'monto',
        'tipo',
    ];

    protected $casts = [
        'fecha'  => 'date',
        'monto'  => 'float',
    ];

    public function cajaChica()
    {
        return $this->belongsTo(CajaChica::class);
    }
}
