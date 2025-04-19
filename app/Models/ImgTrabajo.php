<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImgTrabajo extends Model
{
    protected $table = 'img_trabajos';
    
    protected $fillable = ['ruta_imagen', 'trabajo_id', 'nombre_img', 'descripcion'];
    
    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }
}
