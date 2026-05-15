<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** niveles: id, id_obra, descripcion, m2 */
class Nivel extends Model
{
    protected $table = 'niveles';
    protected $fillable = ['id_obra','descripcion','m2'];

    public function obra()             { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
    public function asignaConceptos()  { return $this->hasMany(AsignaConcepto::class, 'id_nivel'); }
    public function asignaMateriales() { return $this->hasMany(AsignaMaterial::class, 'id_nivel'); }
    public function asignaMaquinaria() { return $this->hasMany(AsignaMaquinaria::class, 'id_nivel'); }
}
