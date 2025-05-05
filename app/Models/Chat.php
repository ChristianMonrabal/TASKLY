<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'trabajo_id',
        'emisor',
        'receptor',
        'contenido',
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function emisor()
    {
        return $this->belongsTo(User::class, 'emisor');
    }

    public function receptor()
    {
        return $this->belongsTo(User::class, 'receptor');
    }
}
