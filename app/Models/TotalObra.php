<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/** total_obra: id, id_obra, total_inicial, total_iva, total_final */
class TotalObra extends Model
{
    protected $table = 'total_obra';
    protected $fillable = ['id_obra','total_inicial','total_iva','total_final'];

    public function obra() { return $this->belongsTo(ObraIniciada::class, 'id_obra'); }
}
