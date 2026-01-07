<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cierre_visita_ambientals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visita_ambiental_id')->constrained()->onDelete('cascade');
            $table->date('fecha_cierre');
            $table->string('estado_visita');
            $table->text('observaciones_finales')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->string('firma_responsable')->nullable();
            $table->string('firma_recibe')->nullable();
            $table->string('firma_testigo')->nullable();
            $table->json('imagenes')->nullable();
            $table->date('finalizada_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cierre_visita_ambientals');
    }
};