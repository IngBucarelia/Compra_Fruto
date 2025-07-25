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
            if (!Schema::hasColumn('labores_cultivo', 'tipo_planta')) {
                $table->string('tipo_planta')->nullable()->after('visita_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labores_cultivo', function (Blueprint $table) {
            if (Schema::hasColumn('labores_cultivo', 'tipo_planta')) {
                $table->dropColumn('tipo_planta');
            }
        });
    }
};
