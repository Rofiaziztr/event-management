<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-2 md:space-y-4 lg:space-y-0">
            <div class="w-full lg:w-auto">
                <h2 class="font-bold text-lg md:text-xl lg:text-2xl text-gray-800 leading-tight truncate">{{ $event->title }}</h2>
                <p class="text-gray-600 mt-1 text-xs md:text-sm">Dibuat oleh {{ $event->creator->full_name }}</p>
                @if ($event->category)
                    <p class="text-xs md:text-xs text-yellow-600 font-medium">{{ $event->category->name }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $dynamicStatus = $event->status;
                @endphp
                @if ($dynamicStatus == 'Terjadwal')
                    <span class="inline-flex items-center px-3 py-1 md:px-4 md:py-1.5 lg:px-4 lg:py-1.5 rounded-full text-xs md:text-sm lg:text-base font-medium bg-cyan-100 text-cyan-800">
                        <div class="w-2 h-2 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3 bg-cyan-400 rounded-full mr-2"></div>{{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Berlangsung')
                    <span class="inline-flex items-center px-3 py-1 md:px-4 md:py-1.5 lg:px-4 lg:py-1.5 rounded-full text-xs md:text-sm lg:text-base font-medium bg-emerald-100 text-emerald-800">
                        <div class="w-2 h-2 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3 bg-emerald-400 rounded-full mr-2 animate-pulse"></div>{{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Selesai')
                    <span class="inline-flex items-center px-3 py-1 md:px-4 md:py-1.5 lg:px-4 lg:py-1.5 rounded-full text-xs md:text-sm lg:text-base font-medium bg-gray-100 text-gray-800">
                        <div class="w-2 h-2 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3 bg-gray-400 rounded-full mr-2"></div>{{ $dynamicStatus }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 md:px-4 md:py-1.5 lg:px-4 lg:py-1.5 rounded-full text-xs md:text-sm lg:text-base font-medium bg-red-100 text-red-800">
                        <div class="w-2 h-2 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3 bg-red-400 rounded-full mr-2"></div>{{ $dynamicStatus }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        /* Transisi yang lebih halus untuk navigasi */
        .tab-transition {
            transition: all 0.3s ease;
        }
        
        /* Animasi mengalir untuk tab aktif */
        .active-tab-border {
            position: relative;
            overflow: hidden;
        }
        
        .active-tab-border::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #f59e0b, #eab308, #f59e0b);
            background-size: 200% 100%;
            animation: flowingBorder 2s linear infinite;
        }
        
        @keyframes flowingBorder {
            0% {
                background-position: 0% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }
        
        .content-transition {
            opacity: 0;
            transform: translateY(5px);
            animation: fadeUp 0.4s forwards;
        }
        
        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    @endpush

    @push('styles')
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        .stats-icon {
            width: 38px;
            height: 38px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .stats-icon-yellow {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }
        .stats-icon-green {
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        }
        .stats-icon-blue {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        }
        .stats-icon-purple {
            background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
        }
        .stats-icon-orange {
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
        }
    </style>
    @endpush

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-full md:max-w-7xl lg:max-w-[90%] xl:max-w-[95%] 2xl:max-w-full mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">
            {{-- Event Info Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-3 md:gap-4 lg:gap-6 animate-fade-in">
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="stats-icon stats-icon-yellow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Peserta</p>
                            <p class="text-xl font-bold text-gray-900">{{ $event->participants->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="stats-icon stats-icon-green">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Sudah Hadir</p>
                            <p class="text-xl font-bold text-gray-900">{{ $event->attendances->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="stats-icon stats-icon-purple">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Dokumen</p>
                            <p class="text-xl font-bold text-gray-900">{{ $event->documents->whereNotNull('file_path')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="stats-icon stats-icon-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Waktu Tersisa</p>
                            <p class="text-xl font-bold text-gray-900">{{ $event->countdown_status }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Tabs --}}
            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-x-auto">
                <div class="border-b border-gray-100">
                    <nav class="flex md:space-x-8 lg:space-x-10 xl:space-x-12 px-3 md:px-6 lg:px-8 xl:px-10 min-w-max" aria-label="Tabs">
                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'detail']) }}"
                            class="py-3 md:py-4 px-3 md:px-2 lg:px-3 border-b-2 font-medium text-xs md:text-sm tab-transition whitespace-nowrap {{ $activeTab == 'detail' ? 'border-yellow-500 text-yellow-600 active-tab-border' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Detail</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'peserta']) }}"
                            class="py-3 md:py-4 px-3 md:px-2 lg:px-3 border-b-2 font-medium text-xs md:text-sm tab-transition whitespace-nowrap {{ $activeTab == 'peserta' ? 'border-yellow-500 text-yellow-600 active-tab-border' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <span>Peserta ({{ $event->participants->count() }})</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'lampiran']) }}"
                            class="py-3 md:py-4 px-3 md:px-2 lg:px-3 border-b-2 font-medium text-xs md:text-sm tab-transition whitespace-nowrap {{ $activeTab == 'lampiran' ? 'border-yellow-500 text-yellow-600 active-tab-border' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                <span>Lampiran</span>
                                <span class="text-xs md:text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 md:px-2 md:py-1 rounded-full">{{ $event->documents->whereNotNull('file_path')->count() }}</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'notulensi']) }}"
                            class="py-3 md:py-4 px-3 md:px-1 border-b-2 font-medium text-xs md:text-sm tab-transition whitespace-nowrap {{ $activeTab == 'notulensi' ? 'border-yellow-500 text-yellow-600 active-tab-border' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span>Notulensi</span>
                            </div>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Tab Content --}}
            <div class="content-transition">
                @if ($activeTab == 'detail')
                    @include('admin.events.tabs.detail', ['event' => $event])
                @elseif ($activeTab == 'peserta')
                    @include('admin.events.tabs.peserta', [
                        'event' => $event,
                        'potentialParticipants' => $potentialParticipants,
                        'errors' => $errors,
                    ])
                @elseif ($activeTab == 'lampiran')
                    @include('admin.events.tabs.lampiran', ['event' => $event])
                @elseif ($activeTab == 'notulensi')
                    @include('admin.events.tabs.notulensi', ['event' => $event])
                @endif
            </div>


            
            {{-- Navigation Action Bar --}}
            <div class="flex flex-wrap gap-3 justify-end">
                {{-- Tombol Kembali --}}
                <a href="{{ route('admin.events.index') }}"
                    class="inline-flex items-center px-3 md:px-5 py-2 md:py-2.5 bg-gray-100 border border-gray-300 rounded-xl text-sm md:font-medium text-gray-700 shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>

                {{-- Tombol Edit --}}
                <a href="{{ route('admin.events.edit', $event) }}"
                    class="inline-flex items-center px-3 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl text-sm md:font-medium text-white shadow-sm hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Edit
                </a>

                {{-- Tombol Hapus --}}
                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline"
                    onsubmit="return confirm('Yakin ingin menghapus event ini? Data terkait akan hilang permanen.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-3 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-red-500 to-red-600 rounded-xl text-sm md:font-medium text-white shadow-sm hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function submitBulkInvite(method) {
            document.getElementById('invite_method_input').value = method;
            if (method === 'all') {
                const divisionSelect = document.getElementById('division');
                if (divisionSelect) divisionSelect.value = '';
            }
            document.getElementById('bulk-invite-form').submit();
        }
    </script>
    @endpush
</x-app-layout>