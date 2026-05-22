<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ObraConcepto extends Model
{
    protected $table = 'obra_conceptos';
    protected $fillable = [
        'id_obra',
        'id_concepto',
        'id_nivel',
        'id_bloque',
        'id_area',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'porcentaje_iva',
        'iva',
        'total_final',
    ];

    public function obra()    { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
    public function concepto(){ return $this->belongsTo(Concepto::class, 'id_concepto'); }
    public function nivel()   { return $this->belongsTo(Nivel::class, 'id_nivel'); }
    public function bloque()  { return $this->belongsTo(Bloque::class, 'id_bloque'); }
    public function area()    { return $this->belongsTo(Area::class, 'id_area'); }

    // Hijos (Insumos asignados a este concepto de la obra)
    public function materiales() { return $this->hasMany(AsignaMaterial::class, 'id_obra_concepto'); }
    public function maquinaria() { return $this->hasMany(AsignaMaquinaria::class, 'id_obra_concepto'); }
    public function manoObra()   { return $this->hasMany(AsignaManoObra::class, 'id_obra_concepto'); }
}
