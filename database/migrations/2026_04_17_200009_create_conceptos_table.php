<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conceptos', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 50);
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->string('partida', 100)->nullable();
            $table->string('subpartida', 100)->nullable();
            $table->string('descripcion', 255);
            $table->foreignId('unidad_medida_id')->constrained('unidades_medida');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conceptos');
    }
};
