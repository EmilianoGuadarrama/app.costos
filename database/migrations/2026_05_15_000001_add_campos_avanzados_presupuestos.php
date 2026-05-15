<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Bloques (Preliminares, Albañilerías, Cancelería…) ──
        if (!Schema::hasTable('bloques')) {
            Schema::create('bloques', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 120);
                $table->integer('orden')->default(0);
                $table->timestamps();
            });
        }

        // ── 2. Niveles (Planta Baja, Planta Alta…) ──
        if (!Schema::hasTable('niveles')) {
            Schema::create('niveles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
                $table->string('nombre', 100);
                $table->decimal('m2', 10, 2)->nullable();
                $table->integer('orden')->default(0);
                $table->timestamps();
            });
        }

        // ── 3. Extender presupuestos ──
        Schema::table('presupuestos', function (Blueprint $table) {
            if (!Schema::hasColumn('presupuestos', 'fecha')) {
                $table->date('fecha')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('presupuestos', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('fecha');
            }
            if (!Schema::hasColumn('presupuestos', 'num_niveles')) {
                $table->unsignedTinyInteger('num_niveles')->default(1)->after('observaciones');
            }
            if (!Schema::hasColumn('presupuestos', 'total_iva')) {
                $table->decimal('total_iva', 16, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('presupuestos', 'total_final')) {
                $table->decimal('total_final', 16, 2)->default(0)->after('total_iva');
            }
        });

        // ── 4. Extender presupuesto_detalles ──
        Schema::table('presupuesto_detalles', function (Blueprint $table) {
            // Alias compatible con el controller existente
            if (!Schema::hasColumn('presupuesto_detalles', 'pu_unitario_snapshot')) {
                $table->decimal('pu_unitario_snapshot', 14, 2)->default(0)->after('precio_unitario');
            }
            if (!Schema::hasColumn('presupuesto_detalles', 'subtotal')) {
                $table->decimal('subtotal', 16, 2)->default(0)->after('pu_unitario_snapshot');
            }
            if (!Schema::hasColumn('presupuesto_detalles', 'porcentaje_iva')) {
                $table->decimal('porcentaje_iva', 5, 2)->default(16)->after('subtotal');
            }
            if (!Schema::hasColumn('presupuesto_detalles', 'iva')) {
                $table->decimal('iva', 14, 2)->default(0)->after('porcentaje_iva');
            }
            if (!Schema::hasColumn('presupuesto_detalles', 'total_final')) {
                $table->decimal('total_final', 16, 2)->default(0)->after('iva');
            }
            if (!Schema::hasColumn('presupuesto_detalles', 'bloque_id')) {
                $table->foreignId('bloque_id')->nullable()->constrained('bloques')->nullOnDelete()->after('concepto_id');
            }
            if (!Schema::hasColumn('presupuesto_detalles', 'nivel_id')) {
                $table->foreignId('nivel_id')->nullable()->constrained('niveles')->nullOnDelete()->after('bloque_id');
            }
            // Cantidad comprada y saldo de seguimiento
            if (!Schema::hasColumn('presupuesto_detalles', 'cantidad_comprada')) {
                $table->decimal('cantidad_comprada', 14, 4)->default(0)->after('total_final');
            }
            if (!Schema::hasColumn('presupuesto_detalles', 'saldo_cantidad')) {
                $table->decimal('saldo_cantidad', 14, 4)->default(0)->after('cantidad_comprada');
            }
            if (!Schema::hasColumn('presupuesto_detalles', 'saldo_monto')) {
                $table->decimal('saldo_monto', 16, 2)->default(0)->after('saldo_cantidad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('presupuesto_detalles', function (Blueprint $table) {
            $table->dropColumn([
                'pu_unitario_snapshot','subtotal','porcentaje_iva','iva','total_final',
                'bloque_id','nivel_id','cantidad_comprada','saldo_cantidad','saldo_monto',
            ]);
        });
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropColumn(['fecha','observaciones','num_niveles','total_iva','total_final']);
        });
        Schema::dropIfExists('niveles');
        Schema::dropIfExists('bloques');
    }
};
