<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('ibt_cosecha_produccion', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('evaluacion_ibt_id')->unique();

            $table->tinyInteger('criterio_ciclo_cosecha')->default(0);
            $table->tinyInteger('recoleccion_fruto')->default(0);
            $table->tinyInteger('calidad_fruto_cosechado')->default(0);
            $table->tinyInteger('produccion')->default(0);

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
        Schema::dropIfExists('ibt_cosecha_produccion');
    }
};
