<?php

namespace Tests\Feature;

use App\Models\Plat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_list_and_create_plats(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/plats', [
            'nom' => 'Couscous Algérois',
            'description' => 'Semoule fine, légumes frais et viande d\'agneau.',
        ]);

        $response->assertRedirect('/plats');
        $this->assertDatabaseHas('plats', [
            'nom' => 'Couscous Algérois',
        ]);
    }

    public function test_staff_can_update_plat(): void
    {
        $gestionnaire = $this->createGestionnaire();
        $plat = Plat::factory()->create(['nom' => 'Chorba']);

        $response = $this->actingAs($gestionnaire)->put("/plats/{$plat->id}", [
            'nom' => 'Chorba Frik',
            'description' => 'Soupe traditionnelle au blé vert concassé.',
        ]);

        $response->assertRedirect('/plats');
        $this->assertDatabaseHas('plats', [
            'id' => $plat->id,
            'nom' => 'Chorba Frik',
        ]);
    }

    public function test_staff_can_delete_plat(): void
    {
        $admin = $this->createAdmin();
        $plat = Plat::factory()->create();

        $response = $this->actingAs($admin)->delete("/plats/{$plat->id}");

        $response->assertRedirect('/plats');
        $this->assertDatabaseMissing('plats', [
            'id' => $plat->id,
        ]);
    }
}
