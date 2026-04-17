<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuesto_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->foreignId('concepto_id')->constrained('conceptos')->cascadeOnDelete();
            $table->decimal('cantidad', 14, 4)->default(0);
            $table->decimal('precio_unitario', 14, 2)->default(0);
            $table->decimal('importe', 16, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuesto_detalles');
    }
};
