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
        Schema::table('labores_cultivo', function (Blueprint $table) {
            // Añadir la nueva columna 'observaciones' como TEXT
            // La colocamos después de 'drenajes' por convención, pero puedes ajustarlo.
            if (!Schema::hasColumn('labores_cultivo', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('drenajes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labores_cultivo', function (Blueprint $table) {
            // Revertir: Eliminar la columna 'observaciones'
            if (Schema::hasColumn('labores_cultivo', 'observaciones')) {
                $table->dropColumn('observaciones');
            }
        });
    }
};
