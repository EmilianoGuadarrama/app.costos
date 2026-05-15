<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * personas: id, nombre, apellido_paterno, apellido_materno,
 *           telefono_1, telefono_2, email, rfc, id_direccion
 */
class Persona extends Model
{
    protected $table = 'personas';
    protected $fillable = [
        'nombre','apellido_paterno','apellido_materno',
        'telefono_1','telefono_2','email','rfc','id_direccion',
    ];

    public function direccion()  { return $this->belongsTo(Direccion::class, 'id_direccion'); }
    public function cliente()    { return $this->hasOne(Cliente::class, 'id_persona'); }
    public function proveedor()  { return $this->hasOne(Proveedor::class, 'id_persona'); }
    public function empleado()   { return $this->hasOne(Empleado::class, 'id_persona'); }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}
