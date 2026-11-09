<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_access_another_students_reservation_detail(): void
    {
        $eleve1 = $this->createEleve();
        $eleve2 = $this->createEleve();

        $reservation = Reservation::factory()->create([
            'eleve_id' => $eleve1->id,
        ]);

        // Eleve 2 attempts to access Eleve 1's reservation
        $response = $this->actingAs($eleve2->user)->get("/reservations/{$reservation->id}");

        $response->assertStatus(403);
    }

    public function test_user_cannot_mass_assign_unfillable_attributes(): void
    {
        $admin = $this->createAdmin();

        $user = new User([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'mass@test.dz',
            'remember_token' => 'illegal_token_value',
            'created_at' => '2000-01-01 00:00:00',
        ]);

        $this->assertNull($user->remember_token);
        $this->assertNull($user->created_at);
    }

    public function test_unauthenticated_request_cannot_mutate_data(): void
    {
        $response = $this->post('/users', [
            'nom' => 'Injected',
            'email' => 'inject@test.dz',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('users', ['email' => 'inject@test.dz']);
    }
}
