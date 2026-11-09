<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaiementRequest;
use App\Http\Requests\UpdatePaiementRequest;
use App\Models\Paiement;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaiementController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Paiement::class);

        $query = Paiement::with(['reservation.eleve.user', 'reservation.repas', 'recorder']);

        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        if ($moyen = $request->input('moyen')) {
            $query->where('moyen', $moyen);
        }

        if ($date = $request->input('date')) {
            $query->whereDate('date', $date);
        }

        if ($search = $request->input('search')) {
            $query->whereHas('reservation.eleve', function ($eq) use ($search) {
                $eq->where('matricule', 'like', "%{$search}%")
                   ->orWhereHas('user', function ($uq) use ($search) {
                       $uq->where('nom', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%");
                   });
            });
        }

        $paiements = $query->latest()->paginate(15)->withQueryString();

        return view('paiements.index', compact('paiements'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Paiement::class);

        $selectedReservationId = $request->input('reservation_id');

        // Unpaid or pending reservations without a payment record
        $reservations = Reservation::with(['eleve.user', 'repas'])
            ->whereDoesntHave('paiement')
            ->latest('date_reservation')
            ->get();

        return view('paiements.create', compact('reservations', 'selectedReservationId'));
    }

    public function store(StorePaiementRequest $request): RedirectResponse
    {
        $this->authorize('create', Paiement::class);

        $validated = $request->validated();
        $validated['recorded_by'] = $request->user()->id;

        if ($validated['statut'] !== 'paye') {
            $validated['moyen'] = null;
        }

        Paiement::create($validated);

        return redirect()->route('paiements.index')->with('success', 'Règlement enregistré avec succès.');
    }

    public function edit(Paiement $paiement): View
    {
        $this->authorize('update', $paiement);

        $paiement->load(['reservation.eleve.user', 'reservation.repas']);

        return view('paiements.edit', compact('paiement'));
    }

    public function update(UpdatePaiementRequest $request, Paiement $paiement): RedirectResponse
    {
        $this->authorize('update', $paiement);

        $validated = $request->validated();

        if ($validated['statut'] !== 'paye') {
            $validated['moyen'] = null;
        }

        $paiement->update($validated);

        return redirect()->route('paiements.index')->with('success', 'Paiement mis à jour avec succès.');
    }
}
