<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConceptoComposicion extends Model
{
    protected $table = 'concepto_composicion';

    protected $fillable = [
        'concepto_id',
        'tipo',
        'referencia_id',
        'descripcion_referencia',
        'cantidad',
        'unidad',
    ];

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }

    /** Devuelve el modelo relacionado según el tipo */
    public function referenciaModel(): ?Model
    {
        return match($this->tipo) {
            'material'   => Material::find($this->referencia_id),
            'maquinaria' => Maquinaria::find($this->referencia_id),
            'mano_obra'  => ManoObra::find($this->referencia_id),
            default      => null,
        };
    }
}
