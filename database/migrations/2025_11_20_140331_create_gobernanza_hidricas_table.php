<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gobernanza_hidricas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('establece_canales_comunicacion')->nullable();
            $table->boolean('identifica_actores_afectados')->nullable();
            $table->boolean('participa_actividades_gestion_hidrica')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gobernanza_hidricas');
    }
};
