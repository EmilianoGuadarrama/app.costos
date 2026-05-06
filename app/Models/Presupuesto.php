<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $table = 'presupuestos';

    protected $fillable = [
        'proyecto_id',
        'nombre',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function detalles()
    {
        return $this->hasMany(PresupuestoDetalle::class);
    }

    /**
     * Total calculado dinámicamente desde los detalles
     */
    public function getTotalAttribute(): float
    {
        return round(
            $this->detalles->sum(fn($d) => $d->cantidad * ($d->pu_unitario_snapshot ?? 0)),
            2
        );
    }
}