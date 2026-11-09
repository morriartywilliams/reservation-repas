@extends('layouts.app')

@section('title', 'Modifier le Menu')
@section('header')
    Modifier : {{ $menu->nom }}
@endsection
@section('subtitle', 'Mise à jour des dates et plats du menu.')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('menus.update', $menu) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Intitulé du Menu *</label>
                <input 
                    type="text" 
                    name="nom" 
                    id="nom" 
                    required 
                    maxlength="150"
                    value="{{ old('nom', $menu->nom) }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('nom') border-red-500 @enderror"
                >
                @error('nom')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début *</label>
                    <input 
                        type="date" 
                        name="date_debut" 
                        id="date_debut" 
                        required 
                        value="{{ old('date_debut', $menu->date_debut?->format('Y-m-d')) }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('date_debut') border-red-500 @enderror"
                    >
                    @error('date_debut')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin *</label>
                    <input 
                        type="date" 
                        name="date_fin" 
                        id="date_fin" 
                        required 
                        value="{{ old('date_fin', $menu->date_fin?->format('Y-m-d')) }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('date_fin') border-red-500 @enderror"
                    >
                    @error('date_fin')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Plats au menu (au moins un requis) *
                </label>
                @error('plats')
                    <p class="mb-2 text-xs text-red-600">{{ $message }}</p>
                @enderror

                @php
                    $selectedPlatIds = old('plats', $menu->plats->pluck('id')->toArray());
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60 overflow-y-auto p-3 border border-gray-200 rounded-md bg-gray-50">
                    @foreach($plats as $plat)
                        <label class="flex items-center p-2 rounded hover:bg-white cursor-pointer transition-colors">
                            <input 
                                type="checkbox" 
                                name="plats[]" 
                                value="{{ $plat->id }}"
                                {{ in_array($plat->id, $selectedPlatIds) ? 'checked' : '' }}
                                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                            >
                            <span class="ml-2 text-sm text-gray-800 font-medium">{{ $plat->nom }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('menus.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
@endsection
