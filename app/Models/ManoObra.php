<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManoObra extends Model
{
    use HasFactory;

    protected $table = 'mano_obra';

    protected $fillable = [
        'clave',
        'categoria',
        'unidad_medida_id',
        'salario_unitario',
    ];

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
