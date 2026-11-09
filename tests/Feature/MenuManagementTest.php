<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Plat;
use App\Models\Repas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_create_menu_with_plats(): void
    {
        $admin = $this->createAdmin();
        $plat1 = Plat::factory()->create();
        $plat2 = Plat::factory()->create();

        $response = $this->actingAs($admin)->post('/menus', [
            'nom' => 'Menu Printemps Semaine 1',
            'date_debut' => '2026-04-05',
            'date_fin' => '2026-04-09',
            'plats' => [$plat1->id, $plat2->id],
        ]);

        $response->assertRedirect('/menus');
        $this->assertDatabaseHas('menus', [
            'nom' => 'Menu Printemps Semaine 1',
            'statut' => 'brouillon',
        ]);

        $menu = Menu::where('nom', 'Menu Printemps Semaine 1')->first();
        $this->assertCount(2, $menu->plats);
    }

    public function test_staff_can_open_the_menu_creation_page(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->get('/menus/create')->assertOk();
    }

    public function test_publishing_menu_generates_repas_for_each_date(): void
    {
        $gestionnaire = $this->createGestionnaire();
        $menu = Menu::factory()->create([
            'date_debut' => '2026-05-10',
            'date_fin' => '2026-05-14', // 5 consecutive days (10, 11, 12, 13, 14)
            'statut' => 'brouillon',
            'created_by' => $gestionnaire->id,
        ]);

        $response = $this->actingAs($gestionnaire)->patch("/menus/{$menu->id}/publier");

        $response->assertRedirect("/menus/{$menu->id}");
        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'statut' => 'publie',
        ]);

        // Verify that exactly 5 Repas were generated with statut 'planifie'
        $this->assertEquals(5, Repas::where('menu_id', $menu->id)->count());
        $this->assertDatabaseHas('repas', [
            'menu_id' => $menu->id,
            'date' => '2026-05-10',
            'statut' => 'planifie',
        ]);
        $this->assertDatabaseHas('repas', [
            'menu_id' => $menu->id,
            'date' => '2026-05-14',
            'statut' => 'planifie',
        ]);
    }

    public function test_eleve_can_only_view_published_menus(): void
    {
        $eleve = $this->createEleve();
        $draftMenu = Menu::factory()->create(['nom' => 'Menu Secret Brouillon', 'statut' => 'brouillon']);
        $publishedMenu = Menu::factory()->publie()->create(['nom' => 'Menu Visible Publie']);

        $response = $this->actingAs($eleve->user)->get('/menus');

        $response->assertStatus(200);
        $response->assertSee('Menu Visible Publie');
        $response->assertDontSee('Menu Secret Brouillon');
    }

    public function test_menu_with_generated_repas_cannot_be_deleted(): void
    {
        $admin = $this->createAdmin();
        $menu = Menu::factory()->publie()->create(['created_by' => $admin->id]);
        Repas::factory()->create(['menu_id' => $menu->id]);

        $response = $this->actingAs($admin)->delete("/menus/{$menu->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('menus', ['id' => $menu->id]);
    }
}
