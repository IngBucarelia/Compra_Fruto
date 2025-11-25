<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantacionEcosistemasTable extends Migration
{
    public function up()
    {
        Schema::create('plantacion_ecosistemas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('visita_ambiental_id');

            $table->enum('planes_manejo_diferenciados', ['si', 'no'])->nullable();
            $table->enum('acciones_conservacion_fragmentos', ['si', 'no'])->nullable();
            $table->enum('implementa_planes_manejo_diferenciado', ['si', 'no'])->nullable();
            $table->enum('respeta_distancias_ronda_hidrica', ['si', 'no'])->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_ambiental_id')
                  ->references('id')->on('visita_ambientals')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plantacion_ecosistemas');
    }
}
