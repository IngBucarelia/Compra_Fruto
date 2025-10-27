<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('datos_personales_socials', function (Blueprint $table) {
            $table->string('numero_rnp')->nullable()->after('rnp');
        });
    }

    public function down()
    {
        Schema::table('datos_personales_socials', function (Blueprint $table) {
            $table->dropColumn('numero_rnp');
        });
    }
};