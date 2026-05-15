<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** materiales: id, nombre, descripcion, marca, id_unidad_medida, cantidad_contenida, precio_x_unidad */
class Material extends Model
{
    protected $table = 'materiales';
    protected $fillable = ['nombre','descripcion','marca','id_unidad_medida','cantidad_contenida','precio_x_unidad'];

    public function unidadMedida()     { return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida'); }
    public function asignaciones()     { return $this->hasMany(AsignaMaterial::class, 'id_material'); }
}