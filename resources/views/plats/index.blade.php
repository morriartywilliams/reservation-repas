@extends('layouts.app')

@section('title', 'Catalogue des Plats')
@section('header', 'Catalogue des Plats')
@section('subtitle', 'Gestion des entrées, plats principaux et desserts servis à la cantine.')

@section('actions')
    @can('create', \App\Models\Plat::class)
        <a href="{{ route('plats.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajouter un plat
        </a>
    @endcan
@endsection

@section('content')
    <x-search-bar :action="route('plats.index')" placeholder="Rechercher par nom de plat, ingrédients..." />

    @if($plats->isEmpty())
        <x-empty-state 
            title="Aucun plat dans le catalogue" 
            message="Commencez par ajouter des plats pour composer vos menus hebdomadaires."
            :actionText="auth()->user()->can('create', \App\Models\Plat::class) ? 'Ajouter un plat' : null"
            :actionUrl="auth()->user()->can('create', \App\Models\Plat::class) ? route('plats.create') : null"
        />
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du plat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menus associés</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($plats as $plat)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $plat->nom }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-md">
                                    {{ $plat->description ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $plat->menus_count }} menu(s)
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    @can('update', $plat)
                                        <a href="{{ route('plats.edit', $plat) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Modifier
                                        </a>
                                    @endcan
                                    @can('delete', $plat)
                                        <button 
                                            type="button" 
                                            onclick="openConfirmModal({
                                                title: 'Supprimer le plat',
                                                message: @js("Supprimer le plat {$plat->nom} ?"),
                                                confirmText: 'Supprimer',
                                                action: @js(route('plats.destroy', $plat)),
                                                method: 'DELETE'
                                            })"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Supprimer
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $plats->links() }}
            </div>
        </div>
    @endif
@endsection
