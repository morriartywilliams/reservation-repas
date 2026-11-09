<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_eleve_can_create_reservation_for_future_meal_of_published_menu(): void
    {
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $futureRepas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(2)->toDateString(),
            'statut' => 'planifie',
        ]);

        $response = $this->actingAs($eleve->user)->post('/reservations', [
            'repas_id' => $futureRepas->id,
        ]);

        $response->assertRedirect('/reservations');
        $this->assertDatabaseHas('reservations', [
            'eleve_id' => $eleve->id,
            'repas_id' => $futureRepas->id,
            'status' => 'confirmee',
            'attendance_status' => 'non_verifiee',
        ]);
    }

    public function test_cannot_book_same_day_or_past_meal(): void
    {
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $todayRepas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->toDateString(),
            'statut' => 'planifie',
        ]);

        $response = $this->actingAs($eleve->user)->post('/reservations', [
            'repas_id' => $todayRepas->id,
        ]);

        $response->assertSessionHasErrors('repas_id');
        $this->assertDatabaseMissing('reservations', [
            'eleve_id' => $eleve->id,
            'repas_id' => $todayRepas->id,
        ]);
    }

    public function test_cannot_book_from_draft_menu(): void
    {
        $eleve = $this->createEleve();
        $draftMenu = Menu::factory()->create(['statut' => 'brouillon']);
        $repas = Repas::factory()->create([
            'menu_id' => $draftMenu->id,
            'date' => now()->addDays(2)->toDateString(),
            'statut' => 'planifie',
        ]);

        $response = $this->actingAs($eleve->user)->post('/reservations', [
            'repas_id' => $repas->id,
        ]);

        $response->assertSessionHasErrors('repas_id');
        $this->assertDatabaseMissing('reservations', [
            'eleve_id' => $eleve->id,
            'repas_id' => $repas->id,
        ]);
    }

    public function test_cannot_book_if_repas_already_marked_servi(): void
    {
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $serviRepas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(2)->toDateString(),
            'statut' => 'servi',
        ]);

        $response = $this->actingAs($eleve->user)->post('/reservations', [
            'repas_id' => $serviRepas->id,
        ]);

        $response->assertSessionHasErrors('repas_id');
    }
}
