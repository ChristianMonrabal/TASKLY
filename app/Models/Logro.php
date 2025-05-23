<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logro extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'foto_logro'];

    public function logrosCompletos()
    {
        return $this->hasMany(LogroCompleto::class, 'logro_id');
    }
}
