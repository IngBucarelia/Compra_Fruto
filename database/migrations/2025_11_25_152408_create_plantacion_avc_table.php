<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantacionAvcTable extends Migration
{
    public function up()
    {
        Schema::create('plantacion_avc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');

            $table->enum('registros_avistamientos', ['si', 'no'])->nullable();
            $table->enum('identifica_avc_arc', ['si', 'no'])->nullable();
            $table->enum('implementa_medidas_manejo', ['si', 'no'])->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_ambiental_id')
                  ->references('id')->on('visita_ambientals')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plantacion_avc');
    }
}
