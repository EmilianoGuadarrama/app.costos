<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoDetalle extends Model
{
    protected $table = 'presupuesto_detalles';

    protected $fillable = [
        'presupuesto_id',
        'concepto_id',
        'bloque_id',
        'nivel_id',
        'cantidad',
        'precio_unitario',
        'pu_unitario_snapshot',
        'subtotal',
        'porcentaje_iva',
        'iva',
        'total_final',
        'importe',
        'cantidad_comprada',
        'saldo_cantidad',
        'saldo_monto',
    ];

    protected $casts = [
        'cantidad'             => 'float',
        'precio_unitario'      => 'float',
        'pu_unitario_snapshot' => 'float',
        'subtotal'             => 'float',
        'porcentaje_iva'       => 'float',
        'iva'                  => 'float',
        'total_final'          => 'float',
        'cantidad_comprada'    => 'float',
        'saldo_cantidad'       => 'float',
        'saldo_monto'          => 'float',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class)->with('unidadMedida');
    }

    public function bloque()
    {
        return $this->belongsTo(Bloque::class);
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    /**
     * PU efectivo: usa pu_unitario_snapshot si existe, sino precio_unitario
     */
    public function getPuEfectivoAttribute(): float
    {
        return (float) ($this->pu_unitario_snapshot ?: $this->precio_unitario ?: 0);
    }

    /**
     * Subtotal calculado = cantidad × PU
     */
    public function getSubtotalCalculadoAttribute(): float
    {
        return round($this->cantidad * $this->pu_efectivo, 2);
    }

    /**
     * IVA calculado
     */
    public function getIvaCalculadoAttribute(): float
    {
        $pct = $this->porcentaje_iva > 0 ? $this->porcentaje_iva : 16;
        return round($this->subtotal_calculado * ($pct / 100), 2);
    }

    /**
     * Total final con IVA
     */
    public function getTotalFinalCalculadoAttribute(): float
    {
        return round($this->subtotal_calculado + $this->iva_calculado, 2);
    }

    /**
     * Importe (alias compatible con código anterior)
     */
    public function getImporteAttribute(): float
    {
        return $this->subtotal_calculado;
    }
}
