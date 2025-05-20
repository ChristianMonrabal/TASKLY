<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogroCompleto extends Model
{
    protected $table = 'logros_completos';

    protected $fillable = ['logro_id', 'usuario_id'];

    public function logro()
    {
        return $this->belongsTo(Logro::class, 'logro_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
