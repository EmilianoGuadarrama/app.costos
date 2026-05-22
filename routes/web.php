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

    // Exportaciones
    Route::get('/export-excel', [PresupuestoController::class, 'exportExcel'])->name('obras.presupuesto.export_excel');
    Route::get('/export-pdf', [PresupuestoController::class, 'exportPdf'])->name('obras.presupuesto.export_pdf');

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
Route::resource('proveedores', ProveedorController::class);
Route::resource('empleados', EmpleadoController::class);

// ==========================================
// 5. FINANZAS
// ==========================================
Route::resource('ingresos', IngresoController::class);
Route::resource('egresos', EgresoController::class);
// Caja general — solo index y show (no hay CRUD completo)
Route::get('caja_general', [CajaGeneralController::class, 'index'])->name('caja_general.index');
Route::get('caja_general/{id}', [CajaGeneralController::class, 'show'])->name('caja_general.show');

// ==========================================
// 6. API — Autocompletado y helpers
// ==========================================
Route::get('/api/conceptos/buscar', [ConceptoController::class, 'buscar'])->name('api.conceptos.buscar');
Route::get('/api/unidad_medida/lista', [\App\Http\Controllers\UnidadMedidaController::class, 'lista'])->name('api.unidades.lista');
Route::post('/api/unidad_medida/rapida', [\App\Http\Controllers\UnidadMedidaController::class, 'storeRapida'])->name('api.unidades.storeRapida');
Route::get('/api/dias_inhabiles', [\App\Http\Controllers\DiasInhabilesController::class, 'index'])->name('api.dias_inhabiles.index');
Route::post('/api/dias_inhabiles', [\App\Http\Controllers\DiasInhabilesController::class, 'store'])->name('api.dias_inhabiles.store');
Route::delete('/api/dias_inhabiles/{id}', [\App\Http\Controllers\DiasInhabilesController::class, 'destroy'])->name('api.dias_inhabiles.destroy');
Route::get('/api/clientes/buscar', [\App\Http\Controllers\ClienteController::class, 'buscar'])->name('api.clientes.buscar');