<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteGenerado extends Model
{
    use HasFactory;

    protected $table = 'reportes';

    protected $fillable = [
        'presupuesto_id',
        'nombre',
        'tipo_salida',
        'ruta_archivo',
        'fecha_generacion',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }
}
