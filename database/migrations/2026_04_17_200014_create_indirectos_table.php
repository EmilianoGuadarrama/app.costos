<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indirectos', function (Blueprint $table) {
            $table->id();
            $table->string('concepto', 200);
            $table->decimal('porcentaje', 8, 4)->default(0);
            $table->string('descripcion', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indirectos');
    }
};
