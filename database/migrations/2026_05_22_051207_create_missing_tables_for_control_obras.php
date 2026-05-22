<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. pre_proveedores
        if (!Schema::hasTable('pre_proveedores')) {
            Schema::create('pre_proveedores', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_obra')->nullable();
                $table->unsignedBigInteger('id_proveedor')->nullable();
                $table->unsignedBigInteger('id_area')->nullable();
                $table->decimal('presupuesto', 16, 2)->default(0);
                $table->decimal('extras', 16, 2)->default(0);
                $table->decimal('total', 16, 2)->default(0);
                $table->decimal('saldo', 16, 2)->default(0);
                $table->decimal('pagado', 16, 2)->default(0);
                $table->timestamps();
            });
        }

        // 2. concepto_composicion
        if (!Schema::hasTable('concepto_composicion')) {
            Schema::create('concepto_composicion', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('concepto_id');
                $table->enum('tipo', ['material', 'maquinaria', 'mano_obra']);
                $table->unsignedBigInteger('referencia_id');
                $table->string('descripcion_referencia', 255);
                $table->decimal('cantidad', 14, 4)->default(1);
                $table->string('unidad', 50)->nullable();
                $table->timestamps();
            });
        }

        // 3. dias_inhabiles
        if (!Schema::hasTable('dias_inhabiles')) {
            Schema::create('dias_inhabiles', function (Blueprint $table) {
                $table->id();
                $table->date('fecha')->unique();
                $table->string('descripcion', 150)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dias_inhabiles');
        Schema::dropIfExists('concepto_composicion');
        Schema::dropIfExists('pre_proveedores');
    }
};
