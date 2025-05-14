<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosBancarios extends Model
{
    protected $table = 'datos_bancarios';

    protected $fillable = ['usuario_id', 'iban', 'titular', 'nombre_banco', 'stripe_account_id'];
}
