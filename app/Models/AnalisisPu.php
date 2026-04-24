<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisisPu extends Model
{
    protected $table = 'analisis_pu';

    protected $fillable = [
        'proyecto_id',
        'concepto_id',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
    }
}