<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    use HasFactory;

    // Si el nombre de la tabla no sigue la convención plural, puedes especificarlo aquí
    protected $table = 'postulaciones';

    // Los campos que son asignables masivamente
    protected $fillable = [
        'trabajo_id',
        'trabajador_id',
        'estado_id'
    ];

    /**
     * Relación con la tabla trabajos
     */
    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'trabajo_id');
    }

    /**
     * Relación con la tabla users (trabajador)
     */
    public function trabajador()
    {
        return $this->belongsTo(User::class, 'trabajador_id');
    }

    /**
     * Relación con la tabla estados
     */
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
