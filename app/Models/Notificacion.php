<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    
    protected $fillable = [
        'usuario_id',
        'mensaje',
        'leido',           // <- aquí
        'fecha_creacion',  // <- aquí
    ];
    
    protected $casts = [
        'leido'          => 'boolean',
        'fecha_creacion' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
