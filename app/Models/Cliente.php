<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * clientes: id, id_persona, id_direccion_fiscal,
 *           nombre_o_razon_social, cuenta_catastral, uso_suelo
 */
class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = [
        'id_persona','id_direccion_fiscal',
        'nombre_o_razon_social','cuenta_catastral','uso_suelo',
    ];

    public function persona()          { return $this->belongsTo(Persona::class, 'id_persona'); }
    public function direccionFiscal()  { return $this->belongsTo(Direccion::class, 'id_direccion_fiscal'); }

    /** Nombre display: primero nombre_o_razon_social, luego persona.nombre */
    public function getNombreAttribute(): string
    {
        return $this->nombre_o_razon_social
            ?? $this->persona?->nombre
            ?? '—';
    }

    public function getEmailAttribute(): ?string
    {
        return $this->persona?->email;
    }

    public function getTelefonoAttribute(): ?string
    {
        return $this->persona?->telefono_1;
    }
}
