<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisPuMaquinaria extends Model
{
    use HasFactory;

    protected $table = 'analisis_pu_maquinaria';

    protected $fillable = [
        'analisis_pu_id',
        'maquinaria_equipo_id',
        'cantidad',
        'costo',
    ];

    public function analisisPu()
    {
        return $this->belongsTo(AnalisisPu::class);
    }

    public function maquinariaEquipo()
    {
        return $this->belongsTo(MaquinariaEquipo::class);
    }
}
