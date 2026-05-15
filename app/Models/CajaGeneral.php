<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** caja_general: id, id_obra, ingresos_totales, egresos_totales */
class CajaGeneral extends Model
{
    protected $table = 'caja_general';
    protected $fillable = ['id_obra','ingresos_totales','egresos_totales'];

    public function obra() { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }

    public function getSaldoAttribute(): float
    {
        return (float)$this->ingresos_totales - (float)$this->egresos_totales;
    }
}
