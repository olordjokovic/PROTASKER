<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 *  inserta los roles básicos del sistema.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'gestor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'usuario', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}