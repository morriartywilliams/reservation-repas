<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;
use App\Models\Plat;
use App\Services\MenuPublishingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Menu::class);

        $query = Menu::with(['creator', 'plats'])->withCount('repas');

        // Scoping for student: only published menus
        if ($request->user()->role?->nom === 'eleve') {
            $query->where('statut', 'publie');
        } elseif ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        if ($search = $request->input('search')) {
            $query->where('nom', 'like', "%{$search}%");
        }

        if ($dateDebut = $request->input('date_debut')) {
            $query->where('date_debut', '>=', $dateDebut);
        }

        if ($dateFin = $request->input('date_fin')) {
            $query->where('date_fin', '<=', $dateFin);
        }

        $menus = $query->latest('date_debut')->paginate(15)->withQueryString();

        return view('menus.index', compact('menus'));
    }

    public function create(): View
    {
        $this->authorize('create', Menu::class);

        $plats = Plat::orderBy('nom')->get();

        return view('menus.create', compact('plats'));
    }

    public function store(StoreMenuRequest $request): RedirectResponse
    {
        $this->authorize('create', Menu::class);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            $menu = Menu::create([
                'nom' => $validated['nom'],
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'],
                'statut' => 'brouillon',
                'created_by' => $request->user()->id,
            ]);

            $menu->plats()->sync($validated['plats']);
        });

        return redirect()->route('menus.index')->with('success', 'Menu créé avec succès (état brouillon).');
    }

    public function show(Menu $menu): View
    {
        $this->authorize('view', $menu);

        $menu->load(['creator', 'plats', 'repas' => function ($q) {
            $q->orderBy('date');
        }]);

        return view('menus.show', compact('menu'));
    }

    public function edit(Menu $menu): View
    {
        $this->authorize('update', $menu);

        $menu->load('plats');
        $plats = Plat::orderBy('nom')->get();

        return view('menus.edit', compact('menu', 'plats'));
    }

    public function update(UpdateMenuRequest $request, Menu $menu): RedirectResponse
    {
        $this->authorize('update', $menu);

        $validated = $request->validated();

        DB::transaction(function () use ($menu, $validated) {
            $menu->update([
                'nom' => $validated['nom'],
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'],
            ]);

            $menu->plats()->sync($validated['plats']);
        });

        return redirect()->route('menus.index')->with('success', 'Menu mis à jour avec succès.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $this->authorize('delete', $menu);

        // Repas are historical meal instances and their foreign key is
        // deliberately restrictive.  Avoid a database exception (and a
        // partially detached pivot) when a published menu is still in use.
        if ($menu->repas()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce menu car des repas ont dÃ©jÃ  Ã©tÃ© gÃ©nÃ©rÃ©s.');
        }

        DB::transaction(function () use ($menu) {
            $menu->plats()->detach();
            $menu->delete();
        });

        return redirect()->route('menus.index')->with('success', 'Menu supprimé avec succès.');
    }

    public function publish(Menu $menu, MenuPublishingService $service): RedirectResponse
    {
        $this->authorize('publish', $menu);

        $service->publish($menu);

        return redirect()->route('menus.show', $menu)->with('success', 'Menu publié et repas hebdomadaires générés avec succès.');
    }
}
