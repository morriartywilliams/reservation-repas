<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Madrassati') }} - @yield('title', 'Gestion de Restauration')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-gray-900">
    <div class="min-h-full flex flex-col md:flex-row">
        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 z-20">
            <x-sidebar />
        </aside>

        <!-- Mobile Drawer Sidebar -->
        <div id="mobile-sidebar" class="fixed inset-0 z-40 flex md:hidden hidden" role="dialog" aria-modal="true">
            <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-emerald-800">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button id="mobile-sidebar-close" type="button" aria-label="Fermer le menu" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <x-sidebar />
            </div>
            <div class="flex-shrink-0 w-14"></div>
        </div>

        <!-- Main Content Area -->
        <div class="md:pl-64 flex flex-col flex-1 min-w-0">
            <x-navbar />

            <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8 max-w-7xl w-full mx-auto">
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                            @yield('header')
                        </h1>
                        @hasSection('subtitle')
                            <p class="mt-1 text-sm text-gray-500">
                                @yield('subtitle')
                            </p>
                        @endif
                    </div>
                    <div>
                        @yield('actions')
                    </div>
                </div>

                <x-alert />

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Universal Confirmation Modal for Destructive Actions -->
    <x-confirm-modal />
</body>
</html>
