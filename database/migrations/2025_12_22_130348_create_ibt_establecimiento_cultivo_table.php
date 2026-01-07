<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ibt_establecimiento_cultivo', function (Blueprint $table) {
            $table->id();

            // Relación con evaluación IBT
            $table->unsignedBigInteger('evaluacion_ibt_id');

            // PREGUNTAS DEL COMPONENTE
            $table->integer('preparacion_terreno')->nullable();
            $table->integer('material_siembra')->nullable();
            $table->integer('densidad_siembra')->nullable();
            $table->integer('distancia_siembra')->nullable();
            $table->integer('control_malezas')->nullable();

            // Puntaje total del componente
            $table->integer('puntaje_total')->default(0);

            $table->timestamps();

            // FK
            $table->foreign('evaluacion_ibt_id')
                  ->references('id')
                  ->on('evaluaciones_ibt')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibt_establecimiento_cultivo');
    }
};
