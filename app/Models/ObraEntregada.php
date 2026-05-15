<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** obras_entregadas */
class ObraEntregada extends Model
{
    protected $table = 'obras_entregadas';
    protected $fillable = ['id_obras_proceso','fecha_entrega','ingresos_generales','egresos'];
    protected $casts = ['fecha_entrega' => 'date'];

    public function obraProceso() { return $this->belongsTo(ObraProceso::class, 'id_obras_proceso'); }
}
