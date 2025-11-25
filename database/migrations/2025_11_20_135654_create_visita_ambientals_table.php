<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitas_ambientales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plantacion_id')->nullable();
            $table->unsignedBigInteger('tecnico_id')->nullable();
            $table->date('fecha_visita');
            $table->string('ubicacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['pendiente','en_proceso','completada'])->default('pendiente');
            $table->timestamps();

            // foreign keys - ajusta si no tienes plantaciones/users
            // $table->foreign('plantacion_id')->references('id')->on('plantaciones')->onDelete('set null');
            // $table->foreign('tecnico_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitas_ambientales');
    }
};
