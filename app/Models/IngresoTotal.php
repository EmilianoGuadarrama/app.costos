<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * ingresos_totales: id, concepto, id_empleado, id_obra, fecha,
 *   id_total_obra_o_presupuesto, monto_dado, saldo_cubierto, porcentaje_cubierto
 */
class IngresoTotal extends Model
{
    protected $table = 'ingresos_totales';
    protected $fillable = [
        'concepto','id_empleado','id_obra','fecha',
        'id_total_obra_o_presupuesto','monto_dado','saldo_cubierto','porcentaje_cubierto',
    ];
    protected $casts = ['fecha' => 'date'];

    public function empleado() { return $this->belongsTo(Empleado::class, 'id_empleado'); }
    public function obra()     { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
}
