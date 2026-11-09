@props([
    'placeholder' => 'Rechercher...',
    'action' => '',
    'resetUrl' => '',
])

<form method="GET" action="{{ $action }}" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="w-full md:flex-1 relative">
            <label for="search-input" class="sr-only">Recherche</label>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                id="search-input"
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="{{ $placeholder }}"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
            >
        </div>

        {{-- Slot for extra dropdown filters (status, role, dates, etc.) --}}
        @if ($slot->isNotEmpty())
            <div class="w-full md:w-auto flex flex-wrap gap-3 items-center">
                {{ $slot }}
            </div>
        @endif

        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Filtrer
            </button>
            @if(request()->hasAny(['search', 'role_id', 'classe', 'statut', 'status', 'attendance_status', 'date', 'date_debut', 'date_fin', 'menu_id', 'moyen']))
                <a href="{{ $resetUrl ?: url()->current() }}" class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Réinitialiser
                </a>
            @endif
        </div>
    </div>
</form>
