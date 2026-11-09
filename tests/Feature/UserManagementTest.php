<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users_with_search_and_role_filters(): void
    {
        $admin = $this->createAdmin();
        $targetUser = $this->createGestionnaire(['nom' => 'UniqueNameXYZ']);

        $response = $this->actingAs($admin)->get('/users?search=UniqueNameXYZ');

        $response->assertStatus(200);
        $response->assertSee('UniqueNameXYZ');
    }

    public function test_admin_can_create_a_new_user(): void
    {
        $admin = $this->createAdmin();
        $roleGestionnaire = Role::where('nom', 'gestionnaire')->first();

        $response = $this->actingAs($admin)->post('/users', [
            'nom' => 'Boudiaf',
            'prenom' => 'Mohamed',
            'email' => 'm.boudiaf@madrassati.dz',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $roleGestionnaire->id,
            'telephone' => '0555123456',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'm.boudiaf@madrassati.dz',
            'nom' => 'Boudiaf',
            'role_id' => $roleGestionnaire->id,
        ]);
    }

    public function test_admin_can_update_user(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createGestionnaire(['nom' => 'OldName']);

        $response = $this->actingAs($admin)->put("/users/{$user->id}", [
            'nom' => 'NewName',
            'prenom' => $user->prenom,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'telephone' => '0555999888',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nom' => 'NewName',
            'telephone' => '0555999888',
        ]);
    }

    public function test_admin_can_soft_delete_user(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createGestionnaire();

        $response = $this->actingAs($admin)->delete("/users/{$user->id}");

        $response->assertRedirect('/users');
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_non_admin_cannot_create_or_modify_users(): void
    {
        $gestionnaire = $this->createGestionnaire();
        $targetUser = $this->createGestionnaire();

        $this->actingAs($gestionnaire)->post('/users', [
            'nom' => 'Hacker',
            'prenom' => 'John',
            'email' => 'hacker@test.dz',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $gestionnaire->role_id,
        ])->assertStatus(403);

        $this->actingAs($gestionnaire)->delete("/users/{$targetUser->id}")->assertStatus(403);
    }
}
