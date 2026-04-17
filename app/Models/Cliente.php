<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'tipo_persona',
        'nombre',
        'razon_social',
        'rfc',
        'direccion',
        'telefono',
        'correo',
    ];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }
}
