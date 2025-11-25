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
        Schema::create('energias_manejos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');

            $table->boolean('registro_consumo_combustible')->nullable();
            $table->boolean('plan_uso_eficiente')->nullable();
            $table->boolean('seguimiento_indicadores')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_ambiental_id')
                ->references('id')->on('visita_ambientals')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('energias_manejos');
    }
};
