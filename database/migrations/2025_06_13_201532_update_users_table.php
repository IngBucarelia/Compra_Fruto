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
            Schema::table('users', function (Blueprint $table) {
                $table->integer('num_documento')->after('password');
                $table->string('area_pertenece')->after('num_documento');
                $table->string('ocupacion')->after('area_pertenece');
                $table->integer('rol')->after('ocupacion'); // 1: Admin, 2: Encargado, etc.
                $table->date('dia_creado')->nullable()->after('rol');
            });
        }

        public function down()
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['num_documento', 'area_pertenece', 'ocupacion', 'rol', 'dia_creado']);
            });
        }
};
