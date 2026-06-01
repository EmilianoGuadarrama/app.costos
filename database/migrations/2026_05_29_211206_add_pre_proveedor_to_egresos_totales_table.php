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
        Schema::table('egresos_totales', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pre_proveedor')->nullable()->after('id_material');
            $table->decimal('monto_material', 12, 2)->default(0)->after('id_pre_proveedor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('egresos_totales', function (Blueprint $table) {
            $table->dropColumn(['id_pre_proveedor', 'monto_material']);
        });
    }
};
