<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mano_obra', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 50)->unique();
            $table->string('categoria', 150);
            $table->foreignId('unidad_medida_id')->constrained('unidades_medida');
            $table->decimal('salario_unitario', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mano_obra');
    }
};
