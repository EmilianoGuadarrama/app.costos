<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaquinariaEquipo extends Model
{
    use HasFactory;

    protected $table = 'maquinaria_equipos';

    protected $fillable = [
        'clave',
        'equipo',
        'unidad_medida_id',
        'costo_por_hora',
    ];

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
