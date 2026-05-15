<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** total_bloque: id, id_bloque, id_obra, total, iva, total_final */
class TotalBloque extends Model
{
    protected $table = 'total_bloque';
    protected $fillable = ['id_bloque','id_obra','total','iva','total_final'];

    public function bloque() { return $this->belongsTo(Bloque::class, 'id_bloque'); }
    public function obra()   { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
}
