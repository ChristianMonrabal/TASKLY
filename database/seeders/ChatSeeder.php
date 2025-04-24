<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chat;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        // Trabajo 1: El cliente es el usuario con id 3 (Christian)
        // Postulantes: Alex (id 2), Juan Carlos (id 4)
        Chat::create([
            'trabajo_id' => 1,
            'emisor' => 3,  // Christian (cliente)
            'receptor' => 2, // Alex (postulante)
            'contenido' => 'Hola Alex, ¿puedes hacer el trabajo de instalación de enchufes?',
        ]);
        Chat::create([
            'trabajo_id' => 1,
            'emisor' => 3,  // Christian (cliente)
            'receptor' => 4, // Juan Carlos (postulante)
            'contenido' => 'Hola Juan Carlos, ¿estás disponible para instalar los enchufes?',
        ]);

        // Trabajo 2: El cliente es el usuario con id 4 (Juan Carlos)
        // Postulantes: Daniel (id 3), Julio César (id 5)
        Chat::create([
            'trabajo_id' => 2,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 3, // Daniel (postulante)
            'contenido' => 'Hola Daniel, ¿cuándo podrías comenzar con la reparación de la puerta?',
        ]);
        Chat::create([
            'trabajo_id' => 2,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 5, // Julio César (postulante)
            'contenido' => 'Hola Julio César, ¿estás libre para reparar la puerta?',
        ]);
    }
}
