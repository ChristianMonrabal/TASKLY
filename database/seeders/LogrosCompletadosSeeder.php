<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogroCompleto;

class LogrosCompletadosSeeder extends Seeder
{
    public function run(): void
    {
        LogroCompleto::create([
            'codigo' => 'LOGRO-001',
            'estado' => true,
            'logro_id' => 1,
            'usuario_id' => 3,
        ]);
    }
}
