<?php

namespace Tests\Feature;

use App\Models\Paiement;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaiementTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_record_payment_for_reservation(): void
    {
        $gestionnaire = $this->createGestionnaire();
        $eleve = $this->createEleve();
        $reservation = Reservation::factory()->create(['eleve_id' => $eleve->id]);

        $response = $this->actingAs($gestionnaire)->post('/paiements', [
            'reservation_id' => $reservation->id,
            'montant' => 150.00,
            'statut' => 'paye',
            'moyen' => 'especes',
            'date' => '2026-05-01',
        ]);

        $response->assertRedirect('/paiements');
        $this->assertDatabaseHas('paiements', [
            'reservation_id' => $reservation->id,
            'montant' => 150.00,
            'statut' => 'paye',
            'moyen' => 'especes',
            'recorded_by' => $gestionnaire->id,
        ]);
    }

    public function test_payment_requires_moyen_when_statut_is_paye(): void
    {
        $admin = $this->createAdmin();
        $reservation = Reservation::factory()->create();

        $response = $this->actingAs($admin)->post('/paiements', [
            'reservation_id' => $reservation->id,
            'montant' => 150.00,
            'statut' => 'paye',
            'moyen' => '', // missing moyen
        ]);

        $response->assertSessionHasErrors('moyen');
    }

    public function test_only_one_payment_can_exist_per_reservation(): void
    {
        $admin = $this->createAdmin();
        $reservation = Reservation::factory()->create();

        Paiement::factory()->create([
            'reservation_id' => $reservation->id,
            'montant' => 150.00,
        ]);

        $response = $this->actingAs($admin)->post('/paiements', [
            'reservation_id' => $reservation->id,
            'montant' => 200.00,
            'statut' => 'paye',
            'moyen' => 'carte_cantine',
        ]);

        $response->assertSessionHasErrors('reservation_id');
        $this->assertEquals(1, Paiement::where('reservation_id', $reservation->id)->count());
    }

    public function test_staff_can_update_payment(): void
    {
        $admin = $this->createAdmin();
        $paiement = Paiement::factory()->create([
            'montant' => 100.00,
            'statut' => 'impaye',
        ]);

        $response = $this->actingAs($admin)->put("/paiements/{$paiement->id}", [
            'reservation_id' => $paiement->reservation_id,
            'montant' => 120.00,
            'statut' => 'paye',
            'moyen' => 'carte_cantine',
            'date' => '2026-05-02',
        ]);

        $response->assertRedirect('/paiements');
        $this->assertDatabaseHas('paiements', [
            'id' => $paiement->id,
            'montant' => 120.00,
            'statut' => 'paye',
            'moyen' => 'carte_cantine',
        ]);
    }
}
