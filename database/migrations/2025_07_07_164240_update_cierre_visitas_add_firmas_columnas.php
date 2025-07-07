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
       Schema::table('cierre_visitas', function (Blueprint $table) {
    // $table->longText('firma_responsable')->nullable()->after('visita_id'); // Ya existe
$table->longText('firma_recibe')->nullable()->after('firma_responsable'); 
    $table->longText('firma_testigo')->nullable()->after('firma_recibe');
    $table->json('imagenes')->nullable()->after('firma_testigo');
    $table->text('observaciones_finales')->nullable()->after('imagenes');
    $table->text('recomendaciones')->nullable()->after('observaciones_finales');
    $table->timestamp('finalizada_en')->nullable()->after('recomendaciones');
    $table->string('estado_visita')->nullable()->default('pendiente')->after('finalizada_en');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cierre_visitas', function (Blueprint $table) {
            //
        });
    }
};
