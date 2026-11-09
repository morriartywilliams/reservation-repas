<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Menu;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $roleName = $user->role?->nom;
        $today = now()->toDateString();

        if ($roleName === 'eleve') {
            $eleve = $user->eleve;

            $upcomingReservations = $eleve
                ? Reservation::with(['repas.menu.plats', 'paiement'])
                    ->where('eleve_id', $eleve->id)
                    ->whereHas('repas', function ($q) use ($today) {
                        $q->whereDate('date', '>=', $today);
                    })
                    ->orderBy(
                        Repas::select('date')->whereColumn('repas.id', 'reservations.repas_id')
                    )
                    ->get()
                : collect();

            $recentReservations = $eleve
                ? Reservation::with(['repas.menu', 'paiement'])
                    ->where('eleve_id', $eleve->id)
                    ->latest('date_reservation')
                    ->take(5)
                    ->get()
                : collect();

            $unpaidCount = $eleve
                ? Reservation::where('eleve_id', $eleve->id)
                    ->where('status', 'confirmee')
                    ->where(function ($q) {
                        $q->whereDoesntHave('paiement')
                          ->orWhereHas('paiement', function ($pq) {
                              $pq->where('statut', 'impaye');
                          });
                    })
                    ->count()
                : 0;

            $activeMenu = Menu::with('plats')
                ->where('statut', 'publie')
                ->where('date_debut', '<=', $today)
                ->where('date_fin', '>=', $today)
                ->first();

            return view('dashboard.eleve', compact(
                'eleve',
                'upcomingReservations',
                'recentReservations',
                'unpaidCount',
                'activeMenu'
            ));
        }

        // Global metrics for Administrateur and Gestionnaire (per §16)
        $totalEleves = Eleve::count();
        $activeMenusCount = Menu::where('statut', 'publie')->count();
        
        $reservationsDuJour = Reservation::where('status', 'confirmee')
            ->whereHas('repas', function ($q) use ($today) {
                $q->whereDate('date', $today);
            })
            ->count();

        $repasServisAujourdhui = Repas::where('statut', 'servi')
            ->whereDate('date', $today)
            ->count();

        // Extra contextual widgets
        $recentReservations = Reservation::with(['eleve.user', 'repas'])
            ->latest('date_reservation')
            ->take(6)
            ->get();

        $todayRepas = Repas::with(['menu', 'reservations'])
            ->whereDate('date', $today)
            ->first();

        $metrics = [
            'total_eleves' => $totalEleves,
            'menus_actifs' => $activeMenusCount,
            'reservations_du_jour' => $reservationsDuJour,
            'repas_servis_jour' => $repasServisAujourdhui,
        ];

        $viewName = $roleName === 'administrateur' ? 'dashboard.admin' : 'dashboard.gestionnaire';

        return view($viewName, compact('metrics', 'recentReservations', 'todayRepas'));
    }
}
