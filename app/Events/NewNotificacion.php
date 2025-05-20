<?php
namespace App\Events;

use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotificacion implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notificacion;
    public $url;

    public function __construct(Notificacion $notificacion)
    {
        $this->notificacion = $notificacion;
        $this->url          = $notificacion->url;
    }

    public function broadcastOn()
    {
        return new Channel('App.Models.User.' . $this->notificacion->usuario_id);
    }

    public function broadcastWith()
    {
        return [
            'notificacion' => $this->notificacion,
            'url'          => $this->url,
        ];
    }
}
