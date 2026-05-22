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

    public function obraConceptos()  { return $this->hasMany(ObraConcepto::class, 'id_bloque'); }
    public function totalBloque()      { return $this->hasMany(TotalBloque::class, 'id_bloque'); }
}
