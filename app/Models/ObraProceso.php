<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** obras_proceso */
class ObraProceso extends Model
{
    protected $table = 'obras_proceso';
    protected $fillable = [
        'id_obra', 'estado', 'con_iva', 'dias_transcurridos', 'porcentaje_avanzado',
        'presupuesto_cubierto', 'presupuesto_restante', 'porcentaje_restante',
        'estimacion_de_entrega', 'nivel_actual',
    ];
    protected $casts = ['estimacion_de_entrega' => 'date', 'con_iva' => 'boolean'];

    public function obraIniciada()  { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
    public function obraEntregada() { return $this->hasOne(ObraEntregada::class, 'id_obras_proceso'); }
}
