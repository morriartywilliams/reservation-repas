@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('header', 'Utilisateurs du Système')
@section('subtitle', 'Liste des comptes administrateurs, gestionnaires et élèves.')

@section('actions')
    <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Nouvel utilisateur
    </a>
@endsection

@section('content')
    <x-search-bar :action="route('users.index')" placeholder="Rechercher par nom, prénom, email...">
        <select name="role_id" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tous les rôles</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                    {{ ucfirst($role->nom) }}
                </option>
            @endforeach
        </select>
    </x-search-bar>

    @if($users->isEmpty())
        <x-empty-state 
            title="Aucun utilisateur trouvé" 
            message="Aucun compte utilisateur ne correspond à vos critères de recherche."
            actionText="Créer un utilisateur"
            :actionUrl="route('users.create')"
        />
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $user->nom_complet }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        {{ ucfirst($user->role?->nom) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->telephone ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <x-status-badge :status="$user->actif ? 'actif' : 'inactif'" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('users.show', $user) }}" class="text-gray-600 hover:text-gray-900">Voir</a>
                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                    @if(auth()->id() !== $user->id)
                                        <button 
                                            type="button" 
                                            onclick="openConfirmModal({
                                                title: 'Supprimer l\'utilisateur',
                                                message: @js("Supprimer le compte de {$user->nom_complet} ?"),
                                                confirmText: 'Supprimer',
                                                action: @js(route('users.destroy', $user)),
                                                method: 'DELETE'
                                            })"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Supprimer
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        </div>
    @endif
@endsection
