@extends('layouts.app')

@section('title', 'Tableau de bord Administrateur')
@section('header', 'Tableau de bord — Administrateur')
@section('subtitle', 'Vue d\'ensemble globale du système de cantine et indicateurs opérationnels.')

@section('content')
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Élèves -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3 text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Nombre d'élèves</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $metrics['total_eleves'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 text-xs">
                <a href="{{ route('students.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Gérer les élèves &rarr;</a>
            </div>
        </div>

        <!-- Menus Actifs -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-emerald-100 rounded-md p-3 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Menus actifs</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $metrics['menus_actifs'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 text-xs">
                <a href="{{ route('menus.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Voir les menus &rarr;</a>
            </div>
        </div>

        <!-- Réservations du jour -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-100 rounded-md p-3 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Réservations du jour</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $metrics['reservations_du_jour'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 text-xs">
                <a href="{{ route('reservations.index', ['date' => now()->toDateString()]) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Vérifier les réservations &rarr;</a>
            </div>
        </div>

        <!-- Repas servis du jour -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3 text-purple-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Repas servis (aujourd'hui)</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $metrics['repas_servis_jour'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 text-xs">
                <a href="{{ route('repas.index', ['date' => now()->toDateString()]) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Repas du jour &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick actions -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-5">
            <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Actions rapides Administrateur
            </h2>
            <div class="space-y-2">
                <a href="{{ route('users.create') }}" class="block p-3 rounded-md bg-gray-50 hover:bg-emerald-50 text-sm font-medium text-gray-800 hover:text-emerald-700 border border-gray-100 transition-colors">
                    + Créer un compte utilisateur
                </a>
                <a href="{{ route('students.create') }}" class="block p-3 rounded-md bg-gray-50 hover:bg-emerald-50 text-sm font-medium text-gray-800 hover:text-emerald-700 border border-gray-100 transition-colors">
                    + Inscrire un nouvel élève
                </a>
                <a href="{{ route('menus.create') }}" class="block p-3 rounded-md bg-gray-50 hover:bg-emerald-50 text-sm font-medium text-gray-800 hover:text-emerald-700 border border-gray-100 transition-colors">
                    + Composer un nouveau menu
                </a>
                <a href="{{ route('plats.create') }}" class="block p-3 rounded-md bg-gray-50 hover:bg-emerald-50 text-sm font-medium text-gray-800 hover:text-emerald-700 border border-gray-100 transition-colors">
                    + Ajouter un plat au catalogue
                </a>
                <a href="{{ route('statistiques.index') }}" class="block p-3 rounded-md bg-gray-50 hover:bg-emerald-50 text-sm font-medium text-gray-800 hover:text-emerald-700 border border-gray-100 transition-colors">
                    &rarr; Consulter les statistiques et taux de fréquentation
                </a>
            </div>
        </div>

        <!-- Recent Reservations -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-lg border border-gray-200 p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-base font-semibold text-gray-900">
                    Dernières réservations enregistrées
                </h2>
                <a href="{{ route('reservations.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                    Tout voir &rarr;
                </a>
            </div>

            @if($recentReservations->isEmpty())
                <x-empty-state title="Aucune réservation" message="Aucune réservation n'a encore été enregistrée dans le système." />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="py-2">Élève</th>
                                <th class="py-2">Date repas</th>
                                <th class="py-2">Statut</th>
                                <th class="py-2">Présence</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentReservations as $res)
                                <tr>
                                    <td class="py-2 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $res->eleve?->user?->nom_complet }}</div>
                                        <div class="text-xs text-gray-500">{{ $res->eleve?->classe }} · {{ $res->eleve?->matricule }}</div>
                                    </td>
                                    <td class="py-2 whitespace-nowrap text-gray-700">
                                        {{ $res->repas?->date?->format('d/m/Y') }}
                                    </td>
                                    <td class="py-2 whitespace-nowrap">
                                        <x-status-badge :status="$res->status" />
                                    </td>
                                    <td class="py-2 whitespace-nowrap">
                                        <x-status-badge :status="$res->attendance_status" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
