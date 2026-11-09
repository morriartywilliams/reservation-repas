@extends('layouts.app')

@section('title', 'Créer un Utilisateur')
@section('header', 'Ajouter un Utilisateur')
@section('subtitle', 'Créez un nouveau compte avec affectation de rôle.')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf

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
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom *</label>
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

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Adresse Email *</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    required 
                    maxlength="150"
                    value="{{ old('email') }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700">Rôle *</label>
                    <select 
                        name="role_id" 
                        id="role_id" 
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm @error('role_id') border-red-500 @enderror"
                    >
                        <option value="">Sélectionner un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->nom) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
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

            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    name="actif" 
                    id="actif" 
                    value="1" 
                    {{ old('actif', '1') ? 'checked' : '' }}
                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                >
                <label for="actif" class="ml-2 block text-sm text-gray-900">
                    Compte actif (autorisé à se connecter)
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Enregistrer l'utilisateur
                </button>
            </div>
        </form>
    </div>
@endsection
