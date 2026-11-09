@extends('layouts.app')

@section('title', 'Menus Hebdomadaires')
@section('header', 'Menus Hebdomadaires')
@section('subtitle', 'Gestion des compositions de menus et plannings de cantine.')

@section('actions')
    @can('create', \App\Models\Menu::class)
        <a href="{{ route('menus.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nouveau menu
        </a>
    @endcan
@endsection

@section('content')
    <x-search-bar :action="route('menus.index')" placeholder="Rechercher un menu...">
        @if(auth()->user()->role?->nom !== 'eleve')
            <select name="statut" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Tous les statuts</option>
                <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                <option value="publie" {{ request('statut') == 'publie' ? 'selected' : '' }}>Publié</option>
            </select>
        @endif
        <input 
            type="date" 
            name="date_debut" 
            value="{{ request('date_debut') }}" 
            placeholder="Date début"
            class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        >
        <input 
            type="date" 
            name="date_fin" 
            value="{{ request('date_fin') }}" 
            placeholder="Date fin"
            class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        >
    </x-search-bar>

    @if($menus->isEmpty())
        <x-empty-state 
            title="Aucun menu disponible" 
            message="Aucun menu hebdomadaire ne correspond à votre sélection."
            :actionText="auth()->user()->can('create', \App\Models\Menu::class) ? 'Créer un menu' : null"
            :actionUrl="auth()->user()->can('create', \App\Models\Menu::class) ? route('menus.create') : null"
        />
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($menus as $menu)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <x-status-badge :status="$menu->statut" />
                            <span class="text-xs text-gray-500">
                                {{ $menu->repas_count }} repas généré(s)
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-1">
                            <a href="{{ route('menus.show', $menu) }}" class="hover:text-emerald-600">
                                {{ $menu->nom }}
                            </a>
                        </h3>

                        <p class="text-xs text-emerald-700 font-medium mb-4 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Du {{ $menu->date_debut->format('d/m/Y') }} au {{ $menu->date_fin->format('d/m/Y') }}
                        </p>

                        <div class="mb-4">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">
                                Plats associés :
                            </span>
                            <div class="flex flex-wrap gap-1">
                                @forelse($menu->plats as $plat)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">
                                        {{ $plat->nom }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-400 italic">Aucun plat rattaché</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs">
                        <a href="{{ route('menus.show', $menu) }}" class="font-semibold text-emerald-600 hover:text-emerald-700">
                            Consulter détails &rarr;
                        </a>

                        <div class="flex items-center gap-2">
                            @can('publish', $menu)
                                @if($menu->statut === 'brouillon')
                                    <button 
                                        type="button" 
                                        onclick="openConfirmModal({
                                            title: 'Publier le menu',
                                            message: 'La publication du menu générera automatiquement tous les repas quotidiens entre le {{ $menu->date_debut->format('d/m/Y') }} et le {{ $menu->date_fin->format('d/m/Y') }}. Les élèves pourront alors réserver.',
                                            confirmText: 'Publier maintenant',
                                            buttonClass: 'inline-flex justify-center rounded-md border border-transparent bg-emerald-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 sm:text-sm',
                                            action: '{{ route('menus.publish', $menu) }}',
                                            method: 'PATCH'
                                        })"
                                        class="text-emerald-700 font-semibold hover:underline"
                                    >
                                        Publier
                                    </button>
                                @endif
                            @endcan

                            @can('update', $menu)
                                <a href="{{ route('menus.edit', $menu) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Modifier
                                </a>
                            @endcan

                            @can('delete', $menu)
                                <button 
                                    type="button" 
                                    onclick="openConfirmModal({
                                        title: 'Supprimer le menu',
                                        message: @js("Confirmez-vous la suppression du menu {$menu->nom} ?"),
                                        confirmText: 'Supprimer',
                                        action: @js(route('menus.destroy', $menu)),
                                        method: 'DELETE'
                                    })"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Supprimer
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    @endif
@endsection
