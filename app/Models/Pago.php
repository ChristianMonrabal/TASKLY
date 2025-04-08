<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'trabajo_id',
        'trabajador_id',
        'cantidad',
        'estado_id',
        'metodo_id',
        'fecha_pago',
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function trabajador()
    {
        return $this->belongsTo(User::class, 'trabajador_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_id');
    }
}
