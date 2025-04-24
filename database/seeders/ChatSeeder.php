<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chat;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
       // Trabajo 1: El cliente es el usuario con id 3 (Christian), los postulantes no serán este usuario
        // Emisor: Cliente (Christian), Receptor: Postulante 1 (Alex)
        Chat::create([
            'trabajo_id' => 1,
            'emisor' => 3,  // Christian (cliente)
            'receptor' => 2, // Alex (postulante)
            'contenido' => 'Hola, tengo una duda sobre el trabajo.',
        ]);

        // Emisor: Cliente (Christian), Receptor: Postulante 2 (Juan Carlos)
        Chat::create([
            'trabajo_id' => 1,
            'emisor' => 3,  // Christian (cliente)
            'receptor' => 4, // Juan Carlos (postulante)
            'contenido' => 'Hola, ¿estás disponible para trabajar en este proyecto?',
        ]);

        // Trabajo 2: El cliente es el usuario con id 3 (Christian), los postulantes no serán este usuario
        // Emisor: Cliente (Christian), Receptor: Postulante 1 (Daniel)
        Chat::create([
            'trabajo_id' => 2,
            'emisor' => 3,  // Christian (cliente)
            'receptor' => 3, // Daniel (postulante)
            'contenido' => '¿Qué tal, Daniel? ¿Puedes hacer este trabajo?',
        ]);

        // Emisor: Cliente (Christian), Receptor: Postulante 2 (Julio César)
        Chat::create([
            'trabajo_id' => 2,
            'emisor' => 3,  // Christian (cliente)
            'receptor' => 5, // Julio César (postulante)
            'contenido' => '¿Cuándo podrías comenzar con la instalación?',
        ]);

        // Trabajo 3: El cliente es el usuario con id 3 (Christian), los postulantes no serán este usuario
        // Emisor: Cliente (Christian), Receptor: Postulante 1 (Alex)
        Chat::create([
            'trabajo_id' => 3,
            'emisor' => 3,  // Christian (cliente)
            'receptor' => 2, // Alex (postulante)
            'contenido' => 'Tengo dudas sobre los materiales que necesitas.',
        ]);

        // Emisor: Cliente (Christian), Receptor: Postulante 2 (Juan Carlos)
        Chat::create([
            'trabajo_id' => 3,
            'emisor' => 3,  // Christian (cliente)
            'receptor' => 4, // Juan Carlos (postulante)
            'contenido' => '¿Tienes experiencia reparando este tipo de puertas?',
        ]);

        // Trabajo 4: El cliente es el usuario con id 4 (Juan Carlos), los postulantes no serán este usuario
        // Emisor: Cliente (Juan Carlos), Receptor: Postulante 1 (Alex)
        Chat::create([
            'trabajo_id' => 4,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 2, // Alex (postulante)
            'contenido' => '¿Te gustaría tomar este trabajo? Necesito que venga hoy.',
        ]);

        // Emisor: Cliente (Juan Carlos), Receptor: Postulante 2 (Daniel)
        Chat::create([
            'trabajo_id' => 4,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 3, // Daniel (postulante)
            'contenido' => '¿Cuánto tiempo tomaría hacer la instalación?',
        ]);

        // Trabajo 5: El cliente es el usuario con id 4 (Juan Carlos), los postulantes no serán este usuario
        // Emisor: Cliente (Juan Carlos), Receptor: Postulante 1 (Julio César)
        Chat::create([
            'trabajo_id' => 5,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 5, // Julio César (postulante)
            'contenido' => 'Hola, ¿puedes instalar la lámpara el jueves?',
        ]);

        // Emisor: Cliente (Juan Carlos), Receptor: Postulante 2 (Alex)
        Chat::create([
            'trabajo_id' => 5,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 2, // Alex (postulante)
            'contenido' => '¿Tienes experiencia trabajando con lámparas de techo?',
        ]);

        // Trabajo 6: El cliente es el usuario con id 4 (Juan Carlos), los postulantes no serán este usuario
        // Emisor: Cliente (Juan Carlos), Receptor: Postulante 1 (Daniel)
        Chat::create([
            'trabajo_id' => 6,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 3, // Daniel (postulante)
            'contenido' => 'Hola, ¿cómo estás? ¿Te gustaría ayudarme con la reparación?',
        ]);

        // Emisor: Cliente (Juan Carlos), Receptor: Postulante 2 (Julio César)
        Chat::create([
            'trabajo_id' => 6,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 5, // Julio César (postulante)
            'contenido' => '¿Podrías ayudarme con la reparación de la ventana?',
        ]);

        // Trabajo 7: El cliente es el usuario con id 4 (Juan Carlos), los postulantes no serán este usuario
        // Emisor: Cliente (Juan Carlos), Receptor: Postulante 1 (Daniel)
        Chat::create([
            'trabajo_id' => 7,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 3, // Daniel (postulante)
            'contenido' => 'Hola, ¿cuándo podrías instalar las cortinas?',
        ]);

        // Emisor: Cliente (Juan Carlos), Receptor: Postulante 2 (Juan Carlos)
        Chat::create([
            'trabajo_id' => 7,
            'emisor' => 4,  // Juan Carlos (cliente)
            'receptor' => 4, // Juan Carlos (postulante)
            'contenido' => '¿Estás disponible para empezar mañana?',
        ]);

        // Trabajo 8: El cliente es el usuario con id 5 (Pablo), los postulantes no serán este usuario
        // Emisor: Cliente (Pablo), Receptor: Postulante 1 (Alex)
        Chat::create([
            'trabajo_id' => 8,
            'emisor' => 5,  // Pablo (cliente)
            'receptor' => 2, // Alex (postulante)
            'contenido' => 'Hola, ¿puedes limpiar mi garaje este fin de semana?',
        ]);

        // Emisor: Cliente (Pablo), Receptor: Postulante 2 (Daniel)
        Chat::create([
            'trabajo_id' => 8,
            'emisor' => 5,  // Pablo (cliente)
            'receptor' => 3, // Daniel (postulante)
            'contenido' => '¿Podrías limpiar mi garaje de 20m² mañana?',
        ]);

        // Trabajo 9: El cliente es el usuario con id 5 (Pablo), los postulantes no serán este usuario
        // Emisor: Cliente (Pablo), Receptor: Postulante 1 (Alex)
        Chat::create([
            'trabajo_id' => 9,
            'emisor' => 5,  // Pablo (cliente)
            'receptor' => 2, // Alex (postulante)
            'contenido' => '¿Estás disponible para reparar la persiana?',
        ]);

        // Emisor: Cliente (Pablo), Receptor: Postulante 2 (Juan Carlos)
        Chat::create([
            'trabajo_id' => 9,
            'emisor' => 5,  // Pablo (cliente)
            'receptor' => 4, // Juan Carlos (postulante)
            'contenido' => 'Hola, ¿puedes venir a reparar la persiana este viernes?',
        ]);
    }
}
