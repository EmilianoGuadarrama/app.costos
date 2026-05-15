<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * empleados: id, rol, descripcion, id_persona
 */
class Empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = ['rol','descripcion','id_persona'];

    public function persona()       { return $this->belongsTo(Persona::class, 'id_persona'); }
    public function obrasIniciadas(){ return $this->hasMany(ObraIniciada::class, 'encargado_id_empleado'); }
    public function ingresos()      { return $this->hasMany(IngresoTotal::class, 'id_empleado'); }

    public function getNombreAttribute(): string
    {
        return $this->persona?->nombre ?? '—';
    }
}
