<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\GeneradorController;
use App\Http\Controllers\UnidadMedidaController;

// =========================
// INICIO
// =========================
Route::get('/', fn () => view('welcome'))->name('inicio');


// =========================
// VISTAS PRINCIPALES
// =========================

Route::view('/proyectos', 'proyectos.index')->name('proyectos');
Route::view('/conceptos', 'conceptos.index')->name('conceptos');
Route::view('/generadores', 'generadores.index')->name('generadores');
Route::view('/materiales', 'materiales.index')->name('materiales');
Route::view('/mano-obra', 'mano_de_obras.index')->name('mano_obra');
Route::view('/maquinaria-equipo', 'maquinaria_equipos.index')->name('maquinaria_equipo');
Route::view('/indirectos', 'indirectos.index')->name('indirectos');
Route::view('/pu', 'pu.index')->name('pu');
Route::view('/presupuesto', 'presupuestos.index')->name('presupuesto');
Route::view('/reportes', 'reportes.index')->name('reportes');
Route::view('/partidas', 'partidas.index')->name('partidas');


// =========================
// CRUD (CONTROLADORES)
// =========================

Route::resource('conceptos', ConceptoController::class);
Route::resource('generadores', GeneradorController::class);
Route::resource('unidad_medida', UnidadMedidaController::class);


// =========================
// CREATE (SOLO VISTAS)
// =========================

Route::view('/proyectos/create', 'proyectos.create')->name('proyectos.create');
Route::view('/conceptos/create', 'conceptos.create')->name('conceptos.create');
Route::view('/generadores/create', 'generadores.create')->name('generadores.create');
Route::view('/materiales/create', 'materiales.create')->name('materiales.create');
Route::view('/mano-obra/create', 'mano_de_obras.create')->name('mano_obra.create');
Route::view('/maquinaria-equipo/create', 'maquinaria_equipos.create')->name('maquinaria_equipo.create');
Route::view('/presupuesto/create', 'presupuestos.create')->name('presupuesto.create');
Route::view('/partidas/create', 'partidas.create')->name('partidas.create');


// =========================
// EDIT (SOLO VISTAS)
// =========================

Route::view('/proyectos/{id}/edit', 'proyectos.edit')->name('proyectos.edit');
Route::view('/conceptos/{id}/edit', 'conceptos.edit')->name('conceptos.edit');
Route::view('/generadores/{id}/edit', 'generadores.edit')->name('generadores.edit');
Route::view('/materiales/{id}/edit', 'materiales.edit')->name('materiales.edit');
Route::view('/mano-obra/{id}/edit', 'mano_de_obras.edit')->name('mano_obra.edit');
Route::view('/maquinaria-equipo/{id}/edit', 'maquinaria_equipos.edit')->name('maquinaria_equipo.edit');
Route::view('/presupuesto/{id}/edit', 'presupuestos.edit')->name('presupuesto.edit');
Route::view('/partidas/{id}/edit', 'partidas.edit')->name('partidas.edit');

Route::get('/', fn () => view('welcome'))->name('inicio');



/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('inicio');

/*
|--------------------------------------------------------------------------
| PROYECTOS
|--------------------------------------------------------------------------
*/

Route::get('/proyectos', function () {
    return view('proyectos.index');
})->name('proyectos');

Route::get('/proyectos/create', function () {
    return view('proyectos.create');
})->name('proyectos.create');

Route::get('/proyectos/{id}/edit', function ($id) {
    return view('proyectos.edit');
})->name('proyectos.edit');

Route::get('/proyectos/{id}', function ($id) {
    return view('proyectos.show');
})->name('proyectos.show');

/*
|--------------------------------------------------------------------------
| UNIDAD DE MEDIDA
|--------------------------------------------------------------------------
*/
Route::get('/unidad-medida', function () {
    $unidades = [];
    return view('unidad_medida.index', compact('unidades'));
})->name('unidad_medida');

Route::get('/unidad-medida/create', function () {
    return view('unidad_medida.create');
})->name('unidad_medida.create');

Route::get('/unidad-medida/{id}/edit', function ($id) {
    return view('unidad_medida.edit');
})->name('unidad_medida.edit');

Route::get('/unidad-medida/{id}', function ($id) {
    return view('unidad_medida.show');
})->name('unidad_medida.show');
/*
|--------------------------------------------------------------------------
| CONCEPTOS
|--------------------------------------------------------------------------
*/

Route::get('/conceptos', function () {
    $conceptos = [];
    return view('conceptos.index', compact('conceptos'));
})->name('conceptos');

Route::get('/conceptos/create', function () {
    return view('conceptos.create');
})->name('conceptos.create');

Route::get('/conceptos/{id}/edit', function ($id) {
    return view('conceptos.edit');
})->name('conceptos.edit');

