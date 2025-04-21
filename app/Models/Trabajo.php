<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'descripcion', 'precio', 'direccion', 'cliente_id', 'estado_id'
    ];

    // Relación many-to-many con Categoria
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categorias_tipo_trabajo', 'trabajo_id', 'categoria_id');
    }

    public function categoriastipotrabajo()
    {
        return $this->belongsToMany(CategoriaTipoTrabajo::class, 'categorias_tipo_trabajo', 'trabajo_id', 'categoria_tipo_trabajo_id');
    }

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ImgTrabajo::class, 'trabajo_id');
    }
}
