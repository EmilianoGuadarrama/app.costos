<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'cliente_id',
        'responsable_tecnico_id',
        'estado_proyecto_id',
        'nombre',
        'ubicacion',
        'tipo_obra',
        'superficie_terreno',
        'tipo_uso',
        'fecha_inicio',
        'duracion_estimada',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function responsableTecnico()
    {
        return $this->belongsTo(ResponsableTecnico::class);
    }

    public function estado()
    {
        return $this->belongsTo(EstadoProyecto::class, 'estado_proyecto_id');
    }

    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class);
    }
}
