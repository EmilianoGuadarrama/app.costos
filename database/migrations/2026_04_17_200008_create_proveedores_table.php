<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('contacto', 150)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 120)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->string('tipo', 80)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
