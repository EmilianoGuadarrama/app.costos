<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maquinaria_equipos', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 50)->unique();
            $table->string('equipo', 150);
            $table->foreignId('unidad_medida_id')->constrained('unidades_medida');
            $table->decimal('costo_por_hora', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maquinaria_equipos');
    }
};
