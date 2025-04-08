<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaTipoTrabajo extends Model
{
    protected $table = 'categorias_tipo_trabajo';

    protected $fillable = [
        'categoria_id',
        'trabajo_id'
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }
    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
