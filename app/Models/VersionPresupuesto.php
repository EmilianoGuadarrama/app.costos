<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VersionPresupuesto extends Model
{
    protected $table = 'versiones_presupuesto';

    protected $fillable = [
        'id_obra',
        'numero_version',
        'es_activa',
        'motivo_cambio',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraIniciada::class, 'id_obra');
    }
    public function getDiffConAnterior()
    {
        if ($this->numero_version <= 1) return [];

        $versionAnterior = $this->numero_version - 1;
        
        $conceptosActuales = ObraConcepto::with('concepto')
            ->where('id_obra', $this->id_obra)
            ->where('version', $this->numero_version)
            ->get();
            
        $conceptosAnteriores = ObraConcepto::with('concepto')
            ->where('id_obra', $this->id_obra)
            ->where('version', $versionAnterior)
            ->get();

        $diff = [];

        $keyFn = function($c) {
            return $c->id_concepto . '-' . $c->id_nivel . '-' . $c->id_bloque . '-' . $c->id_area;
        };

        $dictAnterior = $conceptosAnteriores->keyBy($keyFn);
        $dictActual = $conceptosActuales->keyBy($keyFn);

        // Conceptos Nuevos o Modificados
        foreach ($conceptosActuales as $actual) {
            $key = $keyFn($actual);
            $desc = $actual->concepto ? $actual->concepto->descripcion : 'Concepto (ID '.$actual->id_concepto.')';
            
            if (!$dictAnterior->has($key)) {
                $diff[] = "<strong>Agregado:</strong> {$desc} | Costo total: $ " . number_format($actual->total_final, 2);
            } else {
                $anterior = $dictAnterior->get($key);
                if (abs($actual->total_final - $anterior->total_final) > 0.01) {
                    $diff[] = "<strong>Modificado:</strong> {$desc} | Costo pasó de $ " . number_format($anterior->total_final, 2) . " a $ " . number_format($actual->total_final, 2);
                }
            }
        }

        // Conceptos Eliminados
        foreach ($conceptosAnteriores as $anterior) {
            $key = $keyFn($anterior);
            $desc = $anterior->concepto ? $anterior->concepto->descripcion : 'Concepto (ID '.$anterior->id_concepto.')';
            
            if (!$dictActual->has($key)) {
                $diff[] = "<strong>Eliminado:</strong> {$desc} | Ahorro de $ " . number_format($anterior->total_final, 2);
            }
        }

        if (empty($diff)) {
            $diff[] = "<em>Se creó la versión pero sin cambios directos en montos o conceptos.</em>";
        }

        return $diff;
    }
}
