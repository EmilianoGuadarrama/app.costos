<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'nombre',
        'direccion',
        'logo_path',
    ];

    public function responsablesTecnicos()
    {
        return $this->hasMany(ResponsableTecnico::class);
    }
}
