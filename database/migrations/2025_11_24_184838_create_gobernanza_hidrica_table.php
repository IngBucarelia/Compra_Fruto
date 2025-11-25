<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gobernanza_hidrica', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_id');

            $table->boolean('canales_comunicacion')->default(false);
            $table->boolean('identifica_actores_afectados')->default(false);
            $table->boolean('participa_actividades_gestion')->default(false);

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_id')->references('id')->on('visita_ambientals')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gobernanza_hidrica');
    }
};
