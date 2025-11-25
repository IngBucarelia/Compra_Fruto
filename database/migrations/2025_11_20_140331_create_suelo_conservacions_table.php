<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suelo_conservaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('uso_fuego_preparacion')->nullable();
            $table->boolean('control_coberturas_invasoras')->nullable();
            $table->boolean('siembra_coberturas')->nullable();
            $table->boolean('sigue_recomendaciones_comerciales')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suelo_conservaciones');
    }
};
