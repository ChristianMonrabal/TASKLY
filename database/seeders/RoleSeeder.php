<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Administrador',
            'Cliente'
        ];

        foreach ($roles as $rol) {
            Role::create(['nombre' => $rol]);
        }
    }
}
