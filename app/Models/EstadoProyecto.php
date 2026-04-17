<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoProyecto extends Model
{
    use HasFactory;

    protected $table = 'estados_proyecto';

    protected $fillable = [
        'nombre',
        'color',
    ];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }
}
