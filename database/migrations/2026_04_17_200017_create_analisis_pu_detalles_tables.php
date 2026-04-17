<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Materiales del análisis PU
        Schema::create('analisis_pu_materiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analisis_pu_id')->constrained('analisis_pu')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materiales')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 4)->default(0);
            $table->decimal('costo', 14, 2)->default(0);
            $table->timestamps();
        });

        // Mano de obra del análisis PU
        Schema::create('analisis_pu_mano_obra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analisis_pu_id')->constrained('analisis_pu')->cascadeOnDelete();
            $table->foreignId('mano_obra_id')->constrained('mano_obra')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 4)->default(0);
            $table->decimal('costo', 14, 2)->default(0);
            $table->timestamps();
        });

        // Maquinaria del análisis PU
        Schema::create('analisis_pu_maquinaria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analisis_pu_id')->constrained('analisis_pu')->cascadeOnDelete();
            $table->foreignId('maquinaria_equipo_id')->constrained('maquinaria_equipos')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 4)->default(0);
            $table->decimal('costo', 14, 2)->default(0);
            $table->timestamps();
        });

        // Indirectos del análisis PU
        Schema::create('analisis_pu_indirectos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analisis_pu_id')->constrained('analisis_pu')->cascadeOnDelete();
            $table->foreignId('indirecto_id')->constrained('indirectos')->cascadeOnDelete();
            $table->decimal('monto', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisis_pu_indirectos');
        Schema::dropIfExists('analisis_pu_maquinaria');
        Schema::dropIfExists('analisis_pu_mano_obra');
        Schema::dropIfExists('analisis_pu_materiales');
    }
};
