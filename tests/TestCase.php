<?php

namespace Tests;

use App\Models\Eleve;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Helper to seed standard roles.
     */
    protected function seedRoles(): void
    {
        $this->seed(RoleSeeder::class);
    }

    /**
     * Helper to create an Administrateur user.
     */
    protected function createAdmin(array $attributes = []): User
    {
        $this->seedRoles();
        $role = Role::where('nom', 'administrateur')->first();

        return User::factory()->create(array_merge([
            'role_id' => $role->id,
            'actif' => 1,
        ], $attributes));
    }

    /**
     * Helper to create a Gestionnaire user.
     */
    protected function createGestionnaire(array $attributes = []): User
    {
        $this->seedRoles();
        $role = Role::where('nom', 'gestionnaire')->first();

        return User::factory()->create(array_merge([
            'role_id' => $role->id,
            'actif' => 1,
        ], $attributes));
    }

    /**
     * Helper to create an Élève with linked User.
     */
    protected function createEleve(array $userAttributes = [], array $eleveAttributes = []): Eleve
    {
        $this->seedRoles();
        $role = Role::where('nom', 'eleve')->first();

        $user = User::factory()->create(array_merge([
            'role_id' => $role->id,
            'actif' => 1,
        ], $userAttributes));

        return Eleve::factory()->create(array_merge([
            'user_id' => $user->id,
        ], $eleveAttributes));
    }
}
