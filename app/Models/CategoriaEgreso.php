<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaEgreso extends Model
{
    use HasFactory;

    protected $table = 'categorias_egreso';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}
