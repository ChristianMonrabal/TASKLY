<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre','visible'];

    public function categorias()
    {
        return $this->belongsToMany(Trabajo::class, 'categoria_id');
    }

    // Categoria.php
    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class, 'categorias_tipo_trabajo', 'categoria_id', 'trabajo_id');
    }
}
