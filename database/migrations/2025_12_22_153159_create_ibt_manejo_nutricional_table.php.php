<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ibt_manejo_nutricional', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('evaluacion_ibt_id')->unique();

            $table->unsignedTinyInteger('toma_muestra_foliares')->default(0);
            $table->unsignedTinyInteger('toma_muestras_suelos')->default(0);
            $table->unsignedTinyInteger('censo_produccion')->default(0);
            $table->unsignedTinyInteger('eficacia_fertilizacion')->default(0);
            $table->unsignedTinyInteger('fraccionamiento_fertilizacion')->default(0);
            $table->unsignedTinyInteger('epoca_fertilizacion')->default(0);
            $table->unsignedTinyInteger('medicion_crecimiento')->default(0);

            $table->unsignedTinyInteger('puntaje_total')->default(0);

            $table->timestamps();

            $table->foreign('evaluacion_ibt_id')
                ->references('id')
                ->on('evaluaciones_ibt')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibt_manejo_nutricional');
    }
};
