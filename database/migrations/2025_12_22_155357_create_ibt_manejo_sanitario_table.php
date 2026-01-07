<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ibt_manejo_sanitario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('evaluacion_ibt_id')->unique();

            $table->tinyInteger('censo_enfermedades_plagas')->default(0);
            $table->tinyInteger('oportunidad_control')->default(0);
            $table->tinyInteger('calidad_follaje')->default(0);
            $table->tinyInteger('area_foliar')->default(0);
            $table->tinyInteger('censo_palmas_anormales')->default(0);

            $table->integer('puntaje_total')->default(0);

            $table->timestamps();

            $table->foreign('evaluacion_ibt_id')
                ->references('id')
                ->on('evaluaciones_ibt')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ibt_manejo_sanitario');
    }
};
