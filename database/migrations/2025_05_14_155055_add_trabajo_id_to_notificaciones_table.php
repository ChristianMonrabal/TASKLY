<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrabajoIdToNotificacionesTable extends Migration
{
    public function up()
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('trabajo_id')->nullable()->after('mensaje');
            $table->foreign('trabajo_id')
                  ->references('id')->on('trabajos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropForeign(['trabajo_id']);
            $table->dropColumn('trabajo_id');
        });
    }
}
