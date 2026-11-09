@extends('layouts.app')

@section('title', 'Statistiques & Fréquentation')
@section('header', 'Statistiques & Indicateurs de Performance')
@section('subtitle', 'Analyses chiffrées de la cantine, taux de fréquentation et suivi financier.')

@section('content')
    <div class="space-y-6">
        {{-- Filtres de date --}}
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-4">
            <form action="{{ route('statistiques.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
                <div class="flex-1">
                    <label for="date_debut" class="block text-xs font-medium text-gray-700 mb-1">Période du</label>
                    <input 
                        type="date" 
                        name="date_debut" 
                        id="date_debut" 
                        value="{{ $dateDebut }}" 
                        class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>
                <div class="flex-1">
                    <label for="date_fin" class="block text-xs font-medium text-gray-700 mb-1">Au</label>
                    <input 
                        type="date" 
                        name="date_fin" 
                        id="date_fin" 
                        value="{{ $dateFin }}" 
                        class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none">
                        Filtrer
                    </button>
                    @if($dateDebut || $dateFin)
                        <a href="{{ route('statistiques.index') }}" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- KPI Cards per §16 --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Repas servis --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-purple-50 rounded-md text-purple-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs font-medium text-gray-500 uppercase truncate">Repas Servis</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $repasServisCount }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2 text-xs text-gray-500 border-t border-gray-100">
                    Statut "servi" sur la période
                </div>
            </div>

            {{-- Total réservations --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-blue-50 rounded-md text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs font-medium text-gray-500 uppercase truncate">Total Réservations</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $totalReservationsCount }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2 text-xs text-gray-500 border-t border-gray-100">
                    Tous statuts confondus
                </div>
            </div>

            {{-- Taux de fréquentation (§16: count(present) / count(confirmee && servi) * 100) --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-emerald-50 rounded-md text-emerald-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs font-medium text-gray-500 uppercase truncate">Taux de Fréquentation</dt>
                                <dd class="mt-1 text-2xl font-bold text-emerald-600">{{ $tauxFrequentation }}%</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2 text-xs text-gray-500 border-t border-gray-100">
                    {{ $presentCount }} présents / {{ $confirmedServedCount }} réservations servies
                </div>
            </div>

            {{-- Recettes encaissées --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-amber-50 rounded-md text-amber-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs font-medium text-gray-500 uppercase truncate">Recettes Encaissées</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">
                                    {{ number_format($paiementStats['paye'], 2, ',', ' ') }} DA
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-2 text-xs text-gray-500 border-t border-gray-100">
                    {{ $paiementStats['count_paye'] }} paiements réglés
                </div>
            </div>
        </div>

        {{-- Détails analytiques --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Répartition par statut de réservation --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b pb-2">
                    Statut des Réservations
                </h3>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-emerald-700 font-medium">Confirmées</span>
                            <span class="font-bold text-gray-900">{{ $statusBreakdown['confirmee'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $totalReservationsCount > 0 ? ($statusBreakdown['confirmee'] / $totalReservationsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-red-700 font-medium">Annulées</span>
                            <span class="font-bold text-gray-900">{{ $statusBreakdown['annulee'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalReservationsCount > 0 ? ($statusBreakdown['annulee'] / $totalReservationsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-yellow-700 font-medium">En attente</span>
                            <span class="font-bold text-gray-900">{{ $statusBreakdown['en_attente'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalReservationsCount > 0 ? ($statusBreakdown['en_attente'] / $totalReservationsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Analyse des présences --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b pb-2">
                    Présences aux Repas
                </h3>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-emerald-700 font-medium">Présents</span>
                            <span class="font-bold text-gray-900">{{ $attendanceBreakdown['present'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $totalReservationsCount > 0 ? ($attendanceBreakdown['present'] / $totalReservationsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-red-700 font-medium">Absents</span>
                            <span class="font-bold text-gray-900">{{ $attendanceBreakdown['absent'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalReservationsCount > 0 ? ($attendanceBreakdown['absent'] / $totalReservationsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 font-medium">Non vérifiées</span>
                            <span class="font-bold text-gray-900">{{ $attendanceBreakdown['non_verifiee'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-gray-400 h-2 rounded-full" style="width: {{ $totalReservationsCount > 0 ? ($attendanceBreakdown['non_verifiee'] / $totalReservationsCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Situation de la caisse --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b pb-2">
                    Situation de la Caisse
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between p-2 bg-emerald-50 rounded border border-emerald-100">
                        <div>
                            <div class="font-semibold text-emerald-900">Encaissé (Payé)</div>
                            <div class="text-xs text-emerald-700">{{ $paiementStats['count_paye'] }} règlements</div>
                        </div>
                        <div class="text-base font-bold text-emerald-800">
                            {{ number_format($paiementStats['paye'], 2, ',', ' ') }} DA
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-2 bg-rose-50 rounded border border-rose-100">
                        <div>
                            <div class="font-semibold text-rose-900">Reste à recouvrer (Impayé)</div>
                            <div class="text-xs text-rose-700">{{ $paiementStats['count_impaye'] }} créances</div>
                        </div>
                        <div class="text-base font-bold text-rose-800">
                            {{ number_format($paiementStats['impaye'], 2, ',', ' ') }} DA
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-2 bg-indigo-50 rounded border border-indigo-100">
                        <div>
                            <div class="font-semibold text-indigo-900">Exonérations accordées</div>
                            <div class="text-xs text-indigo-700">Dossiers sociaux / boursiers</div>
                        </div>
                        <div class="text-base font-bold text-indigo-800">
                            {{ $paiementStats['count_exonere'] }} repas
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
