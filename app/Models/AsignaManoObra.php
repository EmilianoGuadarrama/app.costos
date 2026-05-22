<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AsignaManoObra extends Model
{
    protected $table = 'asigna_mano_obra';
    protected $fillable = [
        'id_obra_concepto',
        'id_mano_obra',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'porcentaje_iva',
        'iva',
        'total_final',
    ];

    public function obraConcepto() { return $this->belongsTo(ObraConcepto::class, 'id_obra_concepto'); }
    public function manoObra()     { return $this->belongsTo(ManoObra::class, 'id_mano_obra'); }
}
