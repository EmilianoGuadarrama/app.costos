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
            $table->decimal('cantidad_material', 10, 2)->nullable()->after('id_material');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('egresos_totales', function (Blueprint $table) {
            $table->dropColumn('cantidad_material');
        });
    }
};
