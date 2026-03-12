<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    use HasFactory;

    protected $table = 'conceptos';
    protected $primaryKey = 'id_concepto';

    protected $fillable = [
        'codigo','subpartida','descripcion','unidad','cantidad','pu','importe'
    ];
}
