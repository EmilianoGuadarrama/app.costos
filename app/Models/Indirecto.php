<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indirecto extends Model
{
    use HasFactory;

    protected $table = 'indirectos';

    protected $fillable = [
        'clave',
        'concepto',
        'porcentaje',
        'descripcion',
    ];
}