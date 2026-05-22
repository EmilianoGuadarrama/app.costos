<?php
namespace App\Exports;

use App\Models\ObraIniciada;
use App\Models\Bloque;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PresupuestoExport implements FromView, ShouldAutoSize
{
    protected $obraId;

    public function __construct(int $obraId)
    {
        $this->obraId = $obraId;
    }

    public function view(): View
    {
        $obra = ObraIniciada::with([
            'datosDeObra', 
            'obraConceptos.concepto.unidadMedida',
            'totalBloque'
        ])->findOrFail($this->obraId);

        $bloques = Bloque::orderBy('id')->get();
        $totalesPorBloque = $obra->totalBloque->keyBy('id_bloque');

        // Reuse a simple view for Excel
        return view('obras.presupuesto_export', compact('obra', 'bloques', 'totalesPorBloque'));
    }
}
