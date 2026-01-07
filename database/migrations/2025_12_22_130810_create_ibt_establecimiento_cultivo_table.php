<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ibt_establecimiento_cultivo', function (Blueprint $table) {
            $table->id();

            // 🔑 CLAVE FORÁNEA CORRECTA
            $table->unsignedBigInteger('evaluacion_ibt_id');

            // CAMPOS DEL COMPONENTE
            $table->integer('estudios_caracterizacion_suelos')->nullable();
            $table->integer('estudios_topograficos')->nullable();
            $table->integer('diseno_riegos_drenajes')->nullable();
            $table->integer('diseno_uma')->nullable();
            $table->integer('preparacion_suelos')->nullable();
            $table->integer('leguminosas_cobertura')->nullable();

            $table->integer('puntaje_total')->nullable();

            $table->timestamps();

            // 🔗 FK BIEN FORMADA
            $table->foreign('evaluacion_ibt_id')
                ->references('id')
                ->on('evaluaciones_ibt')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ibt_establecimiento_cultivo');
    }
};
