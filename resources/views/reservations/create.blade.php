@extends('layouts.app')

@section('title', 'Réserver un Repas')
@section('header', 'Réserver un Repas à la Cantine')
@section('subtitle', 'Sélectionnez un repas planifié parmi les menus hebdomadaires publiés.')

@section('content')
    <div class="max-w-3xl mx-auto">
        @if($availableRepas->isEmpty())
            <x-empty-state 
                title="Aucun repas disponible à la réservation" 
                message="Il n'y a actuellement aucun repas futur ouvert à la réservation, ou vous avez déjà réservé pour toutes les dates programmées." 
                actionText="Voir mes réservations"
                :actionUrl="route('reservations.index')"
            />
        @else
            <form method="POST" action="{{ route('reservations.store') }}" class="space-y-6">
                @csrf

                @error('repas_id')
                    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded text-sm text-red-700">
                        {{ $message }}
                    </div>
                @enderror

                <div class="space-y-4">
                    <p class="text-sm font-medium text-gray-700">
                        Choisissez la date du repas souhaité :
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($availableRepas as $repas)
                            <label class="relative flex flex-col p-4 bg-white rounded-lg border-2 border-gray-200 cursor-pointer hover:border-emerald-500 transition-colors shadow-sm has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="text-base font-bold text-gray-900">
                                            {{ $repas->date->translatedFormat('l d F Y') }}
                                        </div>
                                        <div class="text-xs text-emerald-700 font-semibold mt-0.5">
                                            {{ $repas->menu?->nom }}
                                        </div>
                                    </div>
                                    <input 
                                        type="radio" 
                                        name="repas_id" 
                                        value="{{ $repas->id }}" 
                                        {{ old('repas_id') == $repas->id ? 'checked' : '' }}
                                        required
                                        class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-gray-300"
                                    >
                                </div>

                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <span class="text-xs font-semibold text-gray-500 block mb-1">Au menu :</span>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($repas->menu->plats as $plat)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">
                                                {{ $plat->nom }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-md text-xs text-emerald-800">
                    <p class="font-semibold mb-1">Règles de réservation Madrassati :</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Votre réservation est immédiatement confirmée dès la validation.</li>
                        <li>Vous pouvez modifier ou annuler votre réservation jusqu'à la veille du repas.</li>
                        <li>Le jour du service, la réservation devient définitive.</li>
                    </ul>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('reservations.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Confirmer ma réservation
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection
