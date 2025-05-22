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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('trabajo_id')->nullable()->constrained('trabajos');
            $table->string('direccion', 255);
            $table->string('codigo_postal', 10);
            $table->string('ciudad', 100);
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->boolean('es_visible_para_trabajador')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
