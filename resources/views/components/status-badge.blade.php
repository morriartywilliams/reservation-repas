@props(['status'])

@php
    $status = strtolower((string) $status);
    
    $config = match($status) {
        'publie' => ['label' => 'Publié', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
        'brouillon' => ['label' => 'Brouillon', 'class' => 'bg-amber-100 text-amber-800 border-amber-300'],
        'planifie' => ['label' => 'Planifié', 'class' => 'bg-blue-100 text-blue-800 border-blue-300'],
        'servi' => ['label' => 'Servi', 'class' => 'bg-purple-100 text-purple-800 border-purple-300'],
        'confirmee' => ['label' => 'Confirmée', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
        'en_attente' => ['label' => 'En attente', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
        'annulee' => ['label' => 'Annulée', 'class' => 'bg-red-100 text-red-800 border-red-300'],
        'present' => ['label' => 'Présent', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
        'absent' => ['label' => 'Absent', 'class' => 'bg-red-100 text-red-800 border-red-300'],
        'non_verifiee' => ['label' => 'Non vérifiée', 'class' => 'bg-gray-100 text-gray-700 border-gray-300'],
        'paye' => ['label' => 'Payé', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
        'impaye' => ['label' => 'Impayé', 'class' => 'bg-rose-100 text-rose-800 border-rose-300'],
        'exonere' => ['label' => 'Exonéré', 'class' => 'bg-indigo-100 text-indigo-800 border-indigo-300'],
        'actif' => ['label' => 'Actif', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
        'inactif' => ['label' => 'Inactif', 'class' => 'bg-gray-100 text-gray-800 border-gray-300'],
        default => ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800 border-gray-200'],
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $config['class'] }}">
    {{ $config['label'] }}
</span>
