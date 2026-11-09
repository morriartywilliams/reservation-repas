<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nom' => 'administrateur'],
            ['nom' => 'gestionnaire'],
            ['nom' => 'eleve'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nom' => $role['nom']], $role);
        }
    }
}
