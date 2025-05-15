<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reportes extends Model
{
    protected $table = 'reportes';

    protected $fillable = [
        'motivo',
        'id_usuario',
        'gravedad',
        'estado',
        'reportado_Por',
    ];

    /**
     * Usuario que fue reportado.
     */
    public function usuarioReportado()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Usuario que hizo el reporte.
     */
    public function reportadoPor()
    {
        return $this->belongsTo(User::class, 'reportado_Por');
    }

    /**
     * Nivel de gravedad del reporte.
     */
    public function nivelGravedad()
    {
        return $this->belongsTo(Estado::class, 'gravedad');
    }

    /**
     * Estado del reporte (pendiente, cerrado, etc.).
     */
    public function estadoReporte()
    {
        return $this->belongsTo(Estado::class, 'estado');
    }
}
