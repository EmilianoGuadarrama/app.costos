<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisisPuManoObra extends Model
{
    protected $table = 'analisis_pu_mano_obra';

    protected $fillable = [
        'analisis_pu_id',
        'mano_obra_id',
        'cantidad',
        'costo_unitario',   // columna real en BD: costo_unitario
    ];

    public function analisisPu()
    {
        return $this->belongsTo(AnalisisPu::class);
    }

    public function manoObra()
    {
        return $this->belongsTo(ManoObra::class);
    }

    public function getImporteAttribute(): float
    {
        return round($this->cantidad * $this->costo_unitario, 2);
    }
}
