@if ($paginator->hasPages())
    <div class="flex items-center justify-between gap-2 px-6 py-3 border-t border-stone-200 dark:border-strokedark">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing <span class="font-medium">{{ $paginator->firstItem() }}</span> to <span class="font-medium">{{ $paginator->lastItem() }}</span> of <span class="font-medium">{{ $paginator->total() }}</span> results
        </div>
        <nav class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm font-medium text-stone-400 cursor-not-allowed rounded-lg">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-stone-700 hover:text-stone-900 hover:bg-stone-50 rounded-lg transition-colors dark:text-stone-300 dark:hover:bg-gray-800">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </a>
            @endif

            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
            @endphp

            {{-- Always show 1, 2, 3 --}}
            <a href="{{ $paginator->url(1) }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $currentPage == 1 ? 'bg-emerald-600 text-white' : 'text-stone-700 hover:text-stone-900 hover:bg-stone-50 dark:text-stone-300 dark:hover:bg-gray-800' }}">
                1
            </a>

            @if ($lastPage > 1)
                <a href="{{ $paginator->url(2) }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $currentPage == 2 ? 'bg-emerald-600 text-white' : 'text-stone-700 hover:text-stone-900 hover:bg-stone-50 dark:text-stone-300 dark:hover:bg-gray-800' }}">
                    2
                </a>
            @endif

            @if ($lastPage > 2)
                <a href="{{ $paginator->url(3) }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $currentPage == 3 ? 'bg-emerald-600 text-white' : 'text-stone-700 hover:text-stone-900 hover:bg-stone-50 dark:text-stone-300 dark:hover:bg-gray-800' }}">
                    3
                </a>
            @endif

            {{-- Show ellipsis and last page only if last page > 3 --}}
            @if ($lastPage > 3)
                <span class="px-3 py-2 text-sm font-medium text-stone-500 dark:text-stone-400">
                    ...
                </span>

                {{-- Show current page if it's not 1, 2, 3, or last page --}}
                @if ($currentPage > 3 && $currentPage < $lastPage)
                    <span class="px-3 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg">
                        {{ $currentPage }}
                    </span>
                    <span class="px-3 py-2 text-sm font-medium text-stone-500 dark:text-stone-400">
                        ...
                    </span>
                @endif

                {{-- Last Page --}}
                @if ($currentPage == $lastPage)
                    <span class="px-3 py-2 text-sm font-medium bg-emerald-600 text-white rounded-lg">
                        {{ $lastPage }}
                    </span>
                @else
                    <a href="{{ $paginator->url($lastPage) }}" class="px-3 py-2 text-sm font-medium text-stone-700 hover:text-stone-900 hover:bg-stone-50 rounded-lg transition-colors dark:text-stone-300 dark:hover:bg-gray-800">
                        {{ $lastPage }}
                    </a>
                @endif
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-stone-700 hover:text-stone-900 hover:bg-stone-50 rounded-lg transition-colors dark:text-stone-300 dark:hover:bg-gray-800">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            @else
                <span class="px-3 py-2 text-sm font-medium text-stone-400 cursor-not-allowed rounded-lg">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </span>
            @endif
        </nav>
    </div>
@endif

