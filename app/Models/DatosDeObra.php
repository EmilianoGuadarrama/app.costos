<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * datos_de_obra: id, nombre, descripcion, id_direccion, dimensiones_m2, num_niveles
 */
class DatosDeObra extends Model
{
    protected $table = 'datos_de_obra';
    protected $fillable = ['nombre','descripcion','id_direccion','dimensiones_m2','num_niveles'];

    public function direccion()     { return $this->belongsTo(Direccion::class, 'id_direccion'); }
    public function obraIniciada()  { return $this->hasOne(ObraIniciada::class, 'id_datos_de_obra'); }
}
