<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('cierre_visita_sociales', function (Blueprint $table) {
        $table->id();
        $table->foreignId('visita_social_id')->constrained('visitas_sociales')->onDelete('cascade');
        $table->date('fecha_cierre');
        $table->enum('estado_visita', ['completado', 'pendiente', 'cancelado'])->default('pendiente');
        $table->text('observaciones_finales')->nullable();
        $table->text('recomendaciones')->nullable();
        $table->string('firma_responsable');
        $table->string('firma_recibe');
        $table->string('firma_testigo')->nullable();
        $table->json('imagenes')->nullable();
        $table->timestamp('finalizada_en')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_visita_socials');
    }
};
