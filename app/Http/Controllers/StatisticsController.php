<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index(Request $request): View
    {
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        // Repas servis count query (filterable by date range)
        $repasServisQuery = Repas::where('statut', 'servi');
        if ($dateDebut) {
            $repasServisQuery->whereDate('date', '>=', $dateDebut);
        }
        if ($dateFin) {
            $repasServisQuery->whereDate('date', '<=', $dateFin);
        }
        $repasServisCount = $repasServisQuery->count();

        // Total reservations query (filterable by date range)
        $reservationsQuery = Reservation::query();
        if ($dateDebut) {
            $reservationsQuery->whereHas('repas', fn ($q) => $q->whereDate('date', '>=', $dateDebut));
        }
        if ($dateFin) {
            $reservationsQuery->whereHas('repas', fn ($q) => $q->whereDate('date', '<=', $dateFin));
        }
        $totalReservationsCount = (clone $reservationsQuery)->count();

        // Attendance rate (taux de fréquentation per §16):
        // count(reservations where attendance_status = present) / count(reservations where status = 'confirmee' and repas.statut = 'servi') * 100
        $presentCountQuery = Reservation::where('attendance_status', 'present');
        $confirmedServedRepasQuery = Reservation::where('status', 'confirmee')
            ->whereHas('repas', fn ($q) => $q->where('statut', 'servi'));

        if ($dateDebut) {
            $presentCountQuery->whereHas('repas', fn ($q) => $q->whereDate('date', '>=', $dateDebut));
            $confirmedServedRepasQuery->whereHas('repas', fn ($q) => $q->whereDate('date', '>=', $dateDebut));
        }
        if ($dateFin) {
            $presentCountQuery->whereHas('repas', fn ($q) => $q->whereDate('date', '<=', $dateFin));
            $confirmedServedRepasQuery->whereHas('repas', fn ($q) => $q->whereDate('date', '<=', $dateFin));
        }

        $presentCount = $presentCountQuery->count();
        $confirmedServedCount = $confirmedServedRepasQuery->count();

        $tauxFrequentation = $confirmedServedCount > 0
            ? round(($presentCount / $confirmedServedCount) * 100, 1)
            : 0.0;

        // Breakdown stats
        $statusBreakdown = [
            'confirmee' => (clone $reservationsQuery)->where('status', 'confirmee')->count(),
            'annulee' => (clone $reservationsQuery)->where('status', 'annulee')->count(),
            'en_attente' => (clone $reservationsQuery)->where('status', 'en_attente')->count(),
        ];

        $attendanceBreakdown = [
            'present' => (clone $reservationsQuery)->where('attendance_status', 'present')->count(),
            'absent' => (clone $reservationsQuery)->where('attendance_status', 'absent')->count(),
            'non_verifiee' => (clone $reservationsQuery)->where('attendance_status', 'non_verifiee')->count(),
        ];

        $paiementStats = [
            'paye' => Paiement::where('statut', 'paye')->sum('montant'),
            'impaye' => Paiement::where('statut', 'impaye')->sum('montant'),
            'count_paye' => Paiement::where('statut', 'paye')->count(),
            'count_impaye' => Paiement::where('statut', 'impaye')->count(),
            'count_exonere' => Paiement::where('statut', 'exonere')->count(),
        ];

        return view('statistiques.index', compact(
            'repasServisCount',
            'totalReservationsCount',
            'tauxFrequentation',
            'presentCount',
            'confirmedServedCount',
            'statusBreakdown',
            'attendanceBreakdown',
            'paiementStats',
            'dateDebut',
            'dateFin'
        ));
    }
}
