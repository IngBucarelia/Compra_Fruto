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
            Schema::create('sanidades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visita_id')->constrained('visitas')->onDelete('cascade');
                $table->integer('opsophanes');
                $table->integer('pudricion_cogollo');
                $table->integer('raspador');
                $table->integer('palmarum');
                $table->integer('strategus');
                $table->integer('leptoparsa');
                $table->integer('pestalotiopsis');
                $table->integer('pudricion_basal');
                $table->integer('pudricion_estipe');
                $table->string('otros')->nullable();
                $table->text('observaciones')->nullable();
                $table->timestamps();
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanidades');
    }
};
