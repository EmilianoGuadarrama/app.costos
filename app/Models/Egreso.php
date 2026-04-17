<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $table = 'egresos';

    protected $fillable = [
        'proyecto_id',
        'categoria_id',
        'concepto',
        'monto',
        'fecha',
        'comprobante',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaEgreso::class, 'categoria_id');
    }
}
