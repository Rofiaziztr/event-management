<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0"
             x-data="slideIn('down', 100)">
            <div x-data="fadeIn(200)">
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                    <span class="mr-3 text-2xl">🎪</span>
                    {{ __('Event Saya') }}
                </h2>
                <p class="text-gray-600 mt-1">Daftar event yang Anda ikuti</p>
            </div>
            <a href="{{ route('scan.index') }}"
                class="inline-flex items-center px-6 py-3 bg-yellow-500 border border-transparent rounded-xl font-semibold text-white hover:bg-yellow-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                x-data="slideIn('right', 300)">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h4.5v4.5h-4.5v-4.5z" />
                </svg>
                Scan Presensi
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
        </style>
    @endpush

    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-8">

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
                            <div class="stats-icon stats-icon-green">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Sudah Hadir</p>
                            <p class="text-2xl font-bold text-gray-900">{{ count($attendedEventIds ?? []) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="stats-icon stats-icon-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Belum Hadir</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ ($events->total() ?? 0) - count($attendedEventIds ?? []) }}</p>
                        </div>
                    </div>
                </div>


            </div>

            {{-- Filter Section --}}
            <div class="bg-yellow-500 rounded-t-xl p-4 animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Filter & Pencarian Event</h3>
                    </div>
                </div>

                <form method="GET" action="{{ route('participant.events.index') }}" class="bg-white rounded-b-xl p-6 space-y-4 shadow-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Nama
                                Event</label>
                            <input type="text" name="search" id="search" placeholder="Masukkan nama event..."
                                value="{{ request('search') }}"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500" />
                        </div>

                        <div>
                            <label for="category_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select id="category_id" name="category_id"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari
                                    Tanggal</label>
                                <x-date-picker-all-past name="start_date" id="start_date" :value="request('start_date')"
                                    placeholder="Pilih Tanggal" />
                            </div>

                            <div class="flex-1">
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai
                                    Tanggal</label>
                                <x-date-picker-all-past name="end_date" id="end_date" :value="request('end_date')"
                                    placeholder="Pilih Tanggal" />
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2 mt-4">
                        <x-bladewind::button tag="a" href="{{ route('participant.events.index') }}"
                            color="gray" size="small" icon="x-circle">
                            Clear Filter
                        </x-bladewind::button>
                        <x-bladewind::button can_submit="true" color="indigo" size="small" icon="search">
                            Terapkan Filter
                        </x-bladewind::button>
                    </div>
                </form>
            </div>

            {{-- Events Grid --}}
            <div class="bg-yellow-500 rounded-t-xl p-4 animate-fade-in mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Kartu Event</h3>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in">
                @forelse ($events as $event)
                    <a href="{{ route('participant.events.show', $event) }}" class="block">
                        <div
                            class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col relative group transition-all duration-300 card-hover">
                            <div class="p-6 flex-grow z-10">
                                <div class="flex justify-between items-start mb-2">
                                    <h3
                                        class="font-semibold text-lg text-gray-900 group-hover:text-yellow-600 line-clamp-2">
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
                                        <span class="line-clamp-1">{{ $event->location }}</span>
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
                                    @if (in_array($event->id, $attendedEventIds ?? []))
                                        <div class="flex items-center text-green-600">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-medium">Anda sudah hadir</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-yellow-600">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-medium">Belum melakukan presensi</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-l from-yellow-300/20 via-yellow-100/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"
                                style="animation: sunlightMove 4s infinite linear;"></div>
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                        <x-bladewind::empty-state message="Anda belum terdaftar di event mana pun." />
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <x-yellow-pagination :paginator="$events" />
            </div>
    </div>
</x-app-layout>
