<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlatRequest;
use App\Http\Requests\UpdatePlatRequest;
use App\Models\Plat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Plat::class);

        $query = Plat::withCount('menus');

        if ($search = $request->input('search')) {
            $query->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $plats = $query->latest()->paginate(15)->withQueryString();

        return view('plats.index', compact('plats'));
    }

    public function create(): View
    {
        $this->authorize('create', Plat::class);

        return view('plats.create');
    }

    public function store(StorePlatRequest $request): RedirectResponse
    {
        $this->authorize('create', Plat::class);

        Plat::create($request->validated());

        return redirect()->route('plats.index')->with('success', 'Plat ajouté avec succès.');
    }

    public function edit(Plat $plat): View
    {
        $this->authorize('update', $plat);

        return view('plats.edit', compact('plat'));
    }

    public function update(UpdatePlatRequest $request, Plat $plat): RedirectResponse
    {
        $this->authorize('update', $plat);

        $plat->update($request->validated());

        return redirect()->route('plats.index')->with('success', 'Plat mis à jour avec succès.');
    }

    public function destroy(Plat $plat): RedirectResponse
    {
        $this->authorize('delete', $plat);

        if ($plat->menus()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce plat car il est rattaché à un ou plusieurs menus.');
        }

        $plat->delete();

        return redirect()->route('plats.index')->with('success', 'Plat supprimé avec succès.');
    }
}
