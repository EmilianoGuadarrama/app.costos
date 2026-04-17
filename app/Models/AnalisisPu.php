<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisPu extends Model
{
    use HasFactory;

    protected $table = 'analisis_pu';

    protected $fillable = [
        'concepto_id',
        'proyecto_id',
        'costo_directo',
        'costo_indirecto',
        'precio_unitario',
    ];

    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function materiales()
    {
        return $this->hasMany(AnalisisPuMaterial::class);
    }

    public function manoObras()
    {
        return $this->hasMany(AnalisisPuManoObra::class);
    }

    public function maquinarias()
    {
        return $this->hasMany(AnalisisPuMaquinaria::class);
    }

    public function indirectos()
    {
        return $this->hasMany(AnalisisPuIndirecto::class);
    }
}
