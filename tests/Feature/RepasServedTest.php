<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepasServedTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_mark_repas_as_servi(): void
    {
        $gestionnaire = $this->createGestionnaire();
        $menu = Menu::factory()->publie()->create();
        $repas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'statut' => 'planifie',
        ]);

        $response = $this->actingAs($gestionnaire)->patch("/repas/{$repas->id}/marquer-servi");

        $response->assertRedirect("/repas/{$repas->id}");
        $this->assertDatabaseHas('repas', [
            'id' => $repas->id,
            'statut' => 'servi',
        ]);
    }

    public function test_marking_served_locks_attendance_modifications(): void
    {
        $admin = $this->createAdmin();
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $repas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'statut' => 'planifie',
        ]);

        $res = Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
            'attendance_status' => 'non_verifiee',
        ]);

        // Mark served
        $this->actingAs($admin)->patch("/repas/{$repas->id}/marquer-servi");
        $this->assertEquals('servi', $repas->fresh()->statut);

        // Try to modify attendance
        $response = $this->actingAs($admin)->patch('/reservations/valider-presence', [
            'repas_id' => $repas->id,
            'entries' => [
                ['reservation_id' => $res->id, 'attendance_status' => 'present'],
            ],
        ]);

        $response->assertSessionHasErrors('repas_id');
        $this->assertEquals('non_verifiee', $res->fresh()->attendance_status);
    }
}
