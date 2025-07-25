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
        Schema::table('fertilizante_fertilizacion', function (Blueprint $table) {
            if (!Schema::hasColumn('fertilizante_fertilizacion', 'fecha_aplicacion')) {
                $table->date('fecha_aplicacion')->nullable()->after('cantidad'); // Añadir después de 'cantidad'
            }
            if (!Schema::hasColumn('fertilizante_fertilizacion', 'unidad_medida')) {
                $table->string('unidad_medida', 50)->nullable()->after('fecha_aplicacion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fertilizante_fertilizacion', function (Blueprint $table) {
            if (Schema::hasColumn('fertilizante_fertilizacion', 'fecha_aplicacion')) {
                $table->dropColumn('fecha_aplicacion');
            }
            if (Schema::hasColumn('fertilizante_fertilizacion', 'unidad_medida')) {
                $table->dropColumn('unidad_medida');
            }
        });
    }
};
