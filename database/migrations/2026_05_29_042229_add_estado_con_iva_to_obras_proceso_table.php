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
        Schema::table('obras_proceso', function (Blueprint $table) {
            $table->string('estado', 20)->default('en_curso')->after('id_obra');
            $table->boolean('con_iva')->default(true)->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obras_proceso', function (Blueprint $table) {
            $table->dropColumn(['estado', 'con_iva']);
        });
    }
};
