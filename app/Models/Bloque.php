<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * bloques: id, descripcion
 */
class Bloque extends Model
{
    protected $table = 'bloques';
    protected $fillable = ['descripcion'];

    public function asignaConceptos()  { return $this->hasMany(AsignaConcepto::class, 'id_bloque'); }
    public function asignaMateriales() { return $this->hasMany(AsignaMaterial::class, 'id_bloque'); }
    public function asignaMaquinaria() { return $this->hasMany(AsignaMaquinaria::class, 'id_bloque'); }
    public function totalBloque()      { return $this->hasMany(TotalBloque::class, 'id_bloque'); }
}
