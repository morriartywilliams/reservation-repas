@extends('layouts.app')

@section('title', 'Modifier la Réservation')
@section('header', 'Modifier la Réservation')
@section('subtitle')
    Repas actuel : {{ $reservation->repas?->date?->format('d/m/Y') }} ({{ $reservation->repas?->menu?->nom }})
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('reservations.update', $reservation) }}" class="space-y-6">
            @csrf
            @method('PUT')

            @error('repas_id')
                <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded text-sm text-red-700">
                    {{ $message }}
                </div>
            @enderror

            <div class="space-y-4">
                <p class="text-sm font-medium text-gray-700">
                    Sélectionnez la nouvelle date de repas souhaitée :
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Include current repas as an option --}}
                    @if($reservation->repas)
                        <label class="relative flex flex-col p-4 bg-white rounded-lg border-2 border-emerald-500 cursor-pointer shadow-sm has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="inline-block text-xs font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded mb-1">Actuel</span>
                                    <div class="text-base font-bold text-gray-900">
                                        {{ $reservation->repas->date->translatedFormat('l d F Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $reservation->repas->menu?->nom }}
                                    </div>
                                </div>
                                <input 
                                    type="radio" 
                                    name="repas_id" 
                                    value="{{ $reservation->repas->id }}" 
                                    checked
                                    class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-gray-300"
                                >
                            </div>
                        </label>
                    @endif

                    @foreach($availableRepas as $repas)
                        @if($repas->id !== $reservation->repas_id)
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
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('reservations.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Enregistrer la modification
                </button>
            </div>
        </form>
    </div>
@endsection
