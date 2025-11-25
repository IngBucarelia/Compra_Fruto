<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResiduosManejoTable extends Migration
{
    public function up()
    {
        Schema::create('residuos_manejo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');

            $table->boolean('capacita_personal')->nullable();
            $table->boolean('conoce_diferencias')->nullable();
            $table->boolean('certificado_final_respel')->nullable();
            $table->boolean('manifiesto_transporte_respel')->nullable();
            $table->boolean('puntos_ecologicos')->nullable();
            $table->boolean('entrega_transportador_aut')->nullable();
            $table->boolean('disposicion_empresa_aut')->nullable();
            $table->boolean('acciones_minimizar_impacto')->nullable();
            $table->boolean('certificado_relleno_sanitario')->nullable();
            $table->boolean('residuos_aprovechables_gestion')->nullable();
            $table->boolean('pesa_y_registra')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_ambiental_id')
                ->references('id')->on('visita_ambientals')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('residuos_manejo');
    }
}
