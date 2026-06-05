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
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->integer('version')->default(1)->after('id');
            $table->foreignId('presupuesto_padre_id')->nullable()->constrained('presupuestos')->nullOnDelete()->after('version');
            $table->boolean('es_version_actual')->default(true)->after('presupuesto_padre_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropForeign(['presupuesto_padre_id']);
            $table->dropColumn(['version', 'presupuesto_padre_id', 'es_version_actual']);
        });
    }
};
