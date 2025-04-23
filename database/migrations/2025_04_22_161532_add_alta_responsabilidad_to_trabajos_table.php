<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trabajos', function (Blueprint $table) {
            $table->string('alta_responsabilidad', 2)->default('No')->after('fecha_limite');
            // Guardará "Sí" o "No" como string de 2 caracteres
        });
    }

    public function down(): void
    {
        Schema::table('trabajos', function (Blueprint $table) {
            $table->dropColumn('alta_responsabilidad');
        });
    }
};
