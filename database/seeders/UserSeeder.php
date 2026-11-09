<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('nom', 'administrateur')->firstOrFail();
        $gestionnaireRole = Role::where('nom', 'gestionnaire')->firstOrFail();
        $eleveRole = Role::where('nom', 'eleve')->firstOrFail();

        // 1 Admin demo account
        User::firstOrCreate(
            ['email' => 'admin@madrassati.dz'],
            [
                'nom' => 'Mohamed',
                'prenom' => 'Rm',
                'password' => Hash::make('password'),
                'telephone' => '0550123456',
                'actif' => 1,
                'role_id' => $adminRole->id,
            ]
        );

        // 1 Gestionnaire demo account
        User::firstOrCreate(
            ['email' => 'gestionnaire@madrassati.dz'],
            [
                'nom' => 'Mansouri',
                'prenom' => 'Amel',
                'password' => Hash::make('password'),
                'telephone' => '0660123456',
                'actif' => 1,
                'role_id' => $gestionnaireRole->id,
            ]
        );

        // 1 Élève demo account with associated Eleve record
        $eleveUser = User::firstOrCreate(
            ['email' => 'eleve@madrassati.dz'],
            [
                'nom' => 'Saidi',
                'prenom' => 'Yacine',
                'password' => Hash::make('password'),
                'telephone' => '0770123456',
                'actif' => 1,
                'role_id' => $eleveRole->id,
            ]
        );

        Eleve::firstOrCreate(
            ['user_id' => $eleveUser->id],
            [
                'matricule' => 'ELEV-2026-001',
                'classe' => '2AS-Scientifique',
                'date_naissance' => '2008-04-12',
            ]
        );
    }
}
