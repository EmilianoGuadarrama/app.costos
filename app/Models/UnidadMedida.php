<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table = 'unidad_medida';
    protected $fillable = ['abreviatura', 'nombre'];

    public function materiales()  { return $this->hasMany(Material::class, 'id_unidad_medida'); }
    public function maquinaria()  { return $this->hasMany(Maquinaria::class, 'id_unidad_medida'); }
}