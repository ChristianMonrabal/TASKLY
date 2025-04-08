<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $fillable = ['nombre', 'tipo_estado'];
    
    public function trabajos()
    {
        return $this->hasMany(Trabajo::class);
    }
    
    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class);
    }
    
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
