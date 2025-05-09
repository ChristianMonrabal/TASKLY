<?php

namespace App\Events;

use App\Models\Notificacion;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
class NewNotificacion
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notificacion;

    /**
     * Crear una nueva instancia de evento.
     *
     * @param \App\Models\Notificacion $notificacion
     * @return void
     */

    public function __construct(Notificacion $notificacion)
    {
        $this->notificacion = $notificacion;
    }
    
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->notificacion->usuario_id);
    }
}



