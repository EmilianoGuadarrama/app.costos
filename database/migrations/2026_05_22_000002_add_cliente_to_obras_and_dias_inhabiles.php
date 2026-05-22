<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar id_cliente a obras_iniciadas si no existe
        if (!Schema::hasColumn('obras_iniciadas', 'id_cliente')) {
            Schema::table('obras_iniciadas', function (Blueprint $table) {
                $table->unsignedBigInteger('id_cliente')->nullable()->after('encargado_id_empleado');
            });
            // Agregar FK por separado (sin constrained para evitar problemas de tipo)
            try {
                \DB::statement('ALTER TABLE obras_iniciadas ADD CONSTRAINT obras_ini_cliente_fk FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON DELETE SET NULL');
            } catch (\Throwable $e) {
                // Ignorar si ya existe o no es compatible
            }
        }

        // Tabla de días inhábiles globales
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
        if (Schema::hasColumn('obras_iniciadas', 'id_cliente')) {
            Schema::table('obras_iniciadas', function (Blueprint $table) {
                $table->dropForeign(['id_cliente']);
                $table->dropColumn('id_cliente');
            });
        }
        Schema::dropIfExists('dias_inhabiles');
    }
};
