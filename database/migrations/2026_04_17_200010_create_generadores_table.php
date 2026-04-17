<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concepto_id')->constrained('conceptos')->cascadeOnDelete();
            $table->string('localizacion', 150)->nullable();
            $table->string('ejes', 80)->nullable();
            $table->decimal('no_piezas', 12, 2)->default(0);
            $table->decimal('largo', 12, 4)->default(0);
            $table->decimal('ancho', 12, 4)->default(0);
            $table->decimal('alto', 12, 4)->default(0);
            $table->decimal('resultado', 14, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generadores');
    }
};
