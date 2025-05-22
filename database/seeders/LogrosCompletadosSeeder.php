<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogroCompleto;

class LogrosCompletadosSeeder extends Seeder
{
    public function run(): void
    {
        LogroCompleto::create([
            'logro_id' => 1,
            'usuario_id' => 6,
        ]);

        LogroCompleto::create([
            'logro_id' => 2,
            'usuario_id' => 6,
        ]);

        LogroCompleto::create([
            'logro_id' => 3,
            'usuario_id' => 6,
        ]);

        LogroCompleto::create([
            'logro_id' => 4,
            'usuario_id' => 6,
        ]);

        LogroCompleto::create([
            'logro_id' => 5,
            'usuario_id' => 6,
        ]);
    }
}
