@extends('layouts.app')

@section('title', 'Détails de la réservation')
@section('header', 'Détails de la réservation #' . $reservation->id)
@section('subtitle', 'Informations complètes sur la réservation, le repas, l\'élève et le règlement.')

@section('actions')
    <a href="{{ route('reservations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour à la liste
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Card: Statut global & Actions --}}
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-gray-900">
                            Réservation pour le {{ $reservation->repas?->date?->format('d/m/Y') }}
                        </h2>
                        <x-status-badge :status="$reservation->status" />
                        <x-status-badge :status="$reservation->attendance_status" />
                    </div>
                    <p class="text-sm text-gray-500">
                        Enregistrée le {{ $reservation->date_reservation?->format('d/m/Y à H:i') ?? $reservation->created_at?->format('d/m/Y à H:i') }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    @can('update', $reservation)
                        <a href="{{ route('reservations.edit', $reservation) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </a>
                    @endcan

                    @can('delete', $reservation)
                        <button 
                            type="button" 
                            onclick="openConfirmModal({
                                title: 'Annuler la réservation',
                                message: 'Êtes-vous certain de vouloir annuler votre réservation pour le repas du {{ $reservation->repas?->date?->format('d/m/Y') }} ?',
                                confirmText: 'Confirmer l\'annulation',
                                action: '{{ route('reservations.destroy', $reservation) }}',
                                method: 'DELETE'
                            })"
                            class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100"
                        >
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Annuler la réservation
                        </button>
                    @endcan

                    @if(in_array(auth()->user()->role?->nom, ['administrateur', 'gestionnaire']) && $reservation->repas)
                        <a href="{{ route('repas.show', $reservation->repas) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                            <svg class="-ml-1 mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Voir le repas
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card: Informations Élève --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 space-y-4">
                <h3 class="text-base font-semibold text-gray-900 border-b pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informations de l'Élève
                </h3>

                <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Nom & Prénom</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $reservation->eleve?->user?->nom_complet }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Matricule</dt>
                        <dd class="mt-1 font-mono font-medium text-emerald-700">{{ $reservation->eleve?->matricule }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Classe</dt>
                        <dd class="mt-1 text-gray-900">{{ $reservation->eleve?->classe }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Email</dt>
                        <dd class="mt-1 text-gray-700">{{ $reservation->eleve?->user?->email }}</dd>
                    </div>
                    @if($reservation->eleve?->user?->telephone)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Téléphone</dt>
                            <dd class="mt-1 text-gray-700">{{ $reservation->eleve?->user?->telephone }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Card: Repas & Menu --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 space-y-4">
                <h3 class="text-base font-semibold text-gray-900 border-b pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Détails du Repas & Menu
                </h3>

                <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Date servie</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $reservation->repas?->date?->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">État du repas</dt>
                        <dd class="mt-1">
                            <x-status-badge :status="$reservation->repas?->statut" />
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium text-gray-500 uppercase">Menu de rattachement</dt>
                        <dd class="mt-1 font-medium text-emerald-800">
                            {{ $reservation->repas?->menu?->nom }}
                            <span class="text-xs text-gray-500 font-normal">
                                (du {{ $reservation->repas?->menu?->date_debut?->format('d/m/Y') }} au {{ $reservation->repas?->menu?->date_fin?->format('d/m/Y') }})
                            </span>
                        </dd>
                    </div>
                </dl>

                @if($reservation->repas?->menu?->plats && $reservation->repas->menu->plats->isNotEmpty())
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Plats prévus</h4>
                        <ul class="space-y-1">
                            @foreach($reservation->repas->menu->plats as $plat)
                                <li class="text-sm text-gray-700 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    <span class="font-medium">{{ $plat->nom }}</span>
                                    @if($plat->description)
                                        <span class="text-xs text-gray-500">({{ $plat->description }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        {{-- Card: Règlement & Paiement --}}
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-2">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    État du Paiement
                </h3>

                @if(! $reservation->paiement && in_array(auth()->user()->role?->nom, ['administrateur', 'gestionnaire']))
                    <a href="{{ route('paiements.create', ['reservation_id' => $reservation->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Enregistrer un règlement
                    </a>
                @endif
            </div>

            @if($reservation->paiement)
                <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Montant</dt>
                        <dd class="mt-1 text-base font-bold text-gray-900">
                            {{ number_format($reservation->paiement->montant, 2, ',', ' ') }} DA
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Statut</dt>
                        <dd class="mt-1">
                            <x-status-badge :status="$reservation->paiement->statut" />
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Moyen de paiement</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            @if($reservation->paiement->moyen === 'especes')
                                Espèces
                            @elseif($reservation->paiement->moyen === 'carte_cantine')
                                Carte de cantine
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Date du règlement</dt>
                        <dd class="mt-1 text-gray-700">
                            {{ $reservation->paiement->date?->format('d/m/Y') ?? 'Non renseignée' }}
                        </dd>
                    </div>
                </dl>

                <div class="pt-2 text-xs text-gray-500 flex items-center justify-between">
                    <span>
                        Enregistré par : <strong>{{ $reservation->paiement->recorder?->nom_complet ?? 'Système' }}</strong>
                    </span>

                    @can('update', $reservation->paiement)
                        <a href="{{ route('paiements.edit', $reservation->paiement) }}" class="text-emerald-600 hover:text-emerald-800 font-medium">
                            Modifier le paiement →
                        </a>
                    @endcan
                </div>
            @else
                <div class="py-4 text-center">
                    <p class="text-sm text-gray-500">Aucun règlement enregistré pour cette réservation.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
