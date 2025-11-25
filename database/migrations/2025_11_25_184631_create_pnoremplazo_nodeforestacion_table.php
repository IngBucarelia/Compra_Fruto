<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePnoremplazoNodeforestacionTable extends Migration
{
    public function up()
    {
        Schema::create('pnoremplazo_nodeforestacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');

            // Sección: No reemplazo de AVC y ARC
            $table->boolean('cuenta_estudios_avc_arc')->nullable();

            // Sección: No deforestación
            $table->boolean('evidencias_no_reemplazo_bosques')->nullable();
            $table->boolean('permiso_aprovechamiento_forestal')->nullable();
            $table->boolean('restauracion_compensacion')->nullable();
            $table->boolean('dentro_frontera_agricola')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visita_ambientals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pnoremplazo_nodeforestacion');
    }
}
