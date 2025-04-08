<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chat;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        Chat::create([
            'trabajo_id' => 1,
            'trabajador_id' => 3,
            'contenido' => 'Hola, tengo una duda sobre el trabajo.',
        ]);

        Chat::create([
            'trabajo_id' => 1,
            'trabajador_id' => 2,
            'contenido' => '¡Claro! ¿En qué puedo ayudarte?',
        ]);



        Chat::create([
            'trabajo_id' => 2,
            'trabajador_id' => 4,
            'contenido' => 'Hola, tengo una duda sobre el trabajo.',
        ]);

        Chat::create([
            'trabajo_id' => 3,
            'trabajador_id' => 4,
            'contenido' => 'Hola, tengo una duda sobre el trabajo.',
        ]);
    }
}
