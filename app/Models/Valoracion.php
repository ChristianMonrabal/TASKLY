<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    protected $table = 'valoraciones';

    protected $fillable = [
        'trabajo_id',
        'trabajador_id',
        'puntuacion',
        'img_valoracion',
        'comentario',
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
