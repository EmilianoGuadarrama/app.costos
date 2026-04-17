<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCajaChica extends Model
{
    use HasFactory;

    protected $table = 'movimientos_caja_chica';

    protected $fillable = [
        'caja_chica_id',
        'tipo',
        'monto',
        'concepto',
        'fecha',
        'comprobante',
    ];

    public function cajaChica()
    {
        return $this->belongsTo(CajaChica::class);
    }
}
