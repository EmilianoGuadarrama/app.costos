<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisis_pu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concepto_id')->constrained('conceptos')->cascadeOnDelete();
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->decimal('costo_directo', 14, 2)->default(0);
            $table->decimal('costo_indirecto', 14, 2)->default(0);
            $table->decimal('precio_unitario', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisis_pu');
    }
};
