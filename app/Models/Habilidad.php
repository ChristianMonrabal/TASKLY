<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habilidad extends Model
{
    protected $table = 'habilidades';

    protected $fillable = ['trabajador_id', 'categoria_id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'habilidad_user', 'habilidad_id', 'user_id');
    }
}
