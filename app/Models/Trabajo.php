<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
    {
    protected $fillable = [
        'titulo',
        'descripcion',
        'precio',
        'direccion',
        'cliente_id',
        'estado_id',
        'alta_responsabilidad'
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function categoriastipotrabajo()
    {
        return $this->belongsToMany(Categoria::class, 'categorias_tipo_trabajo', 'trabajo_id', 'categoria_id');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ImgTrabajo::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function valoraciones()
    {
        return $this->hasMany(Valoracion::class);
    }

    public function chat()
    {
        return $this->hasMany(Chat::class);
    }
}
