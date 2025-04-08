<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'trabajo_id',
        'trabajador_id',
        'mensaje',
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function trabajador()
    {
        return $this->belongsTo(User::class, 'trabajador_id');
    }
}
