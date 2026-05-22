<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AsignaMaterial extends Model
{
    protected $table = 'asigna_materiales';
    protected $fillable = [
        'id_obra_concepto',
        'id_material',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'porcentaje_iva',
        'iva',
        'total_final',
    ];

    public function obraConcepto() { return $this->belongsTo(ObraConcepto::class, 'id_obra_concepto'); }
    public function material()     { return $this->belongsTo(Material::class, 'id_material'); }
}
