<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agua_uso_eficientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('plan_ahorro_agua')->nullable();
            $table->boolean('mantenimiento_sistemas')->nullable();
            $table->boolean('uso_informacion_balance')->nullable();
            $table->boolean('mecanismo_medicion_registro')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agua_uso_eficientes');
    }
};
