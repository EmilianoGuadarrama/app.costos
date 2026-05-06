<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisisPuMaterial extends Model
{
    protected $table = 'analisis_pu_materiales';

    protected $fillable = [
        'analisis_pu_id',
        'material_id',
        'cantidad',
        'costo_unitario',   // columna real en BD: costo_unitario
    ];

    public function analisisPu()
    {
        return $this->belongsTo(AnalisisPu::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function getImporteAttribute(): float
    {
        return round($this->cantidad * $this->costo_unitario, 2);
    }
}
