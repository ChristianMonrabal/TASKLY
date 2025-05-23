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
        Schema::create('datos_bancarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->string('titular', 100)->nullable();
            $table->string('iban', 34)->unique()->nullable();
            $table->string('nombre_banco', 100)->nullable();
            
            // Campos para información fiscal
            $table->string('nif_fiscal', 20)->nullable();
            $table->string('direccion_fiscal', 255)->nullable();
            $table->string('codigo_postal_fiscal', 10)->nullable();
            $table->string('ciudad_fiscal', 100)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_bancarios');
    }
};
