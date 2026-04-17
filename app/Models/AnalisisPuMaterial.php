<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisPuMaterial extends Model
{
    use HasFactory;

    protected $table = 'analisis_pu_materiales';

    protected $fillable = [
        'analisis_pu_id',
        'material_id',
        'cantidad',
        'costo',
    ];

    public function analisisPu()
    {
        return $this->belongsTo(AnalisisPu::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
