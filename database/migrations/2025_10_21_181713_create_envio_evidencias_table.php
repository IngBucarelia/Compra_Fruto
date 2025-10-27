<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvioEvidenciasTable extends Migration
{
    public function up()
    {
        Schema::create('envio_evidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->constrained('envios')->onDelete('cascade');
            $table->string('archivo'); // ruta
            $table->string('tipo')->nullable(); // foto, documento, otro
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('envio_evidencias');
    }
}
