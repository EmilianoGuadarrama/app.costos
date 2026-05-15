<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** egresos_totales: id, id_area, id_persona, fecha, concepto, pago, id_obra, categoria */
class EgresoTotal extends Model
{
    protected $table = 'egresos_totales';
    protected $fillable = ['id_area','id_persona','fecha','concepto','pago','id_obra','categoria'];
    protected $casts = ['fecha' => 'date'];

    public function area()    { return $this->belongsTo(Area::class, 'id_area'); }
    public function persona() { return $this->belongsTo(Persona::class, 'id_persona'); }
    public function obra()    { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
}
