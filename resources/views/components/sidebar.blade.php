@php
    $role = auth()->user()?->role?->nom;
@endphp

<div class="flex flex-col flex-grow pt-5 bg-emerald-800 overflow-y-auto text-white">
    <div class="flex items-center flex-shrink-0 px-4 mb-4">
        <span class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
            <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            Madrassati
        </span>
    </div>
    
    <div class="px-4 py-2 text-xs font-semibold text-emerald-200 uppercase tracking-wider">
        Menu Principal
    </div>

    <nav class="mt-2 flex-1 px-2 space-y-1">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Tableau de bord
        </a>

        {{-- Admin only: Users & Roles --}}
        @if($role === 'administrateur')
            <a href="{{ route('users.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('users.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Utilisateurs
            </a>

            <a href="{{ route('roles.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('roles.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Rôles & Permissions
            </a>
        @endif

        {{-- Staff: Students --}}
        @if(in_array($role, ['administrateur', 'gestionnaire']))
            <a href="{{ route('students.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('students.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
                Gestion des Élèves
            </a>
        @endif

        {{-- All: Menus --}}
        <a href="{{ route('menus.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('menus.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Menus Hebdomadaires
        </a>

        {{-- Staff: Plats --}}
        @if(in_array($role, ['administrateur', 'gestionnaire']))
            <a href="{{ route('plats.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('plats.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Catalogue des Plats
            </a>

            <a href="{{ route('repas.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('repas.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Repas Quotidiens
            </a>
        @endif

        {{-- All: Reservations --}}
        <a href="{{ route('reservations.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('reservations.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            {{ $role === 'eleve' ? 'Mes Réservations' : 'Réservations & Présences' }}
        </a>

        {{-- Staff: Paiements --}}
        @if(in_array($role, ['administrateur', 'gestionnaire']))
            <a href="{{ route('paiements.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('paiements.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Paiements & Caisse
            </a>

            <a href="{{ route('statistiques.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('statistiques.*') ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Statistiques
            </a>
        @endif
    </nav>
    
    <div class="p-4 border-t border-emerald-700 text-xs text-emerald-200">
        WorldSkills Algeria 2026
    </div>
</div>
