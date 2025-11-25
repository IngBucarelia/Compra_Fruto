<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sustancias_quimicas_biologicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');

            $table->boolean('cuenta_poes')->nullable();
            $table->boolean('personal_capacitado')->nullable();
            $table->boolean('almacenamiento_adecuado')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_ambiental_id')
                  ->references('id')->on('visita_ambientals')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sustancias_quimicas_biologicas');
    }
};
