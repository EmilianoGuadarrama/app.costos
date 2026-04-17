<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisPuIndirecto extends Model
{
    use HasFactory;

    protected $table = 'analisis_pu_indirectos';

    protected $fillable = [
        'analisis_pu_id',
        'indirecto_id',
        'monto',
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
