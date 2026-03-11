<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConceptoController;

Route::get('/', fn () => view('welcome'))->name('inicio');

// Vistas principales
Route::view('/proyectos', 'proyectos.index')->name('proyectos');
Route::view('/generadores', 'generadores.index')->name('generadores');
Route::view('/materiales', 'materiales.index')->name('materiales');
Route::view('/mano-obra', 'mano_de_obras.index')->name('mano_obra');
Route::view('/maquinaria-equipo', 'maquinaria_equipos.index')->name('maquinaria_equipo');
Route::view('/indirectos', 'indirectos.index')->name('indirectos');
Route::view('/pu', 'pu.index')->name('pu');
Route::view('/presupuesto', 'presupuestos.index')->name('presupuesto');
Route::view('/reportes', 'reportes.index')->name('reportes');


// CRUD de conceptos
Route::resource('conceptos', ConceptoController::class);


// Rutas de creación
Route::view('/generadores/create', 'generadores.create')->name('generadores.create');
Route::view('/materiales/create', 'materiales.create')->name('materiales.create');
Route::view('/mano-obra/create', 'mano_de_obras.create')->name('mano_obra.create');
Route::view('/maquinaria-equipo/create', 'maquinaria_equipos.create')->name('maquinaria_equipo.create');
Route::view('/presupuesto/create', 'presupuestos.create')->name('presupuesto.create');
Route::view('/proyectos/create', 'proyectos.create')->name('proyectos.create');


// Rutas de edición
Route::view('/generadores/{id}/edit', 'generadores.edit')->name('generadores.edit');
Route::view('/materiales/{id}/edit', 'materiales.edit')->name('materiales.edit');
Route::view('/mano-obra/{id}/edit', 'mano_de_obras.edit')->name('mano_obra.edit');
Route::view('/maquinaria-equipo/{id}/edit', 'maquinaria_equipos.edit')->name('maquinaria_equipo.edit');
Route::view('/presupuesto/{id}/edit', 'presupuestos.edit')->name('presupuesto.edit');
Route::view('/proyectos/{id}/edit', 'proyectos.edit')->name('proyectos.edit');