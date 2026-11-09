<?php

namespace Tests\Feature;

use App\Models\Eleve;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_gestionnaire_can_list_and_search_students(): void
    {
        $admin = $this->createAdmin();
        $gestionnaire = $this->createGestionnaire();
        $eleve = $this->createEleve([], ['matricule' => 'ELEV-TEST-999']);

        // Admin search
        $response = $this->actingAs($admin)->get('/students?search=ELEV-TEST-999');
        $response->assertStatus(200);
        $response->assertSee('ELEV-TEST-999');

        // Gestionnaire search
        $response = $this->actingAs($gestionnaire)->get('/students?search=ELEV-TEST-999');
        $response->assertStatus(200);
        $response->assertSee('ELEV-TEST-999');
    }

    public function test_admin_can_create_student_with_linked_user_account(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/students', [
            'nom' => 'Zerrouki',
            'prenom' => 'Anis',
            'email' => 'anis.zerrouki@madrassati.dz',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'matricule' => 'MAT-2026-99',
            'classe' => '2AS-Math',
            'date_naissance' => '2008-05-15',
            'telephone' => '0777123456',
        ]);

        $response->assertRedirect('/students');
        $this->assertDatabaseHas('users', [
            'email' => 'anis.zerrouki@madrassati.dz',
            'nom' => 'Zerrouki',
        ]);
        $this->assertDatabaseHas('eleves', [
            'matricule' => 'MAT-2026-99',
            'classe' => '2AS-Math',
        ]);
    }

    public function test_admin_can_update_student(): void
    {
        $admin = $this->createAdmin();
        $eleve = $this->createEleve([], ['classe' => '1AS-1']);

        $response = $this->actingAs($admin)->put("/students/{$eleve->id}", [
            'nom' => $eleve->user->nom,
            'prenom' => $eleve->user->prenom,
            'email' => $eleve->user->email,
            'matricule' => $eleve->matricule,
            'classe' => '2AS-Scientifique',
            'date_naissance' => '2008-01-01',
        ]);

        $response->assertRedirect('/students');
        $this->assertDatabaseHas('eleves', [
            'id' => $eleve->id,
            'classe' => '2AS-Scientifique',
        ]);
    }

    public function test_admin_can_soft_delete_student(): void
    {
        $admin = $this->createAdmin();
        $eleve = $this->createEleve();

        $response = $this->actingAs($admin)->delete("/students/{$eleve->id}");

        $response->assertRedirect('/students');
        $this->assertSoftDeleted('eleves', ['id' => $eleve->id]);
        $this->assertSoftDeleted('users', ['id' => $eleve->user_id]);
    }

    public function test_gestionnaire_cannot_create_or_edit_student(): void
    {
        $gestionnaire = $this->createGestionnaire();
        $eleve = $this->createEleve();

        $this->actingAs($gestionnaire)->post('/students', [
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'test@test.dz',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'matricule' => 'MAT-TEST',
            'classe' => '1AS',
        ])->assertStatus(403);

        $this->actingAs($gestionnaire)->delete("/students/{$eleve->id}")->assertStatus(403);
    }
}
