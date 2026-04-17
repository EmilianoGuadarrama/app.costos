<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('responsable_tecnico_id')->nullable()->constrained('responsable_tecnicos')->nullOnDelete();
            $table->foreignId('estado_proyecto_id')->constrained('estados_proyecto');
            $table->string('nombre', 150);
            $table->string('ubicacion', 200)->nullable();
            $table->string('tipo_obra', 120)->nullable();
            $table->decimal('superficie_terreno', 12, 2)->nullable();
            $table->string('tipo_uso', 120)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->string('duracion_estimada', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
