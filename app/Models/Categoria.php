<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre'];

    public function categorias()
    {
        return $this->belongsToMany(Trabajo::class, 'categoria_id');
    }
}
