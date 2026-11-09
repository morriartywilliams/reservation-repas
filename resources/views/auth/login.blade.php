@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Adresse Email
            </label>
            <div class="mt-1">
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    autocomplete="email" 
                    required 
                    value="{{ old('email') }}"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm @error('email') border-red-500 @enderror"
                    placeholder="ex: admin@madrassati.dz"
                >
            </div>
            @error('email')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
                Mot de passe
            </label>
            <div class="mt-1">
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    autocomplete="current-password" 
                    required 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm @error('password') border-red-500 @enderror"
                >
            </div>
            @error('password')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-900">
                    Se souvenir de moi
                </label>
            </div>
        </div>

        <div>
            <button 
                type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
            >
                Connexion
            </button>
        </div>
    </form>

    <div class="mt-6 border-t border-gray-200 pt-4 text-xs text-gray-500">
        <p class="font-semibold text-gray-700 mb-1">Comptes de démonstration :</p>
        <p><span class="font-medium">Admin :</span> admin@madrassati.dz / password</p>
        <p><span class="font-medium">Gestionnaire :</span> gestionnaire@madrassati.dz / password</p>
        <p><span class="font-medium">Élève :</span> eleve@madrassati.dz / password</p>
    </div>
@endsection
