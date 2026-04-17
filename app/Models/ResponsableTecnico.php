<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsableTecnico extends Model
{
    use HasFactory;

    protected $table = 'responsable_tecnicos';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'cargo',
        'firma_path',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
