<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_bulk_validate_attendance_for_planned_repas(): void
    {
        $admin = $this->createAdmin();
        $eleve1 = $this->createEleve();
        $eleve2 = $this->createEleve();

        $menu = Menu::factory()->publie()->create();
        $repas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'statut' => 'planifie',
        ]);

        $res1 = Reservation::create([
            'eleve_id' => $eleve1->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
            'attendance_status' => 'non_verifiee',
        ]);

        $res2 = Reservation::create([
            'eleve_id' => $eleve2->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
            'attendance_status' => 'non_verifiee',
        ]);

        $response = $this->actingAs($admin)->patch('/reservations/valider-presence', [
            'repas_id' => $repas->id,
            'entries' => [
                ['reservation_id' => $res1->id, 'attendance_status' => 'present'],
                ['reservation_id' => $res2->id, 'attendance_status' => 'absent'],
            ],
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservations', [
            'id' => $res1->id,
            'attendance_status' => 'present',
        ]);
        $this->assertDatabaseHas('reservations', [
            'id' => $res2->id,
            'attendance_status' => 'absent',
        ]);
    }

    public function test_attendance_cannot_be_validated_if_repas_is_already_servi(): void
    {
        $admin = $this->createAdmin();
        $eleve = $this->createEleve();

        $menu = Menu::factory()->publie()->create();
        $repas = Repas::factory()->servi()->create([
            'menu_id' => $menu->id,
        ]);

        $res = Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
            'attendance_status' => 'non_verifiee',
        ]);

        $response = $this->actingAs($admin)->patch('/reservations/valider-presence', [
            'repas_id' => $repas->id,
            'entries' => [
                ['reservation_id' => $res->id, 'attendance_status' => 'present'],
            ],
        ]);

        $response->assertSessionHasErrors('repas_id');
        $this->assertDatabaseHas('reservations', [
            'id' => $res->id,
            'attendance_status' => 'non_verifiee',
        ]);
    }

    public function test_attendance_entries_must_belong_to_the_selected_planned_meal(): void
    {
        $admin = $this->createAdmin();
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $selectedRepas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDay()->toDateString(),
            'statut' => 'planifie',
        ]);
        $otherRepas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(2)->toDateString(),
            'statut' => 'planifie',
        ]);
        $otherReservation = Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $otherRepas->id,
            'status' => 'confirmee',
            'attendance_status' => 'non_verifiee',
        ]);

        $response = $this->actingAs($admin)->patch('/reservations/valider-presence', [
            'repas_id' => $selectedRepas->id,
            'entries' => [
                ['reservation_id' => $otherReservation->id, 'attendance_status' => 'present'],
            ],
        ]);

        $response->assertSessionHasErrors('entries');
        $this->assertDatabaseHas('reservations', [
            'id' => $otherReservation->id,
            'attendance_status' => 'non_verifiee',
        ]);
    }
}
