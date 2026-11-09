<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrateur_sees_global_dashboard_metrics(): void
    {
        $admin = $this->createAdmin();
        $this->createEleve();
        $this->createEleve();

        $menu = Menu::factory()->publie()->create();
        $todayRepas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->toDateString(),
            'statut' => 'servi',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.admin');
        $response->assertViewHas('metrics', function ($metrics) {
            return $metrics['total_eleves'] === 2 &&
                   $metrics['menus_actifs'] === 1 &&
                   $metrics['repas_servis_jour'] === 1;
        });
    }

    public function test_gestionnaire_sees_gestionnaire_dashboard(): void
    {
        $gestionnaire = $this->createGestionnaire();

        $response = $this->actingAs($gestionnaire)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.gestionnaire');
    }

    public function test_eleve_sees_personal_dashboard(): void
    {
        $eleve = $this->createEleve();
        $menu = Menu::factory()->publie()->create();
        $repas = Repas::factory()->create([
            'menu_id' => $menu->id,
            'date' => now()->addDays(2)->toDateString(),
        ]);

        Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $repas->id,
            'status' => 'confirmee',
        ]);

        $response = $this->actingAs($eleve->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.eleve');
        $response->assertViewHas('upcomingReservations');
    }
}
