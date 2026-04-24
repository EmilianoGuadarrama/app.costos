<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $table = 'presupuestos';

    protected $fillable = [
        'proyecto_id',
        'nombre',
        'total',
        'estado',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}