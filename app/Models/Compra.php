<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';

    protected $fillable = [
        'proyecto_id',
        'proveedor_id',
        'area_id',
        'fecha_compra',
        'estado',
        'factura',
        'total',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }
}
