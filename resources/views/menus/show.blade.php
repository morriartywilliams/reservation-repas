@extends('layouts.app')

@section('title', $menu->nom)
@section('header', $menu->nom)
@section('subtitle')
    Du {{ $menu->date_debut->format('d/m/Y') }} au {{ $menu->date_fin->format('d/m/Y') }} · Statut : 
    <x-status-badge :status="$menu->statut" />
@endsection

@section('actions')
    <div class="flex gap-2">
        @can('publish', $menu)
            @if($menu->statut === 'brouillon')
                <button 
                    type="button" 
                    onclick="openConfirmModal({
                        title: 'Publier le menu hebdomadaire',
                        message: 'La publication générera un repas planifié par date entre le {{ $menu->date_debut->format('d/m/Y') }} et le {{ $menu->date_fin->format('d/m/Y') }}. Les élèves pourront effectuer leurs réservations.',
                        confirmText: 'Confirmer la publication',
                        buttonClass: 'inline-flex justify-center rounded-md border border-transparent bg-emerald-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-emerald-700 sm:text-sm',
                        action: '{{ route('menus.publish', $menu) }}',
                        method: 'PATCH'
                    })"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700"
                >
                    Publier le menu
                </button>
            @endif
        @endcan

        @can('update', $menu)
            <a href="{{ route('menus.edit', $menu) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Modifier
            </a>
        @endcan

        <a href="{{ route('menus.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700">
            Retour
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Menu Summary Card -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                Composition du Menu
            </h3>
            
            <dl class="space-y-4 text-sm mb-6">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Créé par</dt>
                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $menu->creator?->nom_complet }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Période de validité</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $menu->date_debut->format('d/m/Y') }} — {{ $menu->date_fin->format('d/m/Y') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Nombre de repas au planning</dt>
                    <dd class="mt-1 text-lg font-bold text-gray-900">{{ $menu->repas->count() }}</dd>
                </div>
            </dl>

            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                Plats au programme :
            </h4>
            <div class="space-y-2">
                @foreach($menu->plats as $plat)
                    <div class="p-3 bg-gray-50 rounded-md border border-gray-100">
                        <div class="font-medium text-sm text-gray-900">{{ $plat->nom }}</div>
                        @if($plat->description)
                            <div class="text-xs text-gray-500 mt-1">{{ $plat->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Generated Repas / Schedule List -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-lg border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center justify-between">
                <span>Calendrier des Repas Quotidiens (Instances)</span>
                @if(auth()->user()->role?->nom === 'eleve' && $menu->statut === 'publie')
                    <a href="{{ route('reservations.create') }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">
                        Réserver mes repas &rarr;
                    </a>
                @endif
            </h3>

            @if($menu->repas->isEmpty())
                <div class="text-center py-8 text-sm text-gray-500">
                    <p>Aucun repas n'a encore été généré pour ce menu.</p>
                    @if($menu->statut === 'brouillon')
                        <p class="mt-2 text-xs text-emerald-600 font-medium">
                            Cliquez sur "Publier le menu" pour générer automatiquement les dates de repas.
                        </p>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="py-2 px-3">Date</th>
                                <th class="py-2 px-3">Statut du Repas</th>
                                <th class="py-2 px-3">Réservations</th>
                                @if(in_array(auth()->user()->role?->nom, ['administrateur', 'gestionnaire']))
                                    <th class="py-2 px-3 text-right">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($menu->repas as $r)
                                <tr>
                                    <td class="py-3 px-3 whitespace-nowrap font-medium text-gray-900">
                                        {{ $r->date->translatedFormat('l d F Y') }}
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <x-status-badge :status="$r->statut" />
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap text-gray-600">
                                        {{ $r->reservations()->count() }} réservation(s)
                                    </td>
                                    @if(in_array(auth()->user()->role?->nom, ['administrateur', 'gestionnaire']))
                                        <td class="py-3 px-3 whitespace-nowrap text-right text-xs">
                                            <a href="{{ route('repas.show', $r) }}" class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                Gérer présences &rarr;
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
