<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisisPu extends Model
{
    protected $table = 'analisis_pu';

    protected $fillable = [
        'concepto_id',
        'observaciones',
    ];

    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
    }

    public function materiales()
    {
        return $this->hasMany(AnalisisPuMaterial::class, 'analisis_pu_id');
    }

    public function manoObra()
    {
        return $this->hasMany(AnalisisPuManoObra::class, 'analisis_pu_id');
    }

    public function maquinaria()
    {
        return $this->hasMany(AnalisisPuMaquinaria::class, 'analisis_pu_id');
    }

    public function indirectos()
    {
        return $this->hasMany(AnalisisPuIndirecto::class, 'analisis_pu_id');
    }

    /**
     * Calcula el costo directo total del APU
     * (materiales + mano de obra + maquinaria)
     */
    public function getCostoDirectoAttribute(): float
    {
        $mat = $this->materiales->sum(fn($r) => $r->cantidad * $r->costo_unitario);
        $mo  = $this->manoObra->sum(fn($r) => $r->cantidad * $r->costo_unitario);
        $maq = $this->maquinaria->sum(fn($r) => $r->cantidad * $r->costo_unitario);
        return round($mat + $mo + $maq, 4);
    }

    /**
     * Calcula el total con indirectos aplicados
     */
    public function getCostoTotalAttribute(): float
    {
        $directo = $this->costo_directo;
        $factor  = $this->indirectos->sum('porcentaje_aplicado') / 100;
        return round($directo * (1 + $factor), 4);
    }
}