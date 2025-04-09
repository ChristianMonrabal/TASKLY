<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    use HasFactory;

    // Relación many-to-many con CategoriaTipoTrabajo
    public function categoriastipotrabajo()
    {
        return $this->belongsToMany(CategoriaTipoTrabajo::class, 'categorias_tipo_trabajo', 'trabajo_id', 'categoria_tipo_trabajo_id');
    }

    // Otras relaciones, como cliente, estado, etc.
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}