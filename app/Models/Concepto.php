<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    use HasFactory;

    protected $table = 'conceptos';

    protected $fillable = [
        'clave',
        'area_id',
        'partida',
        'subpartida',
        'descripcion',
        'unidad_medida_id',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }

    public function generadores()
    {
        return $this->hasMany(Generador::class);
    }
}
