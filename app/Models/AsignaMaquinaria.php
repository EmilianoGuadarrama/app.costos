<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** asigna_maquinaria */
class AsignaMaquinaria extends Model
{
    protected $table = 'asigna_maquinaria';
    protected $fillable = [
        'id_obra','id_nivel','id_bloque','id_area','id_maquinaria',
        'cantidad','precio_unitario','subtotal','porcentaje_iva','iva','total_final',
    ];

    public function obra()       { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
    public function nivel()      { return $this->belongsTo(Nivel::class, 'id_nivel'); }
    public function bloque()     { return $this->belongsTo(Bloque::class, 'id_bloque'); }
    public function area()       { return $this->belongsTo(Area::class, 'id_area'); }
    public function maquinaria() { return $this->belongsTo(Maquinaria::class, 'id_maquinaria'); }
}
