<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Repas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RepasController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Repas::class);

        $query = Repas::with('menu')->withCount([
            'reservations',
            'reservations as reservations_presentes_count' => function ($q) {
                $q->where('attendance_status', 'present');
            },
        ]);

        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        if ($date = $request->input('date')) {
            $query->whereDate('date', $date);
        }

        if ($menuId = $request->input('menu_id')) {
            $query->where('menu_id', $menuId);
        }

        $repasList = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();
        $menus = Menu::orderBy('nom')->get();

        return view('repas.index', compact('repasList', 'menus'));
    }

    public function show(Repas $repas): View
    {
        $this->authorize('view', $repas);

        $repas->load([
            'menu.plats',
            'reservations' => function ($q) {
                $q->with(['eleve.user', 'paiement'])->orderBy('status');
            },
        ]);

        return view('repas.show', compact('repas'));
    }

    public function markServed(Repas $repas): RedirectResponse
    {
        $this->authorize('markServed', $repas);

        if ($repas->statut === 'servi') {
            return back()->with('info', 'Ce repas est déjà marqué comme servi.');
        }

        $repas->update(['statut' => 'servi']);

        return redirect()->route('repas.show', $repas)
            ->with('success', 'Le repas a été marqué comme servi. La distribution et la validation des présences sont désormais clôturées.');
    }
}
