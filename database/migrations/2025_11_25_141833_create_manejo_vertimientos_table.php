<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manejo_vertimientos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('visita_ambiental_id');

            $table->boolean('permiso_vertimientos')->nullable();
            $table->boolean('sistema_agua_domestica')->nullable();
            $table->boolean('sistema_agua_no_domestica')->nullable();
            $table->boolean('sistema_agroquimicos')->nullable();
            $table->boolean('cumple_permiso')->nullable();
            $table->boolean('gestion_permiso')->nullable();
            $table->boolean('triple_lavado')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_ambiental_id')
                  ->references('id')->on('visita_ambientals')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manejo_vertimientos');
    }
};
