<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cajas chicas
        Schema::create('cajas_chicas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->decimal('monto_inicial', 14, 2)->default(0);
            $table->decimal('saldo_actual', 14, 2)->default(0);
            $table->foreignId('responsable_id')->constrained('responsable_tecnicos');
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->timestamps();
        });

        // Movimientos de caja chica
        Schema::create('movimientos_caja_chica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_chica_id')->constrained('cajas_chicas')->cascadeOnDelete();
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->decimal('monto', 14, 2);
            $table->string('concepto', 255);
            $table->date('fecha');
            $table->string('comprobante', 255)->nullable();
            $table->timestamps();
        });

        // Ingresos
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('concepto', 255);
            $table->decimal('monto', 14, 2);
            $table->date('fecha');
            $table->string('comprobante', 255)->nullable();
            $table->timestamps();
        });

        // Egresos
        Schema::create('egresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias_egreso');
            $table->string('concepto', 255);
            $table->decimal('monto', 14, 2);
            $table->date('fecha');
            $table->string('comprobante', 255)->nullable();
            $table->timestamps();
        });

        // Compras
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->foreignId('area_id')->constrained('areas');
            $table->date('fecha_compra');
            $table->string('estado', 50)->default('pendiente');
            $table->string('factura', 100)->nullable();
            $table->decimal('total', 14, 2)->default(0);
            $table->timestamps();
        });

        // Detalle de compras
        Schema::create('compra_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->cascadeOnDelete();
            $table->foreignId('concepto_id')->constrained('conceptos');
            $table->decimal('cantidad', 12, 4);
            $table->decimal('precio_unitario', 14, 2);
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();
        });

        // Proveedor-Área-Proyecto (pivote)
        Schema::create('proveedor_area_proyecto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->cascadeOnDelete();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedor_area_proyecto');
        Schema::dropIfExists('compra_detalles');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('egresos');
        Schema::dropIfExists('ingresos');
        Schema::dropIfExists('movimientos_caja_chica');
        Schema::dropIfExists('cajas_chicas');
    }
};