Route::get('/conceptos/{id}', function ($id) {
    return view('conceptos.show');
})->name('conceptos.show');

/*
|--------------------------------------------------------------------------
| GENERADORES
|--------------------------------------------------------------------------
*/

Route::get('/generadores', function () {
    $generadores = [];
    return view('generadores.index', compact('generadores'));
})->name('generadores');

Route::get('/generadores/create', function () {
    return view('generadores.create');
})->name('generadores.create');

Route::get('/generadores/{id}/edit', function ($id) {
    return view('generadores.edit');
})->name('generadores.edit');

Route::get('/generadores/{id}', function ($id) {
    return view('generadores.show');
})->name('generadores.show');

/*
|--------------------------------------------------------------------------
| MATERIALES
|--------------------------------------------------------------------------
*/

Route::get('/materiales', function () {
    $materiales = [];
    return view('materiales.index', compact('materiales'));
})->name('materiales');

Route::get('/materiales/create', function () {
    return view('materiales.create');
})->name('materiales.create');

Route::get('/materiales/{id}/edit', function ($id) {
    return view('materiales.edit');
})->name('materiales.edit');

Route::get('/materiales/{id}', function ($id) {
    return view('materiales.show');
})->name('materiales.show');

/*
|--------------------------------------------------------------------------
| MANO DE OBRA
|--------------------------------------------------------------------------
*/

Route::get('/mano-obra', function () {
    $manoDeObras = [];
    return view('mano_de_obras.index', compact('manoDeObras'));
})->name('mano_obra');

Route::get('/mano-obra/create', function () {
    return view('mano_de_obras.create');
})->name('mano_obra.create');

Route::get('/mano-obra/{id}/edit', function ($id) {
    return view('mano_de_obras.edit');
})->name('mano_obra.edit');

Route::get('/mano-obra/{id}', function ($id) {
    return view('mano_de_obras.show');
})->name('mano_obra.show');

/*
|--------------------------------------------------------------------------
| MAQUINARIA Y EQUIPO
|--------------------------------------------------------------------------
*/

Route::get('/maquinaria-equipo', function () {
    $maquinarias = [];
    return view('maquinaria_equipos.index', compact('maquinarias'));
})->name('maquinaria_equipo');

Route::get('/maquinaria-equipo/create', function () {
    return view('maquinaria_equipos.create');
})->name('maquinaria_equipo.create');

Route::get('/maquinaria-equipo/{id}/edit', function ($id) {
    return view('maquinaria_equipos.edit');
})->name('maquinaria_equipo.edit');

Route::get('/maquinaria-equipo/{id}', function ($id) {
    return view('maquinaria_equipos.show');
})->name('maquinaria_equipo.show');

/*
|--------------------------------------------------------------------------
| INDIRECTOS
|--------------------------------------------------------------------------
*/

Route::get('/indirectos', function () {
    return view('indirectos.index');
})->name('indirectos');

Route::get('/indirectos/create', function () {
    return view('indirectos.create');
})->name('indirectos.create');

Route::get('/indirectos/{id}/edit', function ($id) {
    return view('indirectos.edit');
})->name('indirectos.edit');

Route::get('/indirectos/{id}', function ($id) {
    return view('indirectos.show');
})->name('indirectos.show');

/*
|--------------------------------------------------------------------------
| P.U
|--------------------------------------------------------------------------
*/

Route::get('/pu', function () {
    return view('pu.index');
})->name('pu');

Route::get('/pu/create', function () {
    return view('pu.create');
})->name('pu.create');

Route::get('/pu/{id}/edit', function ($id) {
    return view('pu.edit');
})->name('pu.edit');

Route::get('/pu/{id}', function ($id) {
    return view('pu.show');
})->name('pu.show');

/*
|--------------------------------------------------------------------------
| PRESUPUESTO
|--------------------------------------------------------------------------
*/

Route::get('/presupuesto', function () {
    $presupuestos = [];
    return view('presupuestos.index', compact('presupuestos'));
})->name('presupuesto');

Route::get('/presupuesto/create', function () {
    return view('presupuestos.create');
})->name('presupuesto.create');

Route::get('/presupuesto/{id}/edit', function ($id) {
    return view('presupuestos.edit');
})->name('presupuesto.edit');

Route::get('/presupuesto/{id}', function ($id) {
    return view('presupuestos.show');
})->name('presupuesto.show');

/*
|--------------------------------------------------------------------------
| REPORTES
|--------------------------------------------------------------------------
*/

Route::get('/reportes', function () {
    return view('reportes.index');
})->name('reportes');

Route::get('/reportes/create', function () {
    return view('reportes.create');
})->name('reportes.create');

Route::get('/reportes/{id}/edit', function ($id) {
    return view('reportes.edit');
})->name('reportes.edit');

Route::get('/reportes/{id}', function ($id) {
    return view('reportes.show');
})->name('reportes.show');
