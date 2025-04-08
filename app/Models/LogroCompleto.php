<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogroCompleto extends Model
{
    protected $table = 'logros_completos';

    protected $fillable = ['user_id', 'logro_id', 'fecha_completado'];
}
