<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DatosBancarios;
use App\Models\User;

class DatosBancariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos los datos bancarios anteriores
        DatosBancarios::truncate();
        
        // ID de la cuenta principal de TASKLY que recibirá la comisión
        $tasklyAccountId = 'acct_1RLTsZIxgDk5hYr7';
        
        // Mapeamos los IDs de Stripe para los usuarios específicos
        // Mapeamos los IDs de Stripe para los usuarios específicos - cuentas reales
        $stripeAccounts = [
            1 => $tasklyAccountId,           // Christian/TASKLY (cuenta principal)
            2 => 'acct_1RLmSdIJN9D9qNg6',  // Alex
            3 => 'acct_1RLmMlIE2Y4Yj6ja',  // Daniel
        ];
        
        // Creamos datos bancarios para todos los trabajadores
        $usuarios = User::where('rol_id', 2)->get();
        
        foreach ($usuarios as $usuario) {
            // Verificamos si existe un ID de Stripe específico para este usuario
            $stripeAccountId = $stripeAccounts[$usuario->id] ?? null;
            
            DatosBancarios::create([
                'usuario_id' => $usuario->id,
                'titular' => $usuario->nombre,
                'iban' => 'ES' . str_pad(mt_rand(1000000000000000, 9999999999999999), 24, '0', STR_PAD_LEFT),
                'nombre_banco' => 'Banco Ejemplo',
                'stripe_account_id' => $stripeAccountId
            ]);
        }
    }
}
