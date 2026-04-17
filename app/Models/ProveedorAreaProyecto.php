<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorAreaProyecto extends Model
{
    use HasFactory;

    protected $table = 'proveedor_area_proyecto';

    protected $fillable = [
        'proveedor_id',
        'area_id',
        'proyecto_id',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
