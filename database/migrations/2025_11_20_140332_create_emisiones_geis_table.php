<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emisiones_geis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('cuantifica_gei')->nullable();
            $table->boolean('acciones_reduccion_gei')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emisiones_geis');
    }
};
