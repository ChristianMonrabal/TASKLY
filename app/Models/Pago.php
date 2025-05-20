<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'postulacion_id',
        'cantidad',
        'estado_id',
        'metodo_id',
        'fecha_pago',
    ];

    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class);
    }
    
    public function trabajo()
    {
        return $this->postulacion->trabajo();
    }

    public function trabajador()
    {
        return $this->postulacion->trabajador();
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
