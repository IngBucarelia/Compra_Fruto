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
        Schema::table('sanidades', function (Blueprint $table) {
            if (!Schema::hasColumn('sanidades', 'local_id')) {
                $table->string('local_id', 36)->nullable()->unique()->after('id'); // UUID es de 36 caracteres
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanidades', function (Blueprint $table) {
            if (Schema::hasColumn('sanidades', 'local_id')) {
                $table->dropUnique(['local_id']); // Eliminar el índice único
                $table->dropColumn('local_id');
            }
        });
    }
};
