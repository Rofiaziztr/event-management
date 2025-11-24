<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Event Saya') }}
                </h2>
                <p class="text-gray-600 mt-1">Daftar event yang Anda ikuti</p>
            </div>
            <a href="{{ route('scan.index') }}"
                class="inline-flex items-center px-6 py-3 bg-yellow-500 border border-transparent rounded-xl font-semibold text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="5" height="5" rx="1" fill="currentColor" opacity="0.3" />
                    <rect x="16" y="3" width="5" height="5" rx="1" fill="currentColor"
                        opacity="0.3" />
                    <rect x="3" y="16" width="5" height="5" rx="1" fill="currentColor"
                        opacity="0.3" />
                    <rect x="16" y="16" width="5" height="5" rx="1" fill="currentColor"
                        opacity="0.3" />
                    <rect x="9" y="9" width="6" height="6" rx="1" stroke="currentColor"
                        stroke-width="1.5" fill="none" />
                    <circle cx="12" cy="12" r="1" fill="currentColor" />
                </svg>
                Scan QR Code
            </a>
        </div>
    </x-slot>

    @push('styles')
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes sunlightMove {
                0% {
                    background-position: 100% 0;
                }

                100% {
                    background-position: -100% 0;
                }
            }

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
                width: 40px;
                height: 40px;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .stats-icon-yellow {
                background-color: rgba(245, 158, 11, 0.1);
                color: rgba(245, 158, 11, 1);
            }

            .stats-icon-green {
                background-color: rgba(16, 185, 129, 0.1);
                color: rgba(16, 185, 129, 1);
            }

            .stats-icon-blue {
                background-color: rgba(59, 130, 246, 0.1);
                color: rgba(59, 130, 246, 1);
            }
        </style>
    @endpush

    <div
        class="max-w-full md:max-w-7xl lg:max-w-[90%] xl:max-w-[95%] 2xl:max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

        @if (session('success'))
            <div class="animate-fade-in">
                <div class="bg-green-50 border border-green-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
            <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="stats-icon stats-icon-yellow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">TOTAL EVENT</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $events->total() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="stats-icon stats-icon-green">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">SUDAH HADIR</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($attendedEventIds ?? []) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="stats-icon stats-icon-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">BELUM HADIR</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ ($events->total() ?? 0) - count($attendedEventIds ?? []) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="animate-fade-in">
            <div class="flex items-center bg-yellow-500 rounded-t-xl px-4 py-4">
                <svg class="w-5 h-5 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <h3 class="text-lg font-semibold text-white">Filter & Pencarian Event</h3>
            </div>
            <div class="bg-white shadow-xl border border-yellow-200 border-t-0 overflow-hidden">
                <form method="GET" action="{{ route('participant.events.index') }}" class="p-4 sm:p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div class="sm:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Nama
                                Event</label>
                            <input type="text" name="search" id="search" placeholder="Masukkan nama event..."
                                value="{{ request('search') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        <div>
                            <label for="category_id"
                                class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select name="category_id" id="category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                                <option value="">Semua Status</option>
                                <option value="Terjadwal" {{ request('status') == 'Terjadwal' ? 'selected' : '' }}>
                                    Terjadwal</option>
                                <option value="Berlangsung"
                                    {{ request('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Dari
                                Tanggal</label>
                            <x-date-picker-all-past name="start_date" id="start_date" :value="request('start_date')"
                                placeholder="Pilih Tanggal" />
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Sampai
                                Tanggal</label>
                            <x-date-picker-all-past name="end_date" id="end_date" :value="request('end_date')"
                                placeholder="Pilih Tanggal" />
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-2 sm:gap-3 pt-2">
                        <button type="button"
                            onclick="window.location.href='{{ route('participant.events.index') }}'"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-gray-500 border border-transparent rounded-lg sm:rounded-xl font-medium text-white text-sm hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear Filter
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-indigo-600 border border-transparent rounded-lg sm:rounded-xl font-medium text-white text-sm hover:bg-indigo-700 transition-colors">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Events Grid --}}
        <div class="animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($events as $event)
                    <a href="{{ route('participant.events.show', $event) }}" class="block">
                        <div
                            class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden flex flex-col relative group h-full transition-all duration-300 hover:shadow-xl card-hover">
                            <div class="p-6 flex-grow z-10">
                                <div class="flex justify-between items-start">
                                    <h3
                                        class="font-semibold text-lg text-gray-900 mb-2 group-hover:text-yellow-600 line-clamp-2">
                                        {{ Str::limit($event->title, 50) }}
                                    </h3>
                                    <x-bladewind::tag label="{{ $event->status }}"
                                        color="{{ $event->status == 'Terjadwal' ? 'cyan' : ($event->status == 'Berlangsung' ? 'green' : ($event->status == 'Selesai' ? 'gray' : 'red')) }}" />
                                </div>

                                @if ($event->category)
                                    <div class="mb-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $event->category->name }}
                                        </span>
                                    </div>
                                @endif

                                <div class="space-y-3 mt-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 3v2.25M10.5 3v2.25M14.25 3v2.25M18 3v2.25M4.5 7.5h15M4.5 12h15m-7.5 4.5h.008v.008H12v-.008z" />
                                        </svg>
                                        <span>{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $event->countdown_status }}</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-3 text-gray-400 mt-1 shrink-0"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>{{ $event->participants->count() }} peserta</span>
                                    </div>
                                </div>

                                <div class="mt-5 pt-4 border-t border-gray-200">
                                    @php
                                        $hasAttended = in_array($event->id, $attendedEventIds ?? []);
                                        $eventStatus = $event->status;
                                        $isEventFinished = $eventStatus === 'Selesai';
                                        $isEventOngoing = $eventStatus === 'Berlangsung';
                                        $isEventScheduled = $eventStatus === 'Terjadwal';
                                        $isEventCancelled = $eventStatus === 'Dibatalkan';
                                    @endphp

                                    @if ($hasAttended)
                                        <!-- Sudah hadir -->
                                        <div class="flex items-center text-green-600">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-medium">Anda sudah hadir</span>
                                        </div>
                                    @elseif ($isEventCancelled)
                                        <!-- Event dibatalkan -->
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                            <span class="text-sm font-medium">Event dibatalkan</span>
                                        </div>
                                    @elseif ($isEventFinished && !$hasAttended)
                                        <!-- Event selesai tapi tidak hadir -->
                                        <div class="flex items-center text-red-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="text-sm font-medium">Tidak hadir</span>
                                        </div>
                                    @elseif ($isEventOngoing && !$hasAttended)
                                        <!-- Event sedang berlangsung tapi belum presensi -->
                                        <div class="flex items-center text-yellow-600">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-medium">Belum melakukan presensi</span>
                                        </div>
                                    @elseif ($isEventScheduled && !$hasAttended)
                                        <!-- Event belum dimulai -->
                                        <div class="flex items-center text-blue-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm font-medium">Event belum dimulai</span>
                                        </div>
                                    @else
                                        <!-- Fallback untuk kondisi lain -->
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm font-medium">Status tidak diketahui</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-l from-yellow-300/20 via-yellow-100/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"
                                style="animation: sunlightMove 4s infinite linear;"></div>
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-3 text-center py-12">
                        <x-bladewind::empty-state message="Anda belum terdaftar di event mana pun." />
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <x-yellow-pagination :paginator="$events" />
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
