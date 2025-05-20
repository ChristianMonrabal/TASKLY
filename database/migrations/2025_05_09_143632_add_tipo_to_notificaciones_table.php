<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoToNotificacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->string('tipo')->default('general'); // Puedes poner un valor por defecto
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
}
