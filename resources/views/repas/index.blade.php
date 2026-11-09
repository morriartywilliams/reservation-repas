@extends('layouts.app')

@section('title', 'Repas Quotidiens')
@section('header', 'Repas Quotidiens')
@section('subtitle', 'Instances de repas programmées, validation des présences et distribution.')

@section('content')
    <x-search-bar :action="route('repas.index')" placeholder="Rechercher par date...">
        <select name="statut" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tous les statuts</option>
            <option value="planifie" {{ request('statut') == 'planifie' ? 'selected' : '' }}>Planifié</option>
            <option value="servi" {{ request('statut') == 'servi' ? 'selected' : '' }}>Servi</option>
        </select>
        <select name="menu_id" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tous les menus</option>
            @foreach($menus as $m)
                <option value="{{ $m->id }}" {{ request('menu_id') == $m->id ? 'selected' : '' }}>
                    {{ $m->nom }}
                </option>
            @endforeach
        </select>
        <input 
            type="date" 
            name="date" 
            value="{{ request('date') }}" 
            class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        >
    </x-search-bar>

    @if($repasList->isEmpty())
        <x-empty-state 
            title="Aucun repas trouvé" 
            message="Aucun repas quotidien ne correspond à votre filtre. Assurez-vous d'avoir publié au moins un menu hebdomadaire."
        />
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date du service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu associé</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut du repas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réservations</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Présences constatées</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($repasList as $repas)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $repas->date->translatedFormat('l d F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $repas->menu?->nom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <x-status-badge :status="$repas->statut" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                    {{ $repas->reservations_count }} réservation(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-emerald-700 font-semibold">
                                    {{ $repas->reservations_presentes_count }} présent(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('repas.show', $repas) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                        Feuille de présence &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $repasList->links() }}
            </div>
        </div>
    @endif
@endsection
