@if ($paginator->hasPages())
    <nav class="flex items-center justify-between border-t border-yellow-200 bg-gradient-to-r from-yellow-50 to-white px-4 py-3 sm:px-6 rounded-xl shadow-sm" aria-label="Pagination">
        <div class="hidden sm:block">
            <p class="text-sm text-gray-700">
                Menampilkan
                <span class="font-medium text-yellow-600">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-medium text-yellow-600">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-medium text-yellow-600">{{ $paginator->total() }}</span>
                hasil
            </p>
        </div>
        
        <div class="flex flex-1 justify-between space-x-2 sm:justify-end sm:space-x-3">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center rounded-xl border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                    <span class="sm:hidden">Prev</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="relative inline-flex items-center rounded-xl border border-yellow-300 bg-white px-4 py-2 text-sm font-medium text-yellow-700 hover:bg-yellow-50 hover:border-yellow-400 transition-all duration-200 hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                    <span class="sm:hidden">Prev</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="hidden sm:flex sm:space-x-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="relative inline-flex items-center rounded-xl bg-gradient-to-r from-yellow-500 to-yellow-600 px-4 py-2 text-sm font-semibold text-white shadow-md ring-1 ring-yellow-500">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" 
                                   class="relative inline-flex items-center rounded-xl border border-yellow-300 bg-white px-4 py-2 text-sm font-medium text-yellow-700 hover:bg-yellow-50 hover:border-yellow-400 transition-all duration-200 hover:shadow-md">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Mobile page info --}}
            <div class="flex sm:hidden items-center space-x-3">
                <span class="text-sm text-gray-700">
                    {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="relative inline-flex items-center rounded-xl border border-yellow-300 bg-white px-4 py-2 text-sm font-medium text-yellow-700 hover:bg-yellow-50 hover:border-yellow-400 transition-all duration-200 hover:shadow-md">
                    <span class="hidden sm:inline">Selanjutnya</span>
                    <span class="sm:hidden">Next</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="relative inline-flex items-center rounded-xl border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
                    <span class="hidden sm:inline">Selanjutnya</span>
                    <span class="sm:hidden">Next</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif