<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** gastos_totales: id, id_obra, descripcion, total, porcentaje_cubierto */
class GastoTotal extends Model
{
    protected $table = 'gastos_totales';
    protected $fillable = ['id_obra','descripcion','total','porcentaje_cubierto'];

    public function obra() { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
}
