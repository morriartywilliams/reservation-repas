@extends('layouts.app')

@section('title', 'Réservations')
@section('header')
    {{ auth()->user()->role?->nom === 'eleve' ? 'Mes Réservations de Cantine' : 'Gestion des Réservations' }}
@endsection
@section('subtitle')
    {{ auth()->user()->role?->nom === 'eleve' ? 'Consultez et gérez vos réservations de repas.' : 'Suivi complet des réservations, présences et états de règlement.' }}
@endsection

@section('actions')
    @can('create', \App\Models\Reservation::class)
        <a href="{{ route('reservations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Réserver un repas
        </a>
    @endcan
@endsection

@section('content')
    <x-search-bar :action="route('reservations.index')" placeholder="{{ auth()->user()->role?->nom === 'eleve' ? 'Rechercher...' : 'Rechercher par élève, matricule, classe...' }}">
        <select name="status" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tous les statuts</option>
            <option value="confirmee" {{ request('status') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
            <option value="annulee" {{ request('status') == 'annulee' ? 'selected' : '' }}>Annulée</option>
            <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
        </select>
        <select name="attendance_status" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Présence</option>
            <option value="present" {{ request('attendance_status') == 'present' ? 'selected' : '' }}>Présent</option>
            <option value="absent" {{ request('attendance_status') == 'absent' ? 'selected' : '' }}>Absent</option>
            <option value="non_verifiee" {{ request('attendance_status') == 'non_verifiee' ? 'selected' : '' }}>Non vérifiée</option>
        </select>
        <input 
            type="date" 
            name="date" 
            value="{{ request('date') }}" 
            class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        >
    </x-search-bar>

    @if($reservations->isEmpty())
        <x-empty-state 
            title="Aucune réservation" 
            message="Aucun enregistrement ne correspond à vos filtres."
            :actionText="auth()->user()->role?->nom === 'eleve' ? 'Réserver un repas' : null"
            :actionUrl="auth()->user()->role?->nom === 'eleve' ? route('reservations.create') : null"
        />
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(auth()->user()->role?->nom !== 'eleve')
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Élève</th>
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date du repas</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut réservation</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Présence</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Règlement</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reservations as $res)
                            @php
                                $today = now()->toDateString();
                                $repasDate = $res->repas?->date?->format('Y-m-d');
                                $isEditable = ($res->repas?->statut === 'planifie' && $repasDate > $today);
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                @if(auth()->user()->role?->nom !== 'eleve')
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $res->eleve?->user?->nom_complet }}</div>
                                        <div class="text-xs text-gray-500">{{ $res->eleve?->classe }} · {{ $res->eleve?->matricule }}</div>
                                    </td>
                                @endif
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">
                                    {{ $res->repas?->date?->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                    {{ $res->repas?->menu?->nom }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <x-status-badge :status="$res->status" />
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <x-status-badge :status="$res->attendance_status" />
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($res->paiement)
                                        <x-status-badge :status="$res->paiement->statut" />
                                    @else
                                        <x-status-badge status="impaye" />
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('reservations.show', $res) }}" class="text-gray-600 hover:text-gray-900">
                                        Détails
                                    </a>

                                    {{-- Student edit/cancel controls under §12 edit window --}}
                                    @can('update', $res)
                                        <a href="{{ route('reservations.edit', $res) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Modifier
                                        </a>
                                    @endcan

                                    @can('delete', $res)
                                        <button 
                                            type="button" 
                                            onclick="openConfirmModal({
                                                title: 'Annuler la réservation',
                                                message: 'Êtes-vous sûr de vouloir annuler votre réservation pour le {{ $res->repas?->date?->format('d/m/Y') }} ?',
                                                confirmText: 'Annuler la réservation',
                                                action: '{{ route('reservations.destroy', $res) }}',
                                                method: 'DELETE'
                                            })"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Annuler
                                        </button>
                                    @endcan

                                    {{-- Staff quick payment record shortcut if unpaid --}}
                                    @if(in_array(auth()->user()->role?->nom, ['administrateur', 'gestionnaire']) && ! $res->paiement)
                                        <a href="{{ route('paiements.create', ['reservation_id' => $res->id]) }}" class="text-emerald-600 hover:text-emerald-900">
                                            Régler
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $reservations->links() }}
            </div>
        </div>
    @endif
@endsection
