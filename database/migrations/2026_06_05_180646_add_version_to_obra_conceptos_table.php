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
        Schema::table('obra_conceptos', function (Blueprint $table) {
            $table->integer('version')->default(1)->after('id_obra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obra_conceptos', function (Blueprint $table) {
            $table->dropColumn('version');
        });
    }
};
