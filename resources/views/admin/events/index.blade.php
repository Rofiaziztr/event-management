<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Manajemen Event') }}
                </h2>
            </div>
            <a href="{{ route('admin.events.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 border border-transparent rounded-xl font-semibold text-white hover:from-yellow-600 hover:to-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Event Baru
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes sunlightMove {
            0% { background-position: 100% 0; }
            100% { background-position: -100% 0; }
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

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            @if (session('success'))
                <div class="animate-fade-in">
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Event</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Sedang Berlangsung</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['berlangsung'] }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Event Bulan Ini</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['bulan_ini'] }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Filter & Pencarian Event</h3>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.events.index') }}" class="p-6 space-y-4">
                    <!-- Baris 1: Cari Nama Event (full width) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-2 md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Nama
                                Event</label>
                            <input type="text" name="search" id="search" placeholder="Masukkan nama event..."
                                value="{{ request('search') }}"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500" />
                        </div>
                    </div>

                    <!-- Baris 2: Kategori, Status, Dari Tanggal, Sampai Tanggal (sejajar) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-1 md:col-span-1">
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

                        <div class="lg:col-span-1 md:col-span-1">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                <option value="">Semua Status</option>
                                <option value="Terjadwal" @selected(request('status') == 'Terjadwal')>Terjadwal</option>
                                <option value="Berlangsung" @selected(request('status') == 'Berlangsung')>Berlangsung</option>
                                <option value="Dibatalkan" @selected(request('status') == 'Dibatalkan')>Dibatalkan</option>
                                <option value="Selesai" @selected(request('status') == 'Selesai')>Selesai</option>
                            </select>
                        </div>

                        <div class="lg:col-span-1 md:col-span-1">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari
                                Tanggal</label>
                            <x-date-picker-all-past name="start_date" id="start_date" :value="request('start_date')"
                                placeholder="Pilih Tanggal" />
                        </div>

                        <div class="lg:col-span-1 md:col-span-1">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai
                                Tanggal</label>
                            <x-date-picker-all-past name="end_date" id="end_date" :value="request('end_date')"
                                placeholder="Pilih Tanggal" />
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 flex items-center justify-end space-x-2">
                        <x-bladewind::button tag="a" href="{{ route('admin.events.index') }}" color="gray"
                            size="small" icon="x-circle">
                            Clear Filter
                        </x-bladewind::button>
                        <x-bladewind::button can_submit="true" color="indigo" size="small" icon="search">
                            Terapkan Filter
                        </x-bladewind::button>
                    </div>
                </form>
            </div>

            <!-- Daftar Event -->
            <div class="animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($events as $event)
                        <a href="{{ route('admin.events.show', $event) }}" class="block">
                            <div
                                class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden flex flex-col relative group h-full transition-all duration-300 hover:shadow-xl card-hover">
                                <div class="p-6 flex-grow z-10">
                                    <div class="flex justify-between items-start">
                                        <h3
                                            class="font-semibold text-lg text-gray-900 mb-2 group-hover:text-yellow-600">
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
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-l from-yellow-300/20 via-yellow-100/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"
                                    style="animation: sunlightMove 4s infinite linear;"></div>
                            </div>
                        </a>
                    @empty
                        <div class="md:col-span-3 text-center py-12">
                            <x-bladewind::empty-state message="Tidak ada event yang ditemukan sesuai filter." />
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