<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

/**
 * obras_iniciadas: id, id_datos_de_obra, encargado_id_empleado, fecha_inicio,
 *                  duracion, precio_por_m2_estimado, total_de_obra_estimado,
 *                  total_presupuestado, total_por_m2
 *
 * Esta es la entidad central de "proyecto/obra".
 */
class ObraIniciada extends Model
{
    use SoftDeletes, Prunable;

    protected $table = 'obras_iniciadas';
    protected $fillable = [
        'id_datos_de_obra','encargado_id_empleado','id_cliente','fecha_inicio','duracion',
        'precio_por_m2_estimado','total_de_obra_estimado','total_presupuestado','total_por_m2',
    ];

    protected $casts = ['fecha_inicio' => 'date'];

    public function datosDeObra()    { return $this->belongsTo(DatosDeObra::class, 'id_datos_de_obra'); }
    public function encargado()      { return $this->belongsTo(Empleado::class, 'encargado_id_empleado'); }
    public function cliente()        { return $this->belongsTo(Cliente::class, 'id_cliente'); }
    public function niveles()        { return $this->hasMany(Nivel::class, 'id_obra'); }
    public function obraProceso()    { return $this->hasOne(ObraProceso::class, 'id_obra'); }
    public function obraConceptos(){ return $this->hasMany(ObraConcepto::class, 'id_obra'); }
    public function preProveedores() { return $this->hasMany(PreProveedor::class, 'id_obra'); }
    public function preMateriales()  { return $this->hasMany(PreMaterial::class, 'id_obra'); }
    public function totalBloque()    { return $this->hasMany(TotalBloque::class, 'id_obra'); }
    public function totalObra()      { return $this->hasOne(TotalObra::class, 'id_obra'); }
    public function cajaGeneral()    { return $this->hasOne(CajaGeneral::class, 'id_obra'); }
    public function egresos()        { return $this->hasMany(EgresoTotal::class, 'id_obra'); }
    public function ingresos()       { return $this->hasMany(IngresoTotal::class, 'id_obra'); }

    /** Nombre de la obra (viene de datos_de_obra) */
    public function getNombreAttribute(): string
    {
        return $this->datosDeObra?->nombre ?? "Obra #{$this->id}";
    }

    /** Días transcurridos desde inicio */
    public function getDiasTranscurridosAttribute(): int
    {
        if (!$this->fecha_inicio) return 0;
        return (int) $this->fecha_inicio->diffInDays(now(), false);
    }

    /** Días que faltan */
    public function getDiasFaltanAttribute(): ?int
    {
        $dur = (int) $this->duracion;
        if (!$dur || !$this->fecha_inicio) return null;
        return $dur - $this->dias_transcurridos;
    }

    /**
     * Get the prunable query.
     */
    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subDays(30));
    }
}
