<header class="bg-white border-b border-gray-200 sticky top-0 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <button id="sidebar-toggle" type="button" aria-label="Ouvrir le menu de navigation" class="md:hidden px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-lg font-bold text-gray-800">
                        Système de Restauration Scolaire
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-semibold text-gray-900 leading-tight">
                                {{ auth()->user()->nom_complet }}
                            </div>
                            <div class="text-xs text-gray-500 flex items-center justify-end gap-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                {{ ucfirst(auth()->user()->role?->nom ?? 'Utilisateur') }}
                                @if(auth()->user()->eleve)
                                    — {{ auth()->user()->eleve->classe }}
                                @endif
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" aria-label="Se déconnecter" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
