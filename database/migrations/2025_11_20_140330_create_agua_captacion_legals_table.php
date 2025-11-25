<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agua_captacion_legals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('permiso_concesion')->nullable();
            $table->boolean('permiso_ocupacion_cauce')->nullable();
            $table->boolean('permisos_captacion')->nullable();
            $table->boolean('registro_agua')->nullable();
            $table->boolean('cumple_manejo_construccion')->nullable();
            $table->boolean('gestion_permiso_ocupacion')->nullable();
            $table->boolean('gestion_permiso_captacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agua_captacion_legals');
    }
};
