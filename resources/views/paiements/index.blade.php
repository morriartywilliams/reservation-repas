@extends('layouts.app')

@section('title', 'Paiements & Caisse')
@section('header', 'Gestion des Paiements & Caisse')
@section('subtitle', 'Enregistrement, suivi des encaissements et régularisation des repas cantine.')

@section('actions')
    @can('create', \App\Models\Paiement::class)
        <a href="{{ route('paiements.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Enregistrer un paiement
        </a>
    @endcan
@endsection

@section('content')
    <x-search-bar :action="route('paiements.index')" placeholder="Rechercher par élève, matricule...">
        <select name="statut" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tous les statuts</option>
            <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
            <option value="impaye" {{ request('statut') == 'impaye' ? 'selected' : '' }}>Impayé</option>
            <option value="exonere" {{ request('statut') == 'exonere' ? 'selected' : '' }}>Exonéré</option>
        </select>
        <select name="moyen" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tous les modes</option>
            <option value="especes" {{ request('moyen') == 'especes' ? 'selected' : '' }}>Espèces</option>
            <option value="carte_cantine" {{ request('moyen') == 'carte_cantine' ? 'selected' : '' }}>Carte cantine</option>
        </select>
        <input 
            type="date" 
            name="date" 
            value="{{ request('date') }}" 
            class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            title="Filtrer par date de règlement"
        >
    </x-search-bar>

    @if($paiements->isEmpty())
        <x-empty-state 
            title="Aucun paiement trouvé" 
            message="Aucun enregistrement de paiement ne correspond à vos filtres actuels."
            :actionText="auth()->user()->can('create', \App\Models\Paiement::class) ? 'Enregistrer un paiement' : null"
            :actionUrl="auth()->user()->can('create', \App\Models\Paiement::class) ? route('paiements.create') : null"
        />
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Élève</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Repas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moyen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Paiement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enregistré par</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($paiements as $paiement)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">
                                        {{ $paiement->reservation?->eleve?->user?->nom_complet ?? 'Élève inconnu' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $paiement->reservation?->eleve?->classe }} · {{ $paiement->reservation?->eleve?->matricule }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                    {{ $paiement->reservation?->repas?->date?->format('d/m/Y') ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                    {{ number_format($paiement->montant, 2, ',', ' ') }} DA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$paiement->statut" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    @if($paiement->moyen === 'especes')
                                        Espèces
                                    @elseif($paiement->moyen === 'carte_cantine')
                                        Carte cantine
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ $paiement->date?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $paiement->recorder?->nom_complet ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('reservations.show', $paiement->reservation_id) }}" class="text-gray-600 hover:text-gray-900">
                                        Réservation
                                    </a>

                                    @can('update', $paiement)
                                        <a href="{{ route('paiements.edit', $paiement) }}" class="text-emerald-600 hover:text-emerald-900">
                                            Modifier
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $paiements->links() }}
            </div>
        </div>
    @endif
@endsection
