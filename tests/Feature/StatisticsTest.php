<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_statistics_calculates_attendance_rate_and_counts_accurately(): void
    {
        $admin = $this->createAdmin();

        $menu = Menu::factory()->publie()->create();
        $serviRepas = Repas::factory()->servi()->create([
            'menu_id' => $menu->id,
            'date' => '2026-05-10',
        ]);

        $eleve1 = $this->createEleve();
        $eleve2 = $this->createEleve();

        // 1 present, 1 absent on a served repas
        Reservation::create([
            'eleve_id' => $eleve1->id,
            'repas_id' => $serviRepas->id,
            'status' => 'confirmee',
            'attendance_status' => 'present',
        ]);

        Reservation::create([
            'eleve_id' => $eleve2->id,
            'repas_id' => $serviRepas->id,
            'status' => 'confirmee',
            'attendance_status' => 'absent',
        ]);

        $response = $this->actingAs($admin)->get('/statistiques');

        $response->assertStatus(200);
        $response->assertViewHas('repasServisCount', 1);
        $response->assertViewHas('totalReservationsCount', 2);
        // Taux: 1 present / 2 confirmed on served repas = 50.0%
        $response->assertViewHas('tauxFrequentation', 50.0);
    }

    public function test_statistics_filters_by_date_range(): void
    {
        $gestionnaire = $this->createGestionnaire();

        $menu = Menu::factory()->publie()->create();
        Repas::factory()->servi()->create([
            'menu_id' => $menu->id,
            'date' => '2026-01-15',
        ]);
        Repas::factory()->servi()->create([
            'menu_id' => $menu->id,
            'date' => '2026-06-20',
        ]);

        $response = $this->actingAs($gestionnaire)->get('/statistiques?date_debut=2026-06-01&date_fin=2026-06-30');

        $response->assertStatus(200);
        $response->assertViewHas('repasServisCount', 1);
    }
}
