<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id',
        'mensaje',
        'leido',
        'fecha_creacion',
        'trabajo_id',
    ];

    protected $casts = [
        'leido'          => 'boolean',
        'fecha_creacion' => 'datetime',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        // Si hemos inyectado manualmente $model->url en el controlador, úsalo:
        if (isset($this->attributes['url'])) {
            return $this->attributes['url'];
        }
        // Si no, genera la ruta mínima sin query:
        return route('vista.chat', ['id' => $this->trabajo_id]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
