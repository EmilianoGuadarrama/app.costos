<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales';

    protected $fillable = [
        'clave',
        'descripcion',
        'unidad_medida_id',
        'precio_unitario',
    ];

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
