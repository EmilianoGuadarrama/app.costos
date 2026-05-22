<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManoObra extends Model
{
    use HasFactory;

    protected $table = 'mano_obra';

    protected $fillable = [
        'nombre',
        'id_unidad_medida',
        'precio_x_unidad',
    ];

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida');
    }
}
