<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chat;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        // Trabajo 1:
        Chat::create([
            'trabajo_id' => 1,
            'emisor' => 5,  // (cliente)
            'receptor' => 6, // (trabajador)
            'contenido' => 'Hola Alex, ¿puedes hacer el trabajo de instalación de enchufes?',
        ]);
        Chat::create([
            'trabajo_id' => 1,
            'emisor' => 5,  // (cliente)
            'receptor' => 6, // (trabajador)
            'contenido' => 'Hola Juan Carlos, ¿estás disponible para instalar los enchufes?',
        ]);

        // Trabajo 2:
        Chat::create([
            'trabajo_id' => 2,
            'emisor' => 5,  // (cliente)
            'receptor' => 6, // (trabajador))
            'contenido' => 'Hola Daniel, ¿cuándo podrías comenzar con la reparación de la puerta?',
        ]);
        Chat::create([
            'trabajo_id' => 2,
            'emisor' => 5,  // (cliente)
            'receptor' => 6, // (trabajador))
            'contenido' => 'Hola Julio César, ¿estás libre para reparar la puerta?',
        ]);
    }
}
