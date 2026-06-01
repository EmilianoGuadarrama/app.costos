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
            $table->unsignedInteger('id_material')->nullable()->after('id_obra');
            
            $table->foreign('id_material')->references('id')->on('materiales')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('egresos_totales', function (Blueprint $table) {
            $table->dropForeign(['id_material']);
            $table->dropColumn('id_material');
        });
    }
};
