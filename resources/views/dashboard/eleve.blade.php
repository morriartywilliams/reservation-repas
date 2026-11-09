@extends('layouts.app')

@section('title', 'Mon Espace Restauration')
@section('header')
    Bienvenue, {{ auth()->user()->prenom }} !
@endsection
@section('subtitle', 'Consultez vos réservations à la cantine et réservez vos prochains repas.')

@section('actions')
    <a href="{{ route('reservations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Réserver un repas
    </a>
@endsection

@section('content')
    <!-- Student Header Summary -->
    <div class="bg-emerald-700 text-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-800 text-emerald-100 mb-2">
                    Profil Élève
                </span>
                <h2 class="text-xl font-bold">{{ auth()->user()->nom_complet }}</h2>
                <p class="text-emerald-100 text-sm mt-1">
                    Matricule : <span class="font-mono font-semibold">{{ $eleve?->matricule ?? 'Non renseigné' }}</span> · 
                    Classe : <span class="font-semibold">{{ $eleve?->classe ?? 'Non assignée' }}</span>
                </p>
            </div>

            <div class="flex gap-4">
                <div class="bg-emerald-800/60 rounded-lg p-3 text-center min-w-[100px]">
                    <div class="text-2xl font-bold">{{ $upcomingReservations->count() }}</div>
                    <div class="text-xs text-emerald-200">Repas à venir</div>
                </div>
                <div class="bg-emerald-800/60 rounded-lg p-3 text-center min-w-[100px]">
                    <div class="text-2xl font-bold {{ $unpaidCount > 0 ? 'text-amber-300' : 'text-emerald-200' }}">
                        {{ $unpaidCount }}
                    </div>
                    <div class="text-xs text-emerald-200">Non réglés</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Menu of the Week -->
    @if($activeMenu)
        <div class="bg-white rounded-lg border border-emerald-200 shadow-sm p-5 mb-6">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h3 class="text-base font-semibold text-gray-900">
                        {{ $activeMenu->nom }}
                    </h3>
                </div>
                <span class="text-xs text-gray-500">
                    Du {{ $activeMenu->date_debut->format('d/m/Y') }} au {{ $activeMenu->date_fin->format('d/m/Y') }}
                </span>
            </div>
            
            <p class="text-xs text-gray-500 mb-3">Plats au menu cette semaine :</p>
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($activeMenu->plats as $plat)
                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                        {{ $plat->nom }}
                    </span>
                @endforeach
            </div>

            <a href="{{ route('reservations.create') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                Choisir mes jours pour ce menu &rarr;
            </a>
        </div>
    @endif

    <!-- Upcoming Reservations Table -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-5 mb-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center justify-between">
            <span>Mes Prochains Repas Réservés</span>
            <a href="{{ route('reservations.index') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-normal">
                Historique complet &rarr;
            </a>
        </h3>

        @if($upcomingReservations->isEmpty())
            <x-empty-state 
                title="Aucune réservation à venir" 
                message="Vous n'avez aucun repas réservé pour les jours prochains. Vous pouvez réserver vos repas à l'avance."
                actionText="Réserver un repas"
                :actionUrl="route('reservations.create')"
            />
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="py-3 px-3">Date du repas</th>
                            <th class="py-3 px-3">Menu</th>
                            <th class="py-3 px-3">Statut réservation</th>
                            <th class="py-3 px-3">Paiement</th>
                            <th class="py-3 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($upcomingReservations as $res)
                            @php
                                $today = now()->toDateString();
                                $repasDate = $res->repas?->date?->format('Y-m-d');
                                $isEditable = ($res->repas?->statut === 'planifie' && $repasDate > $today);
                            @endphp
                            <tr>
                                <td class="py-3 px-3 whitespace-nowrap font-medium text-gray-900">
                                    {{ $res->repas?->date?->translatedFormat('l d F Y') }}
                                </td>
                                <td class="py-3 px-3">
                                    {{ $res->repas?->menu?->nom }}
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    <x-status-badge :status="$res->status" />
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    @if($res->paiement)
                                        <x-status-badge :status="$res->paiement->statut" />
                                    @else
                                        <x-status-badge status="impaye" />
                                    @endif
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap text-right text-xs">
                                    @if($isEditable && $res->status !== 'annulee')
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('reservations.edit', $res) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Modifier
                                            </a>
                                            <button 
                                                type="button" 
                                                onclick="openConfirmModal({
                                                    title: 'Annuler la réservation',
                                                    message: 'Voulez-vous vraiment annuler votre réservation pour le repas du {{ $res->repas?->date?->format('d/m/Y') }} ?',
                                                    confirmText: 'Annuler ma réservation',
                                                    action: '{{ route('reservations.destroy', $res) }}',
                                                    method: 'DELETE'
                                                })"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Annuler
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Verrouillée</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
