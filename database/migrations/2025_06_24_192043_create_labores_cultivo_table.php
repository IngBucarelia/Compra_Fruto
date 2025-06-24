<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('labores_cultivo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visita_id')->constrained('visitas')->onDelete('cascade');
            $table->integer('polinizacion')->nullable();
            $table->integer('limpieza_calle')->nullable();
            $table->integer('limpieza_plato')->nullable();
            $table->integer('poda')->nullable();
            $table->integer('fertilizacion')->nullable();
            $table->integer('enmiendas')->nullable();
            $table->integer('ubicacion_tusa_fibra')->nullable();
            $table->integer('ubicacion_hoja')->nullable();
            $table->integer('lugar_ubicacion_hoja')->nullable();
            $table->integer('plantas_nectariferas')->nullable();
            $table->integer('cobertura')->nullable();
            $table->integer('labor_cosecha')->nullable();
            $table->integer('calidad_fruta')->nullable();
            $table->integer('recoleccion_fruta')->nullable();
            $table->integer('drenajes')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labores_cultivo');
    }
};
