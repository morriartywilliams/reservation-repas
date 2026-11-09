<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/users');
        $response->assertRedirect('/login');

        $response = $this->get('/menus');
        $response->assertRedirect('/login');
    }

    public function test_users_management_is_restricted_to_administrateur(): void
    {
        $admin = $this->createAdmin();
        $gestionnaire = $this->createGestionnaire();
        $eleve = $this->createEleve();

        // Admin: 200
        $this->actingAs($admin)->get('/users')->assertStatus(200);

        // Gestionnaire: 403
        $this->actingAs($gestionnaire)->get('/users')->assertStatus(403);

        // Élève: 403
        $this->actingAs($eleve->user)->get('/users')->assertStatus(403);
    }

    public function test_students_management_write_operations_are_admin_only(): void
    {
        $admin = $this->createAdmin();
        $gestionnaire = $this->createGestionnaire();
        $eleve = $this->createEleve();

        // Listing students: Admin & Gestionnaire allowed, Élève forbidden
        $this->actingAs($admin)->get('/students')->assertStatus(200);
        $this->actingAs($gestionnaire)->get('/students')->assertStatus(200);
        $this->actingAs($eleve->user)->get('/students')->assertStatus(403);

        // Creating student: Admin allowed, Gestionnaire & Élève forbidden
        $this->actingAs($admin)->get('/students/create')->assertStatus(200);
        $this->actingAs($gestionnaire)->get('/students/create')->assertStatus(403);
        $this->actingAs($eleve->user)->get('/students/create')->assertStatus(403);
    }

    public function test_plats_management_allowed_for_staff_forbidden_for_eleve(): void
    {
        $admin = $this->createAdmin();
        $gestionnaire = $this->createGestionnaire();
        $eleve = $this->createEleve();

        $this->actingAs($admin)->get('/plats')->assertStatus(200);
        $this->actingAs($gestionnaire)->get('/plats')->assertStatus(200);
        $this->actingAs($eleve->user)->get('/plats')->assertStatus(403);
    }

    public function test_reservations_creation_allowed_for_eleve_forbidden_for_staff(): void
    {
        $admin = $this->createAdmin();
        $gestionnaire = $this->createGestionnaire();
        $eleve = $this->createEleve();

        $this->actingAs($eleve->user)->get('/reservations/create')->assertStatus(200);
        $this->actingAs($admin)->get('/reservations/create')->assertStatus(403);
        $this->actingAs($gestionnaire)->get('/reservations/create')->assertStatus(403);
    }

    public function test_paiements_and_statistiques_restricted_to_staff(): void
    {
        $admin = $this->createAdmin();
        $gestionnaire = $this->createGestionnaire();
        $eleve = $this->createEleve();

        // Paiements
        $this->actingAs($admin)->get('/paiements')->assertStatus(200);
        $this->actingAs($gestionnaire)->get('/paiements')->assertStatus(200);
        $this->actingAs($eleve->user)->get('/paiements')->assertStatus(403);

        // Statistiques
        $this->actingAs($admin)->get('/statistiques')->assertStatus(200);
        $this->actingAs($gestionnaire)->get('/statistiques')->assertStatus(200);
        $this->actingAs($eleve->user)->get('/statistiques')->assertStatus(403);
    }
}
