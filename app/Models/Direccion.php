<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direcciones';

    protected $fillable = [
        'user_id',
        'trabajo_id',
        'direccion',
        'codigo_postal',
        'ciudad',
        'latitud',
        'longitud',
        'es_visible_para_trabajador'
    ];

    /**
     * Obtiene el usuario asociado a esta dirección
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtiene el trabajo asociado a esta dirección
     */
    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'trabajo_id');
    }

    /**
     * Verifica si esta dirección es visible para el trabajador especificado
     */
    public function esVisiblePara($usuario_id)
    {
        if ($this->es_visible_para_trabajador) {
            return true;
        }

        // Si la dirección está asociada a un trabajo, verificar si el usuario es el trabajador asignado
        if ($this->trabajo_id) {
            $trabajo = $this->trabajo;
            return $trabajo && $trabajo->trabajador_id == $usuario_id;
        }

        return false;
    }
}
