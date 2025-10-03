<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Dashboard Admin
                </h2>
                <p class="text-gray-600 mt-1">Selamat datang kembali, {{ auth()->user()->full_name }}</p>
                <p class="text-sm text-yellow-600 font-medium">Administrator Sistem</p>
            </div>
            <a href="{{ route('admin.events.create') }}"
                class="inline-flex items-center px-6 py-3 bg-yellow-500 border border-transparent rounded-xl font-semibold text-white hover:bg-yellow-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Event Baru
            </a>
        </div>
    </x-slot>

    <div
        class="max-w-full md:max-w-7xl lg:max-w-[90%] xl:max-w-[95%] 2xl:max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

        <!-- Statistics Cards section has been removed as requested -->

        @push('styles')
            <style>
                .chart-container {
                    position: relative;
                    width: 100%;
                    min-height: 400px;
                }

                @media (min-width: 1280px) {
                    .chart-container {
                        min-height: 420px;
                        /* Sedikit lebih tinggi untuk layar besar */
                    }
                }
            </style>
        @endpush

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

        {{-- Main Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in">
            <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="stats-icon stats-icon-yellow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Event</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalEvents }}</p>
                        <div class="flex items-center mt-1">
                            <span
                                class="text-xs font-medium {{ $eventGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $eventGrowth >= 0 ? '+' : '' }}{{ $eventGrowth }}% bulan ini
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="stats-icon stats-icon-orange">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Peserta</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalParticipants }}</p>
                        <p class="text-xs text-green-600 mt-1">{{ $activeEvents }} event aktif</p>
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
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Kehadiran</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalAttendances }}</p>
                        <div class="flex items-center mt-1">
                            <span
                                class="text-xs font-medium {{ $attendanceGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $attendanceGrowth >= 0 ? '+' : '' }}{{ $attendanceGrowth }}% bulan ini
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="stats-icon stats-icon-yellow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Rata-rata Kehadiran</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $averageAttendanceRate }}%</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            @if ($averageAttendanceRate >= 90)
                                Sangat Baik
                            @elseif($averageAttendanceRate >= 80)
                                Baik
                            @elseif($averageAttendanceRate >= 70)
                                Cukup
                            @else
                                Perlu Perbaikan
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Dashboard Content --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            {{-- Left Side - Charts --}}
            <div class="xl:col-span-2 space-y-8">

                {{-- Event Trend Chart --}}
                {{-- FIX: Menambahkan class h-[550px] untuk memaksa tinggi card dan flex flex-col agar konten di dalamnya bisa diatur --}}
                <div
                    class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-fade-in flex flex-col h-[550px]">
                    <div class="p-6 border-b border-gray-100">
                        <div
                            class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Tren Event per Kategori</h3>
                                <p class="text-gray-500 mt-1">Jumlah event per bulan (12 bulan terakhir) berdasarkan
                                    kategori</p>
                            </div>
                            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex space-x-3">
                                <select name="category_filter" onchange="this.form.submit()"
                                    class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                    <option value="all" {{ $selectedCategory == 'all' ? 'selected' : '' }}>Semua
                                        Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}"
                                            {{ $selectedCategory == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                    {{-- FIX: Menambahkan class flex-grow agar chart mengisi sisa ruang vertikal --}}
                    <div class="p-6 flex-grow">
                        @if (!empty($eventTrendStats['datasets']))
                            <div class="chart-container h-full">
                                <canvas id="eventTrendChart"></canvas>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-500 text-lg">Belum ada data event untuk kategori ini</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Category Performance Chart --}}
                {{-- FIX: Menambahkan class h-[550px] untuk memaksa tinggi card dan flex flex-col agar konten di dalamnya bisa diatur --}}
                <div
                    class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-fade-in flex flex-col h-[550px]">
                    <div class="p-6 border-b border-gray-100">
                        <div
                            class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Kategori Terpopuler</h3>
                                <p class="text-gray-500 mt-1">Event dengan kehadiran tertinggi berdasarkan kategori</p>
                            </div>
                            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex space-x-3">
                                <select name="category_period" onchange="this.form.submit()"
                                    class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                    <option value="this_week" {{ $categoryPeriod == 'this_week' ? 'selected' : '' }}>
                                        Minggu Ini</option>
                                    <option value="this_month"
                                        {{ $categoryPeriod == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="last_6_months"
                                        {{ $categoryPeriod == 'last_6_months' ? 'selected' : '' }}>6 Bulan Terakhir
                                    </option>
                                    <option value="this_year" {{ $categoryPeriod == 'this_year' ? 'selected' : '' }}>
                                        Tahun Ini</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    {{-- FIX: Menambahkan class flex-grow agar chart mengisi sisa ruang vertikal --}}
                    <div class="p-6 flex-grow">
                        @if ($categoryStats->isNotEmpty())
                            <div class="chart-container h-full">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <p class="text-gray-500 text-lg">Belum ada data kategori untuk periode ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Side - Quick Info --}}
            <div class="space-y-6">
                {{-- Event Status Distribution --}}
                <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-fade-in">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">Status Event</h3>
                        <p class="text-gray-500 text-sm mt-1">Distribusi status event saat ini</p>
                    </div>
                    <div class="p-6">
                        <div class="chart-container">
                            <canvas id="eventStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-fade-in">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">Statistik Cepat</h3>
                        <p class="text-gray-500 text-sm mt-1">Informasi penting sistem</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div
                            class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-blue-800">Event Bulan Ini</span>
                            </div>
                            <span class="text-xl font-bold text-blue-600">{{ $eventsThisMonth }}</span>
                        </div>

                        <div
                            class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-500 rounded-lg mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-green-800">Kehadiran Bulan Ini</span>
                            </div>
                            <span class="text-xl font-bold text-green-600">{{ $attendancesThisMonth }}</span>
                        </div>

                        <div
                            class="flex items-center justify-between p-3 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-500 rounded-lg mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-yellow-800">Total Kategori</span>
                            </div>
                            <span class="text-xl font-bold text-yellow-600">{{ $totalCategories }}</span>
                        </div>

                        <div
                            class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-500 rounded-lg mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-purple-800">Event Terjadwal</span>
                            </div>
                            <span class="text-xl font-bold text-purple-600">{{ $upcomingEvents }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Section --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            {{-- Upcoming Events --}}
            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-fade-in">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Event Akan Datang</h3>
                            <p class="text-gray-500 text-sm mt-1">7 hari ke depan</p>
                        </div>
                        <a href="{{ route('admin.events.index') }}"
                            class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">
                            Lihat Semua →
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @forelse($upcomingEventsDetailed as $event)
                        <a href="{{ route('admin.events.show', $event) }}"
                            class="block p-4 hover:bg-yellow-50 transition-colors duration-200">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-yellow-100 rounded-lg flex-shrink-0">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate">{{ $event->title }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center space-x-2">
                                            @if ($event->category)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $event->category->name }}
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500">
                                                {{ $event->participants_count }} peserta
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-yellow-600 font-medium">
                                            {{ $event->start_time->format('d M Y, H:i') }} WIB
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $event->start_time->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 text-sm">Tidak ada event dalam 7 hari ke depan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Top Performing Events --}}
            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-fade-in">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Event Terpopuler</h3>
                            <p class="text-gray-500 text-sm mt-1">30 hari terakhir</p>
                        </div>
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @forelse($topPerformingEvents as $index => $event)
                        <a href="{{ route('admin.events.show', $event) }}"
                            class="block p-4 hover:bg-yellow-50 transition-colors duration-200">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">#{{ $index + 1 }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate">{{ $event->title }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm text-green-600 font-semibold">
                                                {{ $event->attendances_count }} hadir
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                dari {{ $event->participants_count }} undangan
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            @php
                                                $rate =
                                                    $event->participants_count > 0
                                                        ? round(
                                                            ($event->attendances_count / $event->participants_count) *
                                                                100,
                                                        )
                                                        : 0;
                                            @endphp
                                            <span
                                                class="text-lg font-bold text-yellow-600">{{ $rate }}%</span>
                                        </div>
                                    </div>
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 h-1.5 rounded-full"
                                            style="width: {{ $rate }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $event->start_time->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="text-gray-500 text-sm">Belum ada data event</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Event Trend Chart (Line)
                @if (!empty($eventTrendStats['datasets']))
                    console.log('Initializing eventTrendChart', @json($eventTrendStats));
                    const eventTrendCtx = document.getElementById('eventTrendChart').getContext('2d');
                    new Chart(eventTrendCtx, {
                        type: 'line',
                        data: {
                            labels: @json($eventTrendStats['labels']),
                            datasets: @json($eventTrendStats['datasets']).map((ds, index) => {
                                // Define colors for each category
                                const colors = [{
                                        border: '#f59e0b',
                                        background: 'rgba(245, 158, 11, 0.2)'
                                    }, // Mineral (amber)
                                    {
                                        border: '#10b981',
                                        background: 'rgba(16, 185, 129, 0.2)'
                                    }, // Batu Bara (emerald)
                                    {
                                        border: '#3b82f6',
                                        background: 'rgba(59, 130, 246, 0.2)'
                                    }, // Panas Bumi (blue)
                                    {
                                        border: '#ef4444',
                                        background: 'rgba(239, 68, 68, 0.2)'
                                    }, // Sarana Teknik (red)
                                    {
                                        border: '#8b5cf6',
                                        background: 'rgba(139, 92, 246, 0.2)'
                                    } // Umum (purple)
                                ];
                                const colorIndex = index % colors.length;

                                return {
                                    label: ds.label,
                                    data: ds.data,
                                    borderColor: colors[colorIndex].border,
                                    backgroundColor: colors[colorIndex].background,
                                    borderWidth: 3,
                                    tension: 0.4,
                                    pointRadius: 5,
                                    pointHoverRadius: 8,
                                    fill: true
                                };
                            })
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            elements: {
                                line: {
                                    fill: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: {
                                            size: 14
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#ffffff',
                                    bodyColor: '#ffffff',
                                    borderColor: '#f59e0b',
                                    borderWidth: 1,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 14
                                    },
                                    bodyFont: {
                                        size: 12
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                        lineWidth: 1
                                    },
                                    ticks: {
                                        color: '#374151',
                                        font: {
                                            size: 12
                                        },
                                        padding: 10,
                                        stepSize: 1
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#374151',
                                        font: {
                                            size: 12
                                        },
                                        maxRotation: 45,
                                        minRotation: 45
                                    }
                                }
                            }
                        }
                    });
                @endif

                // Category Chart (Horizontal Bar)
                @if ($categoryStats->isNotEmpty())
                    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
                    new Chart(categoryCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($categoryStats->pluck('category_name')),
                            datasets: [{
                                label: 'Total Kehadiran',
                                data: @json($categoryStats->pluck('total_attendances')),
                                backgroundColor: [
                                    '#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6',
                                    '#f97316', '#06b6d4', '#84cc16'
                                ].slice(0, {{ $categoryStats->count() }}),
                                borderColor: [
                                    '#d97706', '#059669', '#2563eb', '#dc2626', '#7c3aed',
                                    '#ea580c', '#0891b2', '#65a30d'
                                ].slice(0, {{ $categoryStats->count() }}),
                                borderWidth: 2,
                                borderRadius: 8,
                                borderSkipped: false
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#ffffff',
                                    bodyColor: '#ffffff',
                                    borderColor: '#f59e0b',
                                    borderWidth: 1,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 14
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    callbacks: {
                                        afterLabel: function(context) {
                                            const categoryData = @json($categoryStats);
                                            const category = categoryData[context.dataIndex];
                                            return `${category.total_events} event${category.total_events !== 1 ? 's' : ''}`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                        lineWidth: 1
                                    },
                                    ticks: {
                                        color: '#374151',
                                        font: {
                                            size: 12
                                        },
                                        padding: 10
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#374151',
                                        font: {
                                            size: 12
                                        },
                                        padding: 10
                                    }
                                }
                            }
                        }
                    });
                @endif

                // Event Status Chart (Doughnut)
                const eventStatusCtx = document.getElementById('eventStatusChart').getContext('2d');
                new Chart(eventStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Terjadwal', 'Berlangsung', 'Selesai', 'Dibatalkan'],
                        datasets: [{
                            data: [
                                {{ $eventStatusData['Terjadwal'] }},
                                {{ $eventStatusData['Berlangsung'] }},
                                {{ $eventStatusData['Selesai'] }},
                                {{ $eventStatusData['Dibatalkan'] }}
                            ],
                            backgroundColor: [
                                '#f59e0b', // yellow-500 - Terjadwal
                                '#10b981', // emerald-500 - Berlangsung
                                '#6b7280', // gray-500 - Selesai
                                '#ef4444' // red-500 - Dibatalkan
                            ],
                            borderColor: '#ffffff',
                            borderWidth: 2,
                            hoverBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#f59e0b',
                                borderWidth: 1,
                                cornerRadius: 8,
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 12
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
