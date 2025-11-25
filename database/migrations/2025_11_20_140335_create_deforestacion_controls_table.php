<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deforestacion_controles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('evidencias_no_reemplazo_bosques')->nullable();
            $table->boolean('permiso_aprovechamiento_forestal')->nullable();
            $table->boolean('restauracion_compensacion')->nullable();
            $table->boolean('dentro_frontal_agricola')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deforestacion_controles');
    }
};
