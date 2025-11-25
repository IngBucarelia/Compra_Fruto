<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manejo_sustancias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            
            $table->boolean('cuenta_con_poes')->default(false);
            $table->boolean('personal_capacitado_certificado')->default(false);
            $table->boolean('almacenamiento_adecuado')->default(false);

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('visita_ambiental_id')
                ->references('id')
                ->on('visita_ambientals')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manejo_sustancias');
    }
};
