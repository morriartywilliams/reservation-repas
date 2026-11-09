@extends('layouts.app')

@section('title', 'Feuille de Présence Repas')
@section('header')
    Repas du {{ $repas->date->translatedFormat('l d F Y') }}
@endsection
@section('subtitle')
    Menu : {{ $repas->menu?->nom }} · Statut : <x-status-badge :status="$repas->statut" />
@endsection

@section('actions')
    <div class="flex gap-2">
        @can('markServed', $repas)
            @if($repas->statut === 'planifie')
                <button 
                    type="button" 
                    onclick="openConfirmModal({
                        title: 'Clôturer et marquer le repas comme servi',
                        message: 'Cette action valide définitivement la fin du service de restauration pour ce jour. Une fois marqué comme servi, les présences seront irréversiblement verrouillées.',
                        confirmText: 'Marquer comme servi',
                        buttonClass: 'inline-flex justify-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-purple-700 sm:text-sm',
                        action: '{{ route('repas.markServed', $repas) }}',
                        method: 'PATCH'
                    })"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700"
                >
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Marquer servi & clôturer
                </button>
            @endif
        @endcan

        <a href="{{ route('repas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Retour aux repas
        </a>
    </div>
@endsection

@section('content')
    <!-- Repas Overview Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">
                    Plats au menu pour ce service :
                </span>
                <div class="flex flex-wrap gap-2">
                    @forelse($repas->menu->plats as $plat)
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                            {{ $plat->nom }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-400 italic">Aucun plat rattaché</span>
                    @endforelse
                </div>
            </div>

            <div class="flex gap-4 text-center">
                <div class="px-4 py-2 bg-gray-50 rounded-md border border-gray-200">
                    <div class="text-lg font-bold text-gray-900">{{ $repas->reservations->where('status', 'confirmee')->count() }}</div>
                    <div class="text-xs text-gray-500">Confirmées</div>
                </div>
                <div class="px-4 py-2 bg-emerald-50 rounded-md border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-700">{{ $repas->reservations->where('attendance_status', 'present')->count() }}</div>
                    <div class="text-xs text-emerald-700">Présents</div>
                </div>
                <div class="px-4 py-2 bg-red-50 rounded-md border border-red-200">
                    <div class="text-lg font-bold text-red-700">{{ $repas->reservations->where('attendance_status', 'absent')->count() }}</div>
                    <div class="text-xs text-red-700">Absents</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Sheet Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-gray-200 gap-2">
            <div>
                <h3 class="text-base font-semibold text-gray-900">
                    Validation des Présences au Réfectoire
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    @if($repas->statut === 'planifie')
                        Sélectionnez la présence pour chaque élève ayant réservé, puis validez.
                    @else
                        <span class="text-purple-700 font-semibold">Service clôturé :</span> Les données de présence sont désormais verrouillées et non modifiables.
                    @endif
                </p>
            </div>
        </div>

        @php
            $eligibleReservations = $repas->reservations->where('status', 'confirmee');
        @endphp

        @if($eligibleReservations->isEmpty())
            <x-empty-state 
                title="Aucune réservation confirmée" 
                message="Aucun élève n'a réservé pour ce repas ou toutes les réservations ont été annulées." 
            />
        @else
            @if($repas->statut === 'planifie')
                <form method="POST" action="{{ route('reservations.validateAttendance') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="repas_id" value="{{ $repas->id }}">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm mb-6">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Élève</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Paiement</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Statut actuel</th>
                                    <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase">Marquer Présence</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($eligibleReservations as $index => $res)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <input type="hidden" name="entries[{{ $index }}][reservation_id]" value="{{ $res->id }}">
                                            <div class="font-medium text-gray-900">{{ $res->eleve?->user?->nom_complet }}</div>
                                            <div class="text-xs font-mono text-gray-500">{{ $res->eleve?->matricule }}</div>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600">
                                            {{ $res->eleve?->classe }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($res->paiement)
                                                <x-status-badge :status="$res->paiement->statut" />
                                            @else
                                                <x-status-badge status="impaye" />
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <x-status-badge :status="$res->attendance_status" />
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center justify-center gap-4">
                                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                    <input 
                                                        type="radio" 
                                                        name="entries[{{ $index }}][attendance_status]" 
                                                        value="present"
                                                        {{ $res->attendance_status === 'present' ? 'checked' : '' }}
                                                        class="text-emerald-600 focus:ring-emerald-500 h-4 w-4"
                                                    >
                                                    <span class="text-xs font-medium text-emerald-800">Présent</span>
                                                </label>

                                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                    <input 
                                                        type="radio" 
                                                        name="entries[{{ $index }}][attendance_status]" 
                                                        value="absent"
                                                        {{ $res->attendance_status === 'absent' ? 'checked' : '' }}
                                                        class="text-red-600 focus:ring-red-500 h-4 w-4"
                                                    >
                                                    <span class="text-xs font-medium text-red-800">Absent</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer les présences
                        </button>
                    </div>
                </form>
            @else
                {{-- Read only attendance view when meal is already marked served --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Élève</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Paiement</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Présence constatée</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($eligibleReservations as $res)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900">{{ $res->eleve?->user?->nom_complet }}</div>
                                        <div class="text-xs font-mono text-gray-500">{{ $res->eleve?->matricule }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ $res->eleve?->classe }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($res->paiement)
                                            <x-status-badge :status="$res->paiement->statut" />
                                        @else
                                            <x-status-badge status="impaye" />
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <x-status-badge :status="$res->attendance_status" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </div>
@endsection
