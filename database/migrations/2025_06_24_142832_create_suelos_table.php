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
            Schema::create('suelos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visita_id')->constrained()->onDelete('cascade');
                $table->enum('analisis_foliar', ['si', 'no']);
                $table->enum('alanisis_suelo', ['si', 'no']);
                $table->string('tipo_suelo');
                $table->timestamps();
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suelos');
    }
};
