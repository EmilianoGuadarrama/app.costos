<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    use HasFactory;

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

    public function detalles()
    {
        return $this->hasMany(PresupuestoDetalle::class);
    }

    public function reportes()
    {
        return $this->hasMany(ReporteGenerado::class);
    }
}
