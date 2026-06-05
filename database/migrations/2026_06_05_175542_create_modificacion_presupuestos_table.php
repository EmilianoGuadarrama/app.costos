<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modificacion_presupuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->enum('tipo', ['aditiva', 'deductiva']);
            $table->decimal('monto', 16, 2);
            $table->text('motivo');
            $table->date('fecha');
            $table->string('estado', 50)->default('borrador'); // borrador, aprobado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modificacion_presupuestos');
    }
};
