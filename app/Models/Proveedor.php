<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * proveedores: id, id_persona, empresa
 */
class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $fillable = ['id_persona','empresa'];

    public function persona()       { return $this->belongsTo(Persona::class, 'id_persona'); }
    public function preProveedores(){ return $this->hasMany(PreProveedor::class, 'id_proveedor'); }
    public function preMateriales() { return $this->hasMany(PreMaterial::class, 'id_proveedor'); }

    public function getNombreAttribute(): string
    {
        return $this->empresa ?? $this->persona?->nombre ?? '—';
    }
}
