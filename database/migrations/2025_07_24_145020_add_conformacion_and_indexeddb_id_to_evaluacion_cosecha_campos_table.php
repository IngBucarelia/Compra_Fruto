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
        Schema::table('evaluacion_cosecha_campo', function (Blueprint $table) {
            if (!Schema::hasColumn('evaluacion_cosecha_campo', 'conformacion')) {
                $table->string('conformacion')->nullable()->after('pedunculo'); // Ajusta la posición si lo deseas
            }
            if (!Schema::hasColumn('evaluacion_cosecha_campo', 'indexeddb_id')) {
                $table->string('indexeddb_id')->nullable()->after('visita_id');
                // Si quieres que la combinación visita_id y indexeddb_id sea única:
                // $table->unique(['visita_id', 'indexeddb_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluacion_cosecha_campo', function (Blueprint $table) {
            if (Schema::hasColumn('evaluacion_cosecha_campo', 'conformacion')) {
                $table->dropColumn('conformacion');
            }
            if (Schema::hasColumn('evaluacion_cosecha_campo', 'indexeddb_id')) {
                // Si añadiste la clave única, también debes eliminarla aquí
                // $table->dropUnique(['visita_id', 'indexeddb_id']);
                $table->dropColumn('indexeddb_id');
            }
        });
    }
};
