<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisisPuMaquinaria extends Model
{
    protected $table = 'analisis_pu_maquinaria';

    protected $fillable = [
        'analisis_pu_id',
        'maquinaria_equipo_id',
        'cantidad',
        'costo_unitario',   // columna real en BD: costo_unitario
    ];

    public function analisisPu()
    {
        return $this->belongsTo(AnalisisPu::class);
    }

    public function maquinariaEquipo()
    {
        return $this->belongsTo(MaquinariaEquipo::class);
    }

    public function getImporteAttribute(): float
    {
        return round($this->cantidad * $this->costo_unitario, 2);
    }
}
