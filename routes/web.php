<?php

use Illuminate\Support\Facades\Route;

/* INICIO (WELCOME) */
Route::get('/', function () {
    return view('welcome');
})->name('inicio');

/* VISTAS YA HECHAS */
Route::get('/proyectos', function () {
    return view('proyecto'); // resources/views/proyecto.blade.php
})->name('proyectos');

Route::get('/conceptos', function () {
    return view('conceptos'); // resources/views/conceptos.blade.php
})->name('conceptos');

Route::get('/generadores', function () {
    return view('generadores'); // resources/views/generadores.blade.php
})->name('generadores');

/* RUTAS FALTANTES (según tu menú) -> por ahora "en construcción" */
Route::get('/materiales', function () {
    return view('materiales', ['titulo' => 'Materiales']);
})->name('materiales');

Route::get('/mano-obra', function () {
    return view('mano_obra', ['titulo' => 'Mano de Obra']);
})->name('mano_obra');

Route::get('/maquinaria-equipo', function () {
    return view('maquinaria_equipo', ['titulo' => 'Maquinaria y Equipo']);
})->name('maquinaria_equipo');

Route::get('/costos-indirectos', function () {
    return view('', ['titulo' => 'Costos Indirectos']);
})->name('indirectos');

Route::get('/pu', function () {
    return view('en_construccion', ['titulo' => 'P.U (Precio Unitario)']);
})->name('pu');

Route::get('/presupuesto', function () {
    return view('presupuesto', ['titulo' => 'Presupuesto']);
})->name('presupuesto');

Route::get('/reportes', function () {
    return view('en_construccion', ['titulo' => 'Reportes']);
})->name('reportes');
