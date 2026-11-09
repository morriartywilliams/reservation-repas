@props([
    'title' => 'Aucun résultat trouvé',
    'message' => 'Aucun élément ne correspond à votre recherche ou la liste est actuellement vide.',
    'actionText' => null,
    'actionUrl' => null,
])

<div class="text-center py-12 px-4 bg-white rounded-lg border-2 border-dashed border-gray-300 my-4">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
    </svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ $title }}</h3>
    <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">{{ $message }}</p>
    @if ($actionText && $actionUrl)
        <div class="mt-6">
            <a href="{{ $actionUrl }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                {{ $actionText }}
            </a>
        </div>
    @endif
</div>
