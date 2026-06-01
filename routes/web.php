<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ObraController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ManoObraController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\ObraProcesoController;
use App\Http\Controllers\ObraMaterialController;
use App\Http\Controllers\CajaGeneralController;

// ── Inicio ───────────────────────────────────────────────────────────────────
Route::get('/', [ObraController::class, 'index'])->name('inicio');

// ==========================================
// 1. OBRAS
// ==========================================
Route::get('obras/papelera', [ObraController::class, 'papelera'])->name('obras.papelera');
Route::post('obras/{id}/restaurar', [ObraController::class, 'restaurar'])->name('obras.restaurar');
Route::delete('obras/{id}/force', [ObraController::class, 'forceDelete'])->name('obras.forceDelete');
Route::resource('obras', ObraController::class);

// ==========================================
// 1.5 OBRAS EN PROCESO Y ENTREGADAS
// ==========================================
Route::get('obras_proceso', [ObraProcesoController::class, 'index'])->name('obras_proceso.index');
Route::get('obras_proceso/{id}', [ObraProcesoController::class, 'show'])->name('obras_proceso.show');
Route::get('obras_proceso/{id}/fechas', [ObraProcesoController::class, 'editFechas'])->name('obras_proceso.fechas');
Route::post('obras_proceso/{id}/fechas', [ObraProcesoController::class, 'updateFechas'])->name('obras_proceso.fechas.update');
Route::post('obras_proceso/{id}/pausar', [ObraProcesoController::class, 'pausar'])->name('obras_proceso.pausar');
Route::post('obras_proceso/{id}/finalizar', [ObraProcesoController::class, 'finalizar'])->name('obras_proceso.finalizar');

Route::get('obras/{id}/materiales', [ObraMaterialController::class, 'index'])->name('obras.materiales');
Route::post('obras/{id}/materiales/comprar', [ObraMaterialController::class, 'storeCompra'])->name('obras.materiales.storeCompra');
Route::delete('obras/materiales/comprar/{id_egreso}', [ObraMaterialController::class, 'destroyCompra'])->name('obras.materiales.destroyCompra');
Route::get('api/obras/{id}/materiales-pendientes', [ObraMaterialController::class, 'apiPendientes'])->name('api.obras.materiales.pendientes');

Route::get('obras_entregadas/reporte/{id}', function($id) {
    $entregada = \App\Models\ObraEntregada::with('obraProceso.obraIniciada.datosDeObra')->findOrFail($id);
    return view('obras_entregadas.reporte', compact('entregada'));
})->name('obras_entregadas.reporte');

// ==========================================
// 2. PRESUPUESTO (asigna_conceptos + asigna_materiales + asigna_maquinaria)
// ==========================================
Route::prefix('obras/{obraId}/presupuesto')->group(function () {

    // Ver presupuesto completo por bloques
    Route::get('/', [PresupuestoController::class, 'show'])->name('obras.presupuesto');

    // Formulario agregar conceptos
    Route::get('/agregar', [PresupuestoController::class, 'create'])->name('obras.presupuesto.create');
    Route::post('/conceptos', [PresupuestoController::class, 'storeConceptos'])->name('obras.presupuesto.conceptos.store');
    Route::delete('/conceptos/{id}', [PresupuestoController::class, 'destroyConcepto'])->name('obras.presupuesto.conceptos.destroy');
    Route::patch('/conceptos/{id}', [PresupuestoController::class, 'updateConcepto'])->name('obras.presupuesto.conceptos.update');

    // Formulario agregar materiales (GET + POST separados)
    Route::get('/agregar-materiales', [PresupuestoController::class, 'createMateriales'])->name('obras.presupuesto.materiales.create');
    Route::post('/materiales', [PresupuestoController::class, 'storeMateriales'])->name('obras.presupuesto.materiales.store');
    Route::delete('/materiales/{id}', [PresupuestoController::class, 'destroyMaterial'])->name('obras.presupuesto.materiales.destroy');

    // *** FORMULARIO UNIFICADO (Conceptos + Materiales + Maquinaria) ***
    Route::get('/agregar-todo', [PresupuestoController::class, 'createUnificado'])->name('obras.presupuesto.unificado.create');
    Route::post('/agregar-todo', [PresupuestoController::class, 'storeUnificado'])->name('obras.presupuesto.unificado.store');

    // Aprobar Presupuesto
    Route::post('/aprobar', [PresupuestoController::class, 'aprobar'])->name('obras.presupuesto.aprobar');
    Route::post('/cancelar-aprobacion', [PresupuestoController::class, 'cancelarAprobacion'])->name('obras.presupuesto.cancelar_aprobacion');

    // Exportaciones
    Route::get('/export-excel', [PresupuestoController::class, 'exportExcel'])->name('obras.presupuesto.export_excel');
    Route::get('/export-pdf', [PresupuestoController::class, 'exportPdf'])->name('obras.presupuesto.export_pdf');
    Route::get('/pdf-presupuesto', [PresupuestoController::class, 'generarPresupuestoPdf'])->name('obras.presupuesto.pdf_formal');
    Route::get('/pdf-catalogo', [PresupuestoController::class, 'generarCatalogoConceptosPdf'])->name('obras.presupuesto.pdf_catalogo');

    // Edición en línea
    Route::post('/actualizar-todo', [PresupuestoController::class, 'updateAll'])->name('obras.presupuesto.updateAll');
});

// Rutas directas para editar/actualizar un ObraConcepto por su ID
Route::get('obra-conceptos/{id}/edit', [PresupuestoController::class, 'editConcepto'])->name('obra_conceptos.edit');
Route::patch('obra-conceptos/{id}', [PresupuestoController::class, 'updateConcepto'])->name('obra_conceptos.update');

// ==========================================
// 3. CATÁLOGOS
// ==========================================
Route::resource('conceptos', ConceptoController::class);
Route::resource('materiales', MaterialController::class);
Route::resource('maquinaria', MaquinariaController::class);
Route::resource('mano_obra', ManoObraController::class);
Route::resource('areas', AreaController::class);
Route::resource('unidad_medida', UnidadMedidaController::class)
    ->parameters(['unidad_medida' => 'unidad_medida']);

// ==========================================
// 4. ENTIDADES
// ==========================================
Route::resource('clientes', ClienteController::class);
// ==========================================
// Presupuestos de Proveedores
// ==========================================
Route::get('proveedores/presupuestos', [\App\Http\Controllers\PreProveedorController::class, 'index'])->name('pre_proveedores.index');
Route::post('proveedores/presupuestos', [\App\Http\Controllers\PreProveedorController::class, 'store'])->name('pre_proveedores.store');
Route::post('proveedores/presupuestos/{id}/aprobar', [\App\Http\Controllers\PreProveedorController::class, 'aprobar'])->name('pre_proveedores.aprobar');
Route::post('proveedores/presupuestos/{id}/pago', [\App\Http\Controllers\PreProveedorController::class, 'registrarPago'])->name('pre_proveedores.pago');
Route::post('proveedores/presupuestos/{id}/extras', [\App\Http\Controllers\PreProveedorController::class, 'updateExtras'])->name('pre_proveedores.updateExtras');
Route::delete('proveedores/presupuestos/{id}', [\App\Http\Controllers\PreProveedorController::class, 'destroy'])->name('pre_proveedores.destroy');
Route::post('proveedores/presupuestos/{id}/restore', [\App\Http\Controllers\PreProveedorController::class, 'restore'])->name('pre_proveedores.restore');

Route::resource('proveedores', ProveedorController::class);
Route::resource('empleados', EmpleadoController::class);

// ==========================================
// 5. FINANZAS
// ==========================================
Route::get('ingresos/pdf/{anio}/{mes}', [IngresoController::class, 'pdfMes'])->name('ingresos.pdf_mes');
Route::resource('ingresos', IngresoController::class);
Route::get('egresos/pdf/{anio}/{mes}', [EgresoController::class, 'pdfMes'])->name('egresos.pdf_mes');
Route::resource('egresos', EgresoController::class);
// Caja general — solo index y show (no hay CRUD completo)
Route::get('caja_general', [CajaGeneralController::class, 'index'])->name('caja_general.index');
Route::get('caja_general/{id}', [CajaGeneralController::class, 'show'])->name('caja_general.show');


// ==========================================
// 6. API — Autocompletado y helpers
// ==========================================
Route::get('/api/conceptos/buscar', [ConceptoController::class, 'buscar'])->name('api.conceptos.buscar');
Route::get('/api/unidad_medida/lista', [UnidadMedidaController::class, 'lista'])->name('api.unidades.lista');
Route::post('/api/unidad_medida/rapida', [UnidadMedidaController::class, 'storeRapida'])->name('api.unidades.storeRapida');
Route::get('/api/dias_inhabiles', [\App\Http\Controllers\DiasInhabilesController::class, 'index'])->name('api.dias_inhabiles.index');
Route::post('/api/dias_inhabiles', [\App\Http\Controllers\DiasInhabilesController::class, 'store'])->name('api.dias_inhabiles.store');
Route::delete('/api/dias_inhabiles/{id}', [\App\Http\Controllers\DiasInhabilesController::class, 'destroy'])->name('api.dias_inhabiles.destroy');
Route::get('/api/clientes/buscar', [ClienteController::class, 'buscar'])->name('api.clientes.buscar');

// API — Creación rápida de catálogos desde el formulario de presupuesto
Route::post('/api/areas/rapida',      [AreaController::class,      'storeRapida'])->name('api.areas.storeRapida');
Route::post('/api/bloques/rapida',    [PresupuestoController::class,'storeBloqueRapido'])->name('api.bloques.storeRapida');
Route::post('/api/materiales/rapida', [MaterialController::class,   'storeRapida'])->name('api.materiales.storeRapida');
Route::post('/api/mano_obra/rapida',  [ManoObraController::class,   'storeRapida'])->name('api.mano_obra.storeRapida');
Route::post('/api/maquinaria/rapida', [MaquinariaController::class, 'storeRapida'])->name('api.maquinaria.storeRapida');
Route::post('/api/proveedores/rapida',[ProveedorController::class,  'storeRapida'])->name('api.proveedores.storeRapida');