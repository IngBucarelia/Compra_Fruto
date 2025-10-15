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
            Schema::create('polinizacions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visita_id')->constrained()->onDelete('cascade');
                $table->integer('n_pases');
                $table->integer('ciclos_ronda');
                $table->decimal('ana', 8, 2);
                $table->enum('tipo_ana', ['solido', 'liquido']);
                $table->decimal('talco', 8, 2);
                $table->timestamps();
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polinizacions');
    }
};
