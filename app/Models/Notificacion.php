<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $fillable = [
        'user_id',
        'titulo',
        'mensaje',
        'leida',
        'fecha_envio'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
