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
       Schema::create('ibt_labores_culturales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('evaluacion_ibt_id');

            $table->unsignedTinyInteger('limpieza_platos');
            $table->unsignedTinyInteger('limpieza_interlineas');
            $table->unsignedTinyInteger('poda');
            $table->unsignedTinyInteger('polinizacion');
            $table->unsignedTinyInteger('disposicion_hojas_podadas');
            $table->unsignedTinyInteger('mantenimiento_infraestructura');

            $table->unsignedTinyInteger('puntaje_total');
            $table->timestamps();

            $table->foreign('evaluacion_ibt_id')
                ->references('id')
                ->on('evaluaciones_ibt')
                ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibt_labores_culturales');
    }
};
