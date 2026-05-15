<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** pre_proveedores: id, id_obra, id_proveedor, id_area, presupuesto, extras, total, saldo, pagado */
class PreProveedor extends Model
{
    protected $table = 'pre_proveedores';
    protected $fillable = ['id_obra','id_proveedor','id_area','presupuesto','extras','total','saldo','pagado'];

    public function obra()      { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
    public function proveedor() { return $this->belongsTo(Proveedor::class, 'id_proveedor'); }
    public function area()      { return $this->belongsTo(Area::class, 'id_area'); }
}
