<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * areas: id, abreviatura, descripcion
 */
class Area extends Model
{
    protected $table = 'areas';
    protected $fillable = ['abreviatura', 'descripcion'];

    public function conceptos()          { return $this->hasMany(Concepto::class, 'id_area'); }
    public function asignaConceptos()    { return $this->hasMany(AsignaConcepto::class, 'id_area'); }
    public function asignaMateriales()   { return $this->hasMany(AsignaMaterial::class, 'id_area'); }
    public function asignaMaquinaria()   { return $this->hasMany(AsignaMaquinaria::class, 'id_area'); }
    public function preProveedores()     { return $this->hasMany(PreProveedor::class, 'id_area'); }
    public function preMateriales()      { return $this->hasMany(PreMaterial::class, 'id_area'); }
}
