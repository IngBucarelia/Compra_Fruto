<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ecosistema_proteccions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('planes_manejo_diferenciados')->nullable();
            $table->boolean('acciones_conservacion_fragmentos')->nullable();
            $table->boolean('aplica_planes_diferenciados')->nullable();
            $table->boolean('respeta_distancias_ronda_hidrica')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecosistema_proteccions');
    }
};
