<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('inicio');

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
