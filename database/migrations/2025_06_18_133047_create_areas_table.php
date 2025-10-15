<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_id');
            $table->enum('material', ['guinense', 'hibrido']);
            $table->enum('estado', ['desarrollo', 'produccion']);
            $table->date('anio_siembra');
            $table->integer('area');
            $table->integer('orden_plantis_numero');
            $table->enum('estado_oren_plantis', ['desarrollo', 'produccion']);
            $table->timestamps();

            $table->foreign('visita_id')->references('id')->on('visitas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
