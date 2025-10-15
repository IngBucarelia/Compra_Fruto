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
        Schema::create('cierre_visitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visita_id')->constrained()->onDelete('cascade');
            $table->string('firma_responsable')->nullable();
            $table->text('observaciones_finales')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->json('imagenes')->nullable();
            $table->timestamp('finalizada_en')->nullable();
            $table->string('estado_visita')->default('finalizada');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_visitas');
    }
};
