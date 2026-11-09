<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Eleve;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Eleve::class);

        $query = Eleve::with('user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('matricule', 'like', "%{$search}%")
                  ->orWhere('classe', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('nom', 'like', "%{$search}%")
                         ->orWhere('prenom', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($classe = $request->input('classe')) {
            $query->where('classe', $classe);
        }

        $students = $query->latest()->paginate(15)->withQueryString();
        $classes = Eleve::distinct()->pluck('classe');

        return view('students.index', compact('students', 'classes'));
    }

    public function create(): View
    {
        $this->authorize('create', Eleve::class);

        return view('students.create');
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $this->authorize('create', Eleve::class);

        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $eleveRole = Role::where('nom', 'eleve')->firstOrFail();

            $user = User::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telephone' => $validated['telephone'] ?? null,
                'role_id' => $eleveRole->id,
                'actif' => 1,
            ]);

            Eleve::create([
                'user_id' => $user->id,
                'matricule' => $validated['matricule'],
                'classe' => $validated['classe'],
                'date_naissance' => $validated['date_naissance'] ?? null,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Élève enregistré avec succès.');
    }

    public function show(Eleve $eleve): View
    {
        $this->authorize('view', $eleve);

        $eleve->load(['user', 'reservations.repas.menu', 'reservations.paiement']);

        return view('students.show', compact('eleve'));
    }

    public function edit(Eleve $eleve): View
    {
        $this->authorize('update', $eleve);

        $eleve->load('user');

        return view('students.edit', compact('eleve'));
    }

    public function update(UpdateStudentRequest $request, Eleve $eleve): RedirectResponse
    {
        $this->authorize('update', $eleve);

        $validated = $request->validated();

        DB::transaction(function () use ($eleve, $validated) {
            $userData = [
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'] ?? null,
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $eleve->user->update($userData);

            $eleve->update([
                'matricule' => $validated['matricule'],
                'classe' => $validated['classe'],
                'date_naissance' => $validated['date_naissance'] ?? null,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Élève mis à jour avec succès.');
    }

    public function destroy(Eleve $eleve): RedirectResponse
    {
        $this->authorize('delete', $eleve);

        DB::transaction(function () use ($eleve) {
            $eleve->delete();
            $eleve->user?->delete();
        });

        return redirect()->route('students.index')->with('success', 'Élève supprimé avec succès.');
    }
}
