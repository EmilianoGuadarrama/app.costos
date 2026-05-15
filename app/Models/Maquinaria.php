<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** maquinaria: id, nombre, descripcion, id_unidad_medida, precio_x_unidad */
class Maquinaria extends Model
{
    protected $table = 'maquinaria';
    protected $fillable = ['nombre','descripcion','id_unidad_medida','precio_x_unidad'];

    public function unidadMedida() { return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida'); }
    public function asignaciones() { return $this->hasMany(AsignaMaquinaria::class, 'id_maquinaria'); }
}
