@props(['paginator', 'showInfo' => true])

@if ($paginator->hasPages())
    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 p-6 animate-slide-up">
        <nav class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0" aria-label="Pagination">
            
            {{-- Info Section --}}
            @if($showInfo)
                <div class="flex items-center space-x-2">
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-600">
                            Menampilkan 
                            <span class="font-semibold text-yellow-600">{{ $paginator->firstItem() ?? 0 }}</span>
                            sampai 
                            <span class="font-semibold text-yellow-600">{{ $paginator->lastItem() ?? 0 }}</span>
                            dari 
                            <span class="font-semibold text-yellow-600">{{ $paginator->total() }}</span>
                            data
                        </p>
                    </div>
                    
                    {{-- Mobile info --}}
                    <div class="sm:hidden">
                        <p class="text-sm text-gray-600">
                            Hal <span class="font-semibold text-yellow-600">{{ $paginator->currentPage() }}</span> 
                            dari <span class="font-semibold text-yellow-600">{{ $paginator->lastPage() }}</span>
                        </p>
                    </div>
                </div>
            @endif
            
            {{-- Pagination Controls --}}
            <div class="flex items-center space-x-2">
                
                {{-- Previous Button --}}
                @if ($paginator->onFirstPage())
                    <span class="flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-xl cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium text-yellow-700 bg-white border border-yellow-300 rounded-xl hover:bg-yellow-50 hover:border-yellow-400 transition-all duration-200 hover:shadow-md transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </a>
                @endif

                {{-- Page Numbers (Desktop Only) --}}
                <div class="hidden md:flex items-center space-x-1">
                    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg ring-2 ring-yellow-300">
                                {{ $page }}
                            </span>
                        @elseif ($page == 1 || $page == $paginator->lastPage() || ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2))
                            <a href="{{ $url }}" 
                               class="flex items-center justify-center w-10 h-10 text-sm font-medium text-yellow-700 bg-white border border-yellow-300 rounded-xl hover:bg-yellow-50 hover:border-yellow-400 transition-all duration-200 hover:shadow-md transform hover:scale-105">
                                {{ $page }}
                            </a>
                        @elseif ($page == $paginator->currentPage() - 3 || $page == $paginator->currentPage() + 3)
                            <span class="flex items-center justify-center w-10 h-10 text-sm text-gray-400">
                                ...
                            </span>
                        @endif
                    @endforeach
                </div>

                {{-- Mobile Page Numbers --}}
                <div class="md:hidden flex items-center space-x-1">
                    @php
                        $start = max(1, $paginator->currentPage() - 1);
                        $end = min($paginator->lastPage(), $paginator->currentPage() + 1);
                    @endphp
                    
                    @if($start > 1)
                        <a href="{{ $paginator->url(1) }}" 
                           class="flex items-center justify-center w-10 h-10 text-sm font-medium text-yellow-700 bg-white border border-yellow-300 rounded-xl hover:bg-yellow-50">
                            1
                        </a>
                        @if($start > 2)
                            <span class="text-gray-400">...</span>
                        @endif
                    @endif
                    
                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $paginator->currentPage())
                            <span class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                                {{ $i }}
                            </span>
                        @else
                            <a href="{{ $paginator->url($i) }}" 
                               class="flex items-center justify-center w-10 h-10 text-sm font-medium text-yellow-700 bg-white border border-yellow-300 rounded-xl hover:bg-yellow-50">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor
                    
                    @if($end < $paginator->lastPage())
                        @if($end < $paginator->lastPage() - 1)
                            <span class="text-gray-400">...</span>
                        @endif
                        <a href="{{ $paginator->url($paginator->lastPage()) }}" 
                           class="flex items-center justify-center w-10 h-10 text-sm font-medium text-yellow-700 bg-white border border-yellow-300 rounded-xl hover:bg-yellow-50">
                            {{ $paginator->lastPage() }}
                        </a>
                    @endif
                </div>

                {{-- Next Button --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium text-yellow-700 bg-white border border-yellow-300 rounded-xl hover:bg-yellow-50 hover:border-yellow-400 transition-all duration-200 hover:shadow-md transform hover:scale-105">
                        <span class="hidden sm:inline">Selanjutnya</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span class="flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-xl cursor-not-allowed">
                        <span class="hidden sm:inline">Selanjutnya</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        </nav>
    </div>
@endif