<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'codigo_postal',
        'google_id',
        'password',
        'fecha_nacimiento',
        'foto_perfil',
        'descripcion',
        'dni',
        'rol_id',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'activo' lo dejamos como string “si”/“no”
    ];

    public function rol()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function codigosDescuento()
    {
        return $this->hasMany(LogroCompleto::class, 'usuario_id');
    }

    public function logrosCompletos()
    {
        return $this->belongsToMany(Logro::class, 'logros_completos', 'usuario_id', 'logro_id');
    }

    public function datosBancarios()
    {
        return $this->hasOne(DatosBancarios::class, 'usuario_id');
    }

    public function habilidades()
    {
        return $this->belongsToMany(Categoria::class, 'habilidades', 'trabajador_id', 'categoria_id');
    }

    public function trabajosComoCliente()
    {
        return $this->hasMany(Trabajo::class, 'cliente_id');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'trabajador_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'trabajador_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'trabajador_id');
    }
    
    public function valoracionesComoTrabajador()
    {
        return $this->hasMany(Valoracion::class, 'trabajador_id');
    }
    public function valoracionesRecibidas()
    {
        return $this->hasMany(\App\Models\Valoracion::class, 'trabajador_id');
    }
}
