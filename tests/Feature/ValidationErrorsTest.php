<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationErrorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_creation_validates_required_fields(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/users', []);

        $response->assertSessionHasErrors(['nom', 'prenom', 'email', 'password', 'role_id']);
    }

    public function test_student_creation_validates_student_fields(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/students', []);

        $response->assertSessionHasErrors(['nom', 'prenom', 'email', 'password', 'matricule', 'classe']);
    }

    public function test_menu_creation_validates_dates_and_plats(): void
    {
        $admin = $this->createAdmin();

        // Invalid: date_fin before date_debut, empty plats
        $response = $this->actingAs($admin)->post('/menus', [
            'nom' => 'Invalid Menu',
            'date_debut' => '2026-05-10',
            'date_fin' => '2026-05-01',
            'plats' => [],
        ]);

        $response->assertSessionHasErrors(['date_fin', 'plats']);
    }

    public function test_plat_creation_validates_nom(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/plats', [
            'nom' => '',
        ]);

        $response->assertSessionHasErrors('nom');
    }

    public function test_paiement_creation_validates_fields(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/paiements', []);

        $response->assertSessionHasErrors(['reservation_id', 'montant', 'statut']);
    }
}
