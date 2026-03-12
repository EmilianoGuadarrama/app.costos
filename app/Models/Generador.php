<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Generador extends Model
{
    protected $table = 'generadores';
    protected $primaryKey = 'id_generador';

    protected $fillable = [
        'concepto',
        'unidad',
        'localizacion',
        'ejes',
        'no_piezas',
        'ancho',
        'largo',
        'alto',
        'resultado',
    ];
}
