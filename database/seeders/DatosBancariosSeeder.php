<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DatosBancarios;
use App\Models\User;

class DatosBancariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = User::where('rol_id', 2)->get();

        foreach ($usuarios as $usuario) {
            DatosBancarios::create([
                'usuario_id' => $usuario->id,
                'titular' => $usuario->nombre,
                'iban' => 'ES' . str_pad(mt_rand(1000000000000000, 9999999999999999), 24, '0', STR_PAD_LEFT), // IBAN ajustado a 34 caracteres
                'nombre_banco' => 'Banco Ejemplo',
            ]);
        }
    }
}
