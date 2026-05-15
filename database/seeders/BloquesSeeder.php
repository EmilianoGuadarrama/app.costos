<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BloquesSeeder extends Seeder
{
    public function run(): void
    {
        // Añadir columna 'orden' si no existe (la tabla se creó desde SQL sin ella)
        if (!Schema::hasColumn('bloques', 'orden')) {
            Schema::table('bloques', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->integer('orden')->default(0)->after('descripcion');
            });
        }

        $bloques = [
            ['id' => 1,  'descripcion' => 'Preliminares',                    'orden' => 1],
            ['id' => 2,  'descripcion' => 'Albañilerías',                    'orden' => 2],
            ['id' => 3,  'descripcion' => 'Cancelería',                      'orden' => 3],
            ['id' => 4,  'descripcion' => 'Mobiliarios',                     'orden' => 4],
            ['id' => 5,  'descripcion' => 'Dirección y Administración',       'orden' => 5],
            ['id' => 6,  'descripcion' => 'Carpintería',                     'orden' => 6],
            ['id' => 7,  'descripcion' => 'Eléctrico',                       'orden' => 7],
            ['id' => 8,  'descripcion' => 'Plomería',                        'orden' => 8],
            ['id' => 9,  'descripcion' => 'Extracción',                      'orden' => 9],
            ['id' => 10, 'descripcion' => 'Acabados',                        'orden' => 10],
            ['id' => 11, 'descripcion' => 'Accesorios',                      'orden' => 11],
            ['id' => 12, 'descripcion' => 'Herrería',                        'orden' => 12],
        ];

        foreach ($bloques as $b) {
            DB::table('bloques')->updateOrInsert(
                ['id' => $b['id']],
                array_merge($b, ['updated_at' => now()])
            );
        }
    }
}
