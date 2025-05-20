<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Si ya tienes un boolean, primero lo eliminamos
            if (Schema::hasColumn('categorias', 'visible')) {
                $table->dropColumn('visible');
            }
        });

        Schema::table('categorias', function (Blueprint $table) {
            // Lo volvemos a añadir como string 'Sí'/'No'
            $table->string('visible', 2)->default('Sí')->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('visible');
        });

        Schema::table('categorias', function (Blueprint $table) {
            // Opcionalmente, si quieres volver al booleano original:
            $table->boolean('visible')->default(true)->after('nombre');
        });
    }
};
