<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            MetodoPagoSeeder::class,
            LogrosSeeder::class,
            CategoriasSeeder::class,
            UsuarioSeeder::class,
            LogrosCompletadosSeeder::class,
            DatosBancariosSeeder::class,
            HabilidadesSeeder::class,
            EstadoSeeder::class,
            TrabajoSeeder::class,
            CategoriaTipoTrabajoSeeder::class,
            ChatSeeder::class,
            PostulacionesSeeder::class,
            ValoracionesSeeder::class,
            PagosSeeder::class,
            CalendarioSeeder::class,
        ]);
    }
}
