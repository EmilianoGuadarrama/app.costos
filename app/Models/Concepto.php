<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concepto extends Model
{
    use SoftDeletes;

    protected $table = 'conceptos';

    protected $primaryKey = 'id_concepto';

    protected $fillable = [
        'codigo',
        'descripcion',
        'id_unidad',
        'precio_unitario'
    ];
}