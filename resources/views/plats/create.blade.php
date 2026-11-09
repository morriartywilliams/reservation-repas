@extends('layouts.app')

@section('title', 'Nouveau Plat')
@section('header', 'Ajouter un Plat')
@section('subtitle', 'Enregistrez un nouveau plat pour enrichir le catalogue.')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('plats.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom du plat *</label>
                <input 
                    type="text" 
                    name="nom" 
                    id="nom" 
                    required 
                    maxlength="150"
                    value="{{ old('nom') }}" 
                    placeholder="ex: Couscous traditionnel aux légumes"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('nom') border-red-500 @enderror"
                >
                @error('nom')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description / Ingrédients / Allergènes</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4" 
                    placeholder="Détaillez la recette, les accompagnements ou les ingrédients..."
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('description') border-red-500 @enderror"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('plats.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Enregistrer le plat
                </button>
            </div>
        </form>
    </div>
@endsection
