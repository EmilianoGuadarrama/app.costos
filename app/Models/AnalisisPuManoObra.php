<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisPuManoObra extends Model
{
    use HasFactory;

    protected $table = 'analisis_pu_mano_obra';

    protected $fillable = [
        'analisis_pu_id',
        'mano_obra_id',
        'cantidad',
        'costo',
    ];

    public function analisisPu()
    {
        return $this->belongsTo(AnalisisPu::class);
    }

    public function manoObra()
    {
        return $this->belongsTo(ManoObra::class);
    }
}
