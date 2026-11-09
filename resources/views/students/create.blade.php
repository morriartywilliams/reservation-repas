@extends('layouts.app')

@section('title', 'Inscrire un Élève')
@section('header', 'Inscription d\'un Nouvel Élève')
@section('subtitle', 'Créez le profil élève ainsi que ses identifiants de connexion.')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('students.store') }}" class="space-y-6">
            @csrf

            <h3 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider pb-2 border-b border-emerald-100">
                1. Informations Scolaires
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="matricule" class="block text-sm font-medium text-gray-700">Numéro de Matricule *</label>
                    <input 
                        type="text" 
                        name="matricule" 
                        id="matricule" 
                        required 
                        maxlength="30"
                        value="{{ old('matricule') }}" 
                        placeholder="ex: ELEV-2026-042"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm font-mono @error('matricule') border-red-500 @enderror"
                    >
                    @error('matricule')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="classe" class="block text-sm font-medium text-gray-700">Classe / Niveau *</label>
                    <input 
                        type="text" 
                        name="classe" 
                        id="classe" 
                        required 
                        maxlength="50"
                        value="{{ old('classe') }}" 
                        placeholder="ex: 1AS-Scientifique 1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('classe') border-red-500 @enderror"
                    >
                    @error('classe')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="date_naissance" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                <input 
                    type="date" 
                    name="date_naissance" 
                    id="date_naissance" 
                    max="{{ now()->toDateString() }}"
                    value="{{ old('date_naissance') }}" 
                    class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('date_naissance') border-red-500 @enderror"
                >
                @error('date_naissance')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider pb-2 pt-4 border-b border-emerald-100">
                2. État Civil & Coordonnées
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom *</label>
                    <input 
                        type="text" 
                        name="prenom" 
                        id="prenom" 
                        required 
                        maxlength="100"
                        value="{{ old('prenom') }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('prenom') border-red-500 @enderror"
                    >
                    @error('prenom')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom de famille *</label>
                    <input 
                        type="text" 
                        name="nom" 
                        id="nom" 
                        required 
                        maxlength="100"
                        value="{{ old('nom') }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('nom') border-red-500 @enderror"
                    >
                    @error('nom')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email de connexion *</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        required 
                        maxlength="150"
                        value="{{ old('email') }}" 
                        placeholder="eleve@madrassati.dz"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone (Parents/Tuteur)</label>
                    <input 
                        type="tel" 
                        name="telephone" 
                        id="telephone" 
                        maxlength="20"
                        value="{{ old('telephone') }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe *</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        minlength="8"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmation mot de passe *</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required 
                        minlength="8"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                    >
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('students.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Enregistrer l'élève
                </button>
            </div>
        </form>
    </div>
@endsection
