<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Requests\ValidateAttendanceRequest;
use App\Models\Repas;
use App\Models\Reservation;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Reservation::class);

        $user = $request->user();
        $query = Reservation::with(['eleve.user', 'repas.menu', 'paiement']);

        // Scope query by role: student sees own only, staff sees all
        if ($user->role?->nom === 'eleve') {
            $query->whereHas('eleve', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($attendance = $request->input('attendance_status')) {
            $query->where('attendance_status', $attendance);
        }

        if ($date = $request->input('date')) {
            $query->whereHas('repas', function ($q) use ($date) {
                $q->whereDate('date', $date);
            });
        }

        if ($search = $request->input('search')) {
            $query->whereHas('eleve', function ($eq) use ($search) {
                $eq->where('matricule', 'like', "%{$search}%")
                   ->orWhere('classe', 'like', "%{$search}%")
                   ->orWhereHas('user', function ($uq) use ($search) {
                       $uq->where('nom', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%");
                   });
            });
        }

        $reservations = $query->latest('date_reservation')->paginate(15)->withQueryString();

        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Reservation::class);

        $user = $request->user();
        $eleve = $user->eleve;

        $today = now()->toDateString();

        // Available meals: menu published, repas planifie, repas date > today, student not already booked
        $bookedRepasIds = $eleve
            ? Reservation::where('eleve_id', $eleve->id)->pluck('repas_id')->toArray()
            : [];

        $availableRepas = Repas::with('menu.plats')
            ->whereHas('menu', function ($q) {
                $q->where('statut', 'publie');
            })
            ->where('statut', 'planifie')
            ->whereDate('date', '>', $today)
            ->whereNotIn('id', $bookedRepasIds)
            ->orderBy('date')
            ->get();

        return view('reservations.create', compact('availableRepas'));
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $this->authorize('create', Reservation::class);

        $user = $request->user();
        $eleve = $user->eleve;

        Reservation::create([
            'eleve_id' => $eleve->id,
            'repas_id' => $request->input('repas_id'),
            'status' => 'confirmee',
            'attendance_status' => 'non_verifiee',
            'date_reservation' => now(),
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation effectuée avec succès (statut confirmée).');
    }

    public function show(Reservation $reservation): View
    {
        $this->authorize('view', $reservation);

        $reservation->load(['eleve.user', 'repas.menu.plats', 'paiement.recorder']);

        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation): View
    {
        $this->authorize('update', $reservation);

        $user = auth()->user();
        $eleve = $user->eleve;
        $today = now()->toDateString();

        $bookedRepasIds = Reservation::where('eleve_id', $eleve->id)
            ->where('id', '!=', $reservation->id)
            ->pluck('repas_id')
            ->toArray();

        $availableRepas = Repas::with('menu.plats')
            ->whereHas('menu', function ($q) {
                $q->where('statut', 'publie');
            })
            ->where('statut', 'planifie')
            ->whereDate('date', '>', $today)
            ->whereNotIn('id', $bookedRepasIds)
            ->orderBy('date')
            ->get();

        return view('reservations.edit', compact('reservation', 'availableRepas'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation): RedirectResponse
    {
        $this->authorize('update', $reservation);

        $reservation->update([
            'repas_id' => $request->input('repas_id'),
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation modifiée avec succès.');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $this->authorize('delete', $reservation);

        // §12.5: Cancelling never deletes the row — it sets status = 'annulee', preserving history
        $reservation->update([
            'status' => 'annulee',
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation annulée avec succès.');
    }

    public function validateAttendance(ValidateAttendanceRequest $request, AttendanceService $attendanceService): RedirectResponse
    {
        $this->authorize('validateAttendance', Reservation::class);

        $updatedCount = $attendanceService->validate(
            (int) $request->input('repas_id'),
            $request->input('entries')
        );

        return back()->with('success', "Présences enregistrées avec succès ({$updatedCount} élèves mis à jour).");
    }
}
