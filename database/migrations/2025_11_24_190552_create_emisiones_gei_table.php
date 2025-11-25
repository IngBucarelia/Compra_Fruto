<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmisionesGeiTable extends Migration
{
    public function up()
    {
        Schema::create('emisiones_gei', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');

            $table->boolean('cuantifica_emisiones')->nullable(); 
            $table->boolean('implementa_acciones_reduccion')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_ambiental_id')
                ->references('id')->on('visita_ambientals')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('emisiones_gei');
    }
}
