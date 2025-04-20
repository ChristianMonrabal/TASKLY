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
            'emisor' => 3,
            'receptor' => 2,
            'contenido' => 'Hola, tengo una duda sobre el trabajo.',
        ]);

        Chat::create([
            'trabajo_id' => 1,
            'emisor' => 2,
            'receptor' => 3,
            'contenido' => '¡Claro! ¿En qué puedo ayudarte?',
        ]);

        Chat::create([
            'trabajo_id' => 1,
            'emisor' => 4,
            'receptor' => 2,
            'contenido' => 'Hola, tengo una duda sobre el trabajo.',
        ]);

        Chat::create([
            'trabajo_id' => 3,
            'emisor' => 4,
            'receptor' => 3,
            'contenido' => 'Hola, tengo una duda sobre el trabajo.',
        ]);
    }
}
