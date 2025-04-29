<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class calendario extends Model
{
    protected $table = 'calendario';

    protected $fillable = [
        'titulo',
        'descripcion',
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'trabajo');
    }

    public function clientes()
    {
        return $this->belongsTo(User::class, 'cliente');
    }

    public function trabajadores()
    {
        return $this->belongsTo(User::class, 'trabajador');
    }
    
}
