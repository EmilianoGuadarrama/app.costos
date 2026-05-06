<?php

use Illuminate\Support\Facades\Route;

// Controladores Base
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CategoriaEgresoController;

// Controladores de Elementos
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\GeneradorController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ManoObraController;
use App\Http\Controllers\MaquinariaEquipoController;
use App\Http\Controllers\IndirectoController;

// Controladores de Entidades Principales
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ResponsableTecnicoController;
use App\Http\Controllers\EstadoProyectoController;

// Controladores de Proyectos y Presupuestos
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\AnalisisPuController;
use App\Http\Controllers\ReporteGeneradoController;

// Controladores de Movimientos Financieros
use App\Http\Controllers\CajaChicaController;
use App\Http\Controllers\MovimientoCajaChicaController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\CompraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se definen todas las rutas para la aplicación "App Costos".
| Hemos centralizado todo utilizando Resource Controllers para
| mantener una estructura MVC limpia y predecible.
|
*/

// Ruta principal corregida
Route::get('/', [ProyectoController::class, 'create'])->name('inicio');

// ==========================================
// 1. CONFIGURACIONES BASE
// ==========================================
Route::resource('unidad_medida', UnidadMedidaController::class)->parameters(['unidad_medida' => 'unidad_medida']);
Route::resource('areas', AreaController::class);
Route::resource('categorias_egreso', CategoriaEgresoController::class);

// ==========================================
// 2. CATÁLOGOS Y ELEMENTOS
// ==========================================
Route::resource('conceptos', ConceptoController::class);
Route::resource('materiales', MaterialController::class);
Route::resource('mano_obra', ManoObraController::class)->parameters(['mano_obra' => 'mano_obra']);
Route::resource('maquinaria_equipo', MaquinariaEquipoController::class)->parameters(['maquinaria_equipo' => 'maquinaria_equipo']);
Route::resource('indirectos', IndirectoController::class);
Route::resource('generadores', GeneradorController::class);

// ==========================================
// 3. ENTIDADES PRINCIPALES
// ==========================================
Route::resource('clientes', ClienteController::class);
Route::resource('empresas', EmpresaController::class);
Route::resource('proveedores', ProveedorController::class);
Route::resource('responsables_tecnicos', ResponsableTecnicoController::class);
Route::resource('estados_proyecto', EstadoProyectoController::class);

// ==========================================
// 4. PROYECTOS Y PRESUPUESTOS
// ==========================================
Route::resource('proyectos', ProyectoController::class);
Route::resource('presupuestos', PresupuestoController::class);
Route::resource('analisis_pu', AnalisisPuController::class)->parameters(['analisis_pu' => 'analisis_pu']);
Route::resource('reportes', ReporteGeneradoController::class);

// ==========================================
// 5. MOVIMIENTOS FINANCIEROS Y CAJA
// ==========================================
Route::resource('cajas_chicas', CajaChicaController::class);
Route::resource('movimientos_caja_chica', MovimientoCajaChicaController::class)->parameters(['movimientos_caja_chica' => 'movimientos_caja_chica']);
Route::resource('ingresos', IngresoController::class);
Route::resource('egresos', EgresoController::class);
Route::resource('compras', CompraController::class);