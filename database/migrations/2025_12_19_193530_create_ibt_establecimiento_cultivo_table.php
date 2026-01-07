<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ibt_establecimiento_cultivo', function (Blueprint $table) {
            $table->id();

            // Relación con la evaluación IBT
            $table->foreignId('evaluacion_ibt_id')
                ->constrained('evaluaciones_ibt')
                ->onDelete('cascade');

            // Preguntas (calificación actual)
            $table->integer('estudios_caracterizacion_suelos')->nullable(); // máx 2
            $table->integer('estudios_topograficos')->nullable();           // máx 2
            $table->integer('diseno_riegos_drenajes')->nullable();           // máx 6
            $table->integer('diseno_uma')->nullable();                       // máx 3
            $table->integer('preparacion_suelos')->nullable();               // máx 4
            $table->integer('leguminosas_cobertura')->nullable();            // máx 3

            // Puntaje total del componente
            $table->integer('puntaje_total')->default(0);                   // máx 20

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ibt_establecimiento_cultivo');
    }
};
