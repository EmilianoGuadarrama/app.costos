<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * asigna_conceptos: id, id_obra, id_nivel, id_bloque, id_area,
 *   id_concepto, cantidad, precio_unitario, subtotal, porcentaje_iva, iva, total_final
 */
class AsignaConcepto extends Model
{
    protected $table = 'asigna_conceptos';
    protected $fillable = [
        'id_obra','id_nivel','id_bloque','id_area','id_concepto',
        'cantidad','precio_unitario','subtotal','porcentaje_iva','iva','total_final',
    ];

    public function obra()     { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
    public function nivel()    { return $this->belongsTo(Nivel::class, 'id_nivel'); }
    public function bloque()   { return $this->belongsTo(Bloque::class, 'id_bloque'); }
    public function area()     { return $this->belongsTo(Area::class, 'id_area'); }
    public function concepto() { return $this->belongsTo(Concepto::class, 'id_concepto'); }
}
