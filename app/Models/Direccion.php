<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * direcciones: id, calle_y_numero, colonia, delegacion, id_estado, codigo_postal
 */
class Direccion extends Model
{
    protected $table = 'direcciones';
    protected $fillable = ['calle_y_numero','colonia','delegacion','id_estado','codigo_postal'];

    public function estado() { return $this->belongsTo(Estado::class, 'id_estado'); }
}
