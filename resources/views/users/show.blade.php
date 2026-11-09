@extends('layouts.app')

@section('title', 'Détails Utilisateur')
@section('header', $user->nom_complet)
@section('subtitle', 'Détails du compte et historique.')

@section('actions')
    <div class="flex gap-2">
        <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Modifier
        </a>
        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700">
            Retour à la liste
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden max-w-3xl">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Informations Personnelles</h3>
        </div>
        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nom complet</dt>
                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $user->nom_complet }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse email</dt>
                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle attribué</dt>
                    <dd class="mt-1 text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            {{ ucfirst($user->role?->nom) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</dt>
                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $user->telephone ?? 'Non renseigné' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Statut du compte</dt>
                    <dd class="mt-1 text-sm">
                        <x-status-badge :status="$user->actif ? 'actif' : 'inactif'" />
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'inscription</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at?->format('d/m/Y H:i') }}</dd>
                </div>

                @if($user->eleve)
                    <div class="sm:col-span-2 pt-4 border-t border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Profil Élève Associé</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-gray-50 p-3 rounded-md">
                            <div>
                                <span class="text-xs text-gray-500 block">Matricule :</span>
                                <span class="text-sm font-mono font-bold text-gray-800">{{ $user->eleve->matricule }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">Classe :</span>
                                <span class="text-sm font-medium text-gray-800">{{ $user->eleve->classe }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">Date de naissance :</span>
                                <span class="text-sm font-medium text-gray-800">{{ $user->eleve->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </dl>
        </div>
    </div>
@endsection
