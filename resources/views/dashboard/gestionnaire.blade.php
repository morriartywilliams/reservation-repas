@extends('layouts.app')

@section('title', 'Tableau de bord Gestionnaire')
@section('header', 'Tableau de bord — Gestionnaire de Cantine')
@section('subtitle', 'Gestion quotidienne des repas, présences et flux de cantine.')

@section('content')
    <!-- Stat Cards Grid (Spec Metrics §16) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Élèves inscrits -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3 text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Élèves</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $metrics['total_eleves'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 text-xs">
                <a href="{{ route('students.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Consulter la liste &rarr;</a>
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
                <a href="{{ route('menus.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Gérer les menus &rarr;</a>
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
                <a href="{{ route('reservations.index', ['date' => now()->toDateString()]) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Valider les présences &rarr;</a>
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
                <a href="{{ route('repas.index', ['date' => now()->toDateString()]) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Détails distribution &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Operations column -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Shortcuts -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-5">
            <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Actions de Gestion
            </h2>
            <div class="space-y-2">
                <a href="{{ route('menus.create') }}" class="block p-3 rounded-md bg-gray-50 hover:bg-emerald-50 text-sm font-medium text-gray-800 hover:text-emerald-700 border border-gray-100 transition-colors">
                    + Nouveau menu hebdomadaire
                </a>
                <a href="{{ route('plats.create') }}" class="block p-3 rounded-md bg-gray-50 hover:bg-emerald-50 text-sm font-medium text-gray-800 hover:text-emerald-700 border border-gray-100 transition-colors">
                    + Nouveau plat
                </a>
                <a href="{{ route('paiements.create') }}" class="block p-3 rounded-md bg-gray-50 hover:bg-emerald-50 text-sm font-medium text-gray-800 hover:text-emerald-700 border border-gray-100 transition-colors">
                    + Enregistrer un paiement
                </a>
                <a href="{{ route('statistiques.index') }}" class="block p-3 rounded-md bg-gray-50 hover:bg-emerald-50 text-sm font-medium text-gray-800 hover:text-emerald-700 border border-gray-100 transition-colors">
                    &rarr; Rapport de fréquentation
                </a>
            </div>
        </div>

        <!-- Today meal widget -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-lg border border-gray-200 p-5">
            <h2 class="text-base font-semibold text-gray-900 mb-4">
                Service du jour — {{ now()->translatedFormat('l d F Y') }}
            </h2>

            @if($todayRepas)
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg mb-4 flex justify-between items-center">
                    <div>
                        <div class="font-semibold text-emerald-900">{{ $todayRepas->menu?->nom }}</div>
                        <div class="text-xs text-emerald-700 mt-1">
                            {{ $todayRepas->reservations->where('status', 'confirmee')->count() }} réservations confirmées · 
                            Statut : <x-status-badge :status="$todayRepas->statut" />
                        </div>
                    </div>
                    <a href="{{ route('repas.show', $todayRepas) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-emerald-600 hover:bg-emerald-700">
                        Valider présences / Clôturer
                    </a>
                </div>
            @else
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600 text-center mb-4">
                    Aucun repas planifié pour la date d'aujourd'hui.
                </div>
            @endif

            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                Dernières réservations
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                            <th class="py-2">Élève</th>
                            <th class="py-2">Date</th>
                            <th class="py-2">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentReservations->take(4) as $res)
                            <tr>
                                <td class="py-2">{{ $res->eleve?->user?->nom_complet }} ({{ $res->eleve?->classe }})</td>
                                <td class="py-2">{{ $res->repas?->date?->format('d/m/Y') }}</td>
                                <td class="py-2"><x-status-badge :status="$res->status" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
