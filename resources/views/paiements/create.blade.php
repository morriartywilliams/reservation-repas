@extends('layouts.app')

@section('title', 'Enregistrer un paiement')
@section('header', 'Enregistrer un règlement')
@section('subtitle', 'Associer un paiement à une réservation existante sans règlement enregistré.')

@section('actions')
    <a href="{{ route('paiements.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour à la liste
    </a>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
            <form action="{{ route('paiements.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Reservation selection --}}
                <div>
                    <label for="reservation_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Réservation concernée <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="reservation_id" 
                        id="reservation_id" 
                        required 
                        class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('reservation_id') border-red-300 @enderror"
                    >
                        <option value="">Sélectionnez une réservation en attente de paiement...</option>
                        @foreach($reservations as $res)
                            <option 
                                value="{{ $res->id }}" 
                                {{ (old('reservation_id', $selectedReservationId) == $res->id) ? 'selected' : '' }}
                            >
                                #{{ $res->id }} — {{ $res->eleve?->user?->nom_complet }} ({{ $res->eleve?->classe }}) — Repas du {{ $res->repas?->date?->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('reservation_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @if($reservations->isEmpty())
                        <p class="mt-2 text-xs text-amber-600">
                            Toutes les réservations actuelles ont déjà un règlement associé.
                        </p>
                    @endif
                </div>

                {{-- Montant --}}
                <div>
                    <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">
                        Montant (DZD) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input 
                            type="number" 
                            name="montant" 
                            id="montant" 
                            step="0.01" 
                            min="0" 
                            value="{{ old('montant', '150.00') }}" 
                            required 
                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 pr-12 @error('montant') border-red-300 @enderror"
                            placeholder="0.00"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500 text-xs font-semibold">
                            DA
                        </div>
                    </div>
                    @error('montant')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Statut --}}
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">
                            Statut du paiement <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="statut" 
                            id="statut" 
                            required 
                            onchange="toggleMoyenRequirement()"
                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('statut') border-red-300 @enderror"
                        >
                            <option value="paye" {{ old('statut', 'paye') === 'paye' ? 'selected' : '' }}>Payé</option>
                            <option value="impaye" {{ old('statut') === 'impaye' ? 'selected' : '' }}>Impayé</option>
                            <option value="exonere" {{ old('statut') === 'exonere' ? 'selected' : '' }}>Exonéré</option>
                        </select>
                        @error('statut')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Moyen de paiement --}}
                    <div id="moyen-group">
                        <label for="moyen" class="block text-sm font-medium text-gray-700 mb-1">
                            Mode de règlement <span id="moyen-asterisk" class="text-red-500">*</span>
                        </label>
                        <select 
                            name="moyen" 
                            id="moyen" 
                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('moyen') border-red-300 @enderror"
                        >
                            <option value="">Sélectionner un mode...</option>
                            <option value="especes" {{ old('moyen', 'especes') === 'especes' ? 'selected' : '' }}>Espèces</option>
                            <option value="carte_cantine" {{ old('moyen') === 'carte_cantine' ? 'selected' : '' }}>Carte cantine</option>
                        </select>
                        @error('moyen')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Obligatoire uniquement si le statut est "Payé".</p>
                    </div>
                </div>

                {{-- Date de paiement --}}
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                        Date de versement
                    </label>
                    <input 
                        type="date" 
                        name="date" 
                        id="date" 
                        value="{{ old('date', now()->toDateString()) }}" 
                        class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('date') border-red-300 @enderror"
                    >
                    @error('date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('paiements.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" class="px-5 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700">
                        Valider l'encaissement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMoyenRequirement() {
            const statut = document.getElementById('statut').value;
            const moyenGroup = document.getElementById('moyen-group');
            const moyenAsterisk = document.getElementById('moyen-asterisk');
            const moyenSelect = document.getElementById('moyen');

            if (statut === 'paye') {
                moyenAsterisk.style.display = 'inline';
                moyenSelect.setAttribute('required', 'required');
            } else {
                moyenAsterisk.style.display = 'none';
                moyenSelect.removeAttribute('required');
            }
        }
        document.addEventListener('DOMContentLoaded', toggleMoyenRequirement);
    </script>
@endsection
