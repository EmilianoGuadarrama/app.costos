<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaChica extends Model
{
    use HasFactory;

    protected $table = 'cajas_chicas';

    protected $fillable = [
        'nombre',
        'monto_inicial',
        'saldo_actual',
        'responsable_id',
        'proyecto_id',
    ];

    public function responsable()
    {
        return $this->belongsTo(ResponsableTecnico::class, 'responsable_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoCajaChica::class);
    }
}
