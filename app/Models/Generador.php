<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generador extends Model
{
    use HasFactory;

    protected $table = 'generadores';

    protected $fillable = [
        'concepto_id',
        'localizacion',
        'ejes',
        'no_piezas',
        'largo',
        'ancho',
        'alto',
        'resultado',
    ];

    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
    }
}
