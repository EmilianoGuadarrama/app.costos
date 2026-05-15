<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * conceptos: id, id_area, descripcion, p_u, duracion_en_dias
 * NOTA: p_u es el precio unitario directo, sin APU separado.
 */
class Concepto extends Model
{
    protected $table = 'conceptos';
    protected $fillable = ['id_area','descripcion','p_u','duracion_en_dias','id_unidad_medida'];

    public function area()             { return $this->belongsTo(Area::class, 'id_area'); }
    public function unidadMedida()     { return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida'); }
    public function asignaConceptos()  { return $this->hasMany(AsignaConcepto::class, 'id_concepto'); }
}
