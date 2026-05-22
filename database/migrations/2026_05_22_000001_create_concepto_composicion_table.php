<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concepto_composicion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('concepto_id');
            $table->enum('tipo', ['material', 'maquinaria', 'mano_obra']);
            $table->unsignedBigInteger('referencia_id');
            $table->string('descripcion_referencia', 255);
            $table->decimal('cantidad', 14, 4)->default(1);
            $table->string('unidad', 50)->nullable();
            $table->timestamps();

            // FK sin restringir tipo exacto (compatible con INT y BIGINT UNSIGNED)
            $table->foreign('concepto_id')->references('id')->on('conceptos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concepto_composicion');
    }
};
