@extends('layouts.app')

@section('title', 'Rôles & Permissions')
@section('header', 'Rôles Système')
@section('subtitle', 'Liste des rôles du système de cantine Madrassati (administrateur, gestionnaire, élève).')

@section('content')
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden max-w-4xl">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <p class="text-xs text-gray-600">
                Ces rôles sont fixes conformément aux spécifications WorldSkills et définissent l'accès aux fonctionnalités de l'application.
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identifiant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateurs actifs</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions clés</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-gray-500">
                                #{{ $role->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($role->nom) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $role->users_count }} utilisateur(s)
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                @if($role->nom === 'administrateur')
                                    Accès total : gestion utilisateurs, élèves, menus, plats, repas, paiements, statistiques.
                                @elseif($role->nom === 'gestionnaire')
                                    Gestion opérationnelle : consultation élèves, menus, plats, repas servis, validation présences, paiements, statistiques.
                                @else
                                    Espace élève : consultation menus publiés, réservation et annulation de repas dans la fenêtre autorisée.
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
