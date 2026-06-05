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
        'version',
        'presupuesto_padre_id',
        'es_version_actual'
    ];

    protected $casts = [
        'fecha' => 'date',
        'es_version_actual' => 'boolean',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function detalles()
    {
        return $this->hasMany(PresupuestoDetalle::class);
    }

    public function padre()
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_padre_id');
    }

    public function versiones()
    {
        return $this->hasMany(Presupuesto::class, 'presupuesto_padre_id');
    }

    public function modificaciones()
    {
        return $this->hasMany(ModificacionPresupuesto::class);
    }

    /**
     * Total base calculado dinámicamente desde los detalles
     */
    public function getTotalBaseAttribute(): float
    {
        return round(
            $this->detalles->sum(fn($d) => $d->cantidad * ($d->pu_unitario_snapshot ?? 0)),
            2
        );
    }

    /**
     * Total final calculado incluyendo aditivas y deductivas
     */
    public function getTotalAttribute(): float
    {
        $base = $this->total_base;
        $aditivas = $this->modificaciones()->where('tipo', 'aditiva')->sum('monto');
        $deductivas = $this->modificaciones()->where('tipo', 'deductiva')->sum('monto');
        
        return $base + $aditivas - $deductivas;
    }
}