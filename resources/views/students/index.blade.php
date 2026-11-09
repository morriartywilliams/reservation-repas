@extends('layouts.app')

@section('title', 'Gestion des Élèves')
@section('header', 'Répertoire des Élèves')
@section('subtitle', 'Gestion des dossiers scolaires et inscription à la cantine.')

@section('actions')
    @can('create', \App\Models\Eleve::class)
        <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Inscrire un élève
        </a>
    @endcan
@endsection

@section('content')
    <x-search-bar :action="route('students.index')" placeholder="Rechercher par matricule, nom, prénom...">
        <select name="classe" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Toutes les classes</option>
            @foreach($classes as $c)
                <option value="{{ $c }}" {{ request('classe') == $c ? 'selected' : '' }}>
                    {{ $c }}
                </option>
            @endforeach
        </select>
    </x-search-bar>

    @if($students->isEmpty())
        <x-empty-state 
            title="Aucun élève trouvé" 
            message="Aucun élève ne correspond à vos filtres de recherche."
            :actionText="auth()->user()->can('create', \App\Models\Eleve::class) ? 'Inscrire un élève' : null"
            :actionUrl="auth()->user()->can('create', \App\Models\Eleve::class) ? route('students.create') : null"
        />
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Élève</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de naissance</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-semibold text-emerald-700">
                                    {{ $student->matricule }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $student->user?->nom_complet }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $student->classe }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->user?->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->date_naissance?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('students.show', $student) }}" class="text-gray-600 hover:text-gray-900">Détails</a>
                                    @can('update', $student)
                                        <a href="{{ route('students.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                    @endcan
                                    @can('delete', $student)
                                        <button 
                                            type="button" 
                                            onclick="openConfirmModal({
                                                title: 'Supprimer l\'élève',
                                                message: @js("Supprimer l'élève {$student->user?->nom_complet} (matricule : {$student->matricule}) ? Son compte associé sera aussi désactivé."),
                                                confirmText: 'Supprimer l\'élève',
                                                action: @js(route('students.destroy', $student)),
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
                {{ $students->links() }}
            </div>
        </div>
    @endif
@endsection
