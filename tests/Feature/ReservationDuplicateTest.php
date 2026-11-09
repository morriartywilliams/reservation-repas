<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationDuplicateTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_request_rejects_second_booking_for_same_meal(): void
    {
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $repas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(3)->toDateString(),
            'statut' => 'planifie',
        ]);

        // First booking succeeds
        Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
            'attendance_status' => 'non_verifiee',
            'date_reservation' => now(),
        ]);

        // Second booking attempt through HTTP POST
        $response = $this->actingAs($eleve->user)->post('/reservations', [
            'repas_id' => $repas->id,
        ]);

        $response->assertSessionHasErrors('repas_id');
        $this->assertEquals(1, Reservation::where('eleve_id', $eleve->id)->where('repas_id', $repas->id)->count());
    }

    public function test_database_unique_constraint_blocks_duplicate_booking(): void
    {
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $repas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(3)->toDateString(),
        ]);

        Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
        ]);

        $this->expectException(QueryException::class);

        Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
        ]);
    }
}
