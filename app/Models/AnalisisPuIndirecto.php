<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisisPuIndirecto extends Model
{
    protected $table = 'analisis_pu_indirectos';

    protected $fillable = [
        'analisis_pu_id',
        'indirecto_id',
        'porcentaje_aplicado',  // columna real en BD: porcentaje_aplicado
    ];

    public function analisisPu()
    {
        return $this->belongsTo(AnalisisPu::class);
    }

    public function indirecto()
    {
        return $this->belongsTo(Indirecto::class);
    }
}
