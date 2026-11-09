@extends('layouts.app')

@section('title', 'Détails de l\'Élève')
@section('header')
    {{ $eleve->user?->nom_complet }}
@endsection
@section('subtitle')
    Dossier de l'élève · Matricule : {{ $eleve->matricule }}
@endsection

@section('actions')
    <div class="flex gap-2">
        @can('update', $eleve)
            <a href="{{ route('students.edit', $eleve) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Modifier
            </a>
        @endcan
        <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700">
            Retour à la liste
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Info Card -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                Fiche Renseignement
            </h3>
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Matricule</dt>
                    <dd class="mt-1 font-mono font-bold text-emerald-700">{{ $eleve->matricule }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Classe</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $eleve->classe }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Date de naissance</dt>
                    <dd class="mt-1 text-gray-900">{{ $eleve->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Email</dt>
                    <dd class="mt-1 text-gray-900">{{ $eleve->user?->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Téléphone</dt>
                    <dd class="mt-1 text-gray-900">{{ $eleve->user?->telephone ?? 'Non renseigné' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Total réservations</dt>
                    <dd class="mt-1 text-lg font-bold text-gray-900">{{ $eleve->reservations->count() }}</dd>
                </div>
            </dl>
        </div>

        <!-- Student Reservation History -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-lg border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                Historique des Réservations de Cantine
            </h3>

            @if($eleve->reservations->isEmpty())
                <x-empty-state 
                    title="Aucune réservation" 
                    message="Cet élève n'a effectué aucune réservation de repas pour le moment." 
                />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="py-2 px-3">Date repas</th>
                                <th class="py-2 px-3">Menu</th>
                                <th class="py-2 px-3">Statut</th>
                                <th class="py-2 px-3">Présence</th>
                                <th class="py-2 px-3">Paiement</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($eleve->reservations->sortByDesc('date_reservation') as $res)
                                <tr>
                                    <td class="py-3 px-3 whitespace-nowrap font-medium text-gray-900">
                                        {{ $res->repas?->date?->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-3 text-gray-600">
                                        {{ $res->repas?->menu?->nom }}
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <x-status-badge :status="$res->status" />
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <x-status-badge :status="$res->attendance_status" />
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @if($res->paiement)
                                            <x-status-badge :status="$res->paiement->statut" />
                                        @else
                                            <x-status-badge status="impaye" />
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
