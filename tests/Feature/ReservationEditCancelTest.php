<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationEditCancelTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_edit_reservation_within_valid_window(): void
    {
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $repas1 = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(2)->toDateString(),
            'statut' => 'planifie',
        ]);
        $repas2 = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(3)->toDateString(),
            'statut' => 'planifie',
        ]);

        $reservation = Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $repas1->id,
            'status' => 'confirmee',
        ]);

        $response = $this->actingAs($eleve->user)->put("/reservations/{$reservation->id}", [
            'repas_id' => $repas2->id,
        ]);

        $response->assertRedirect('/reservations');
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'repas_id' => $repas2->id,
        ]);
    }

    public function test_student_can_cancel_reservation_which_sets_status_annulee_without_deleting_row(): void
    {
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $repas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(2)->toDateString(),
            'statut' => 'planifie',
        ]);

        $reservation = Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
        ]);

        $response = $this->actingAs($eleve->user)->delete("/reservations/{$reservation->id}");

        $response->assertRedirect('/reservations');
        // Row still exists in DB, but with status 'annulee' per §12.5
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'annulee',
        ]);
    }

    public function test_student_cannot_edit_or_cancel_on_or_after_meal_day(): void
    {
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $todayRepas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->toDateString(), // meal day arrived!
            'statut' => 'planifie',
        ]);

        $reservation = Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $todayRepas->id,
            'status' => 'confirmee',
        ]);

        // Attempt edit
        $response = $this->actingAs($eleve->user)->get("/reservations/{$reservation->id}/edit");
        $response->assertStatus(403);

        // Attempt cancel
        $response = $this->actingAs($eleve->user)->delete("/reservations/{$reservation->id}");
        $response->assertStatus(403);
    }

    public function test_student_cannot_cancel_another_students_reservation(): void
    {
        $eleve1 = $this->createEleve();
        $eleve2 = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $repas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(2)->toDateString(),
            'statut' => 'planifie',
        ]);

        $reservation = Reservation::create([
            'eleve_id' => $eleve1->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
        ]);

        // Eleve 2 tries to cancel Eleve 1's reservation
        $response = $this->actingAs($eleve2->user)->delete("/reservations/{$reservation->id}");
        $response->assertStatus(403);
    }
}
