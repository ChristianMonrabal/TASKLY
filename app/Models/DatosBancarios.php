<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosBancarios extends Model
{
    protected $table = 'datos_bancarios';

    protected $fillable = [
        'usuario_id', 
        'iban', 
        'titular', 
        'nombre_banco', 
        'stripe_account_id',
        'direccion_fiscal',
        'codigo_postal_fiscal',
        'ciudad_fiscal',
        'nif_fiscal'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
