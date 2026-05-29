<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

/** pre_proveedores: id, id_obra, id_proveedor, id_area, presupuesto, extras, total, saldo, pagado, estado */
class PreProveedor extends Model
{
    use SoftDeletes, Prunable;
    protected $table = 'pre_proveedores';
    protected $fillable = ['id_obra','id_proveedor','id_area','presupuesto','extras','total','saldo','pagado','estado'];

    public function obra()      { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
    public function proveedor() { return $this->belongsTo(Proveedor::class, 'id_proveedor'); }
    public function area()      { return $this->belongsTo(Area::class, 'id_area'); }

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subDays(30));
    }
}
