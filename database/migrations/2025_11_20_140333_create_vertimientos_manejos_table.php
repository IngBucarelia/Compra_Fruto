<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vertimientos_manejos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('permiso_vertimiento')->nullable();
            $table->boolean('sistemas_tratamiento_domestico')->nullable();
            $table->boolean('sistemas_tratamiento_agroquimicos')->nullable();
            $table->boolean('cumple_obligacion_permiso')->nullable();
            $table->boolean('gestion_permiso_vertimiento')->nullable();
            $table->boolean('realiza_triplelavado')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vertimientos_manejos');
    }
};
