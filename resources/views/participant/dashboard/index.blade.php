<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Peserta') }}
                </h2>
                <p class="text-gray-600 mt-1">Selamat datang, {{ auth()->user()->full_name }}</p>
                <p class="text-sm text-yellow-600 font-medium flex items-center">
                    <span class="mr-1">🎪</span>
                    Peserta Event
                </p>
            </div>
            @php
                $activeEventsCount = $ongoingEvents
                    ->filter(function ($event) use ($attendedEventIds) {
                        return !in_array($event->id, $attendedEventIds);
                    })
                    ->count();
                $hasActiveEvents = $activeEventsCount > 0;
            @endphp

            @if ($hasActiveEvents)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                @if ($activeEventsCount > 1)
                                    {{ $activeEventsCount }} event aktif - presensi tersedia
                                @else
                                    {{ $ongoingEvents->first()->title }} - presensi tersedia
                                @endif
                            </p>
                            <p class="text-xs text-gray-600">
                                @if ($activeEventsCount > 1)
                                    Berakhir pukul {{ $ongoingEvents->first()->end_time->format('H:i') }} WIB
                                @else
                                    Sampai {{ $ongoingEvents->first()->end_time->format('H:i') }} WIB
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('scan.index') }}"
                            class="bg-yellow-500 text-white px-3 py-1.5 rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors">
                            Presensi
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('scan.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-yellow-500 border border-transparent rounded-xl font-semibold text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all duration-300 ease-in-out transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="5" height="5" rx="1" fill="currentColor"
                            opacity="0.3" />
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
            @endif
        </div>
    </x-slot>

    <div
        class="max-w-full md:max-w-7xl lg:max-w-[90%] xl:max-w-[95%] 2xl:max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

        @push('styles')
            <style>
                .gradient-bg {
                    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
                }

                .glass-effect {
                    backdrop-filter: blur(10px);
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                }

                .animate-pulse-slow {
                    animation: pulse 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                }

                .line-clamp-2 {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }

                .text-shadow-lg {
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
                }

                .animate-border-glow {
                    animation: border-glow 2s ease-in-out infinite;
                }

                @keyframes border-glow {

                    0%,
                    100% {
                        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
                    }

                    50% {
                        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0);
                    }
                }
            </style>
        @endpush

        <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
            <div class="max-w-7xl mt-10 mx-auto px-6 space-y-8 pb-8">

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <div class="stats-icon stats-icon-blue">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 font-medium">Total Undangan</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalInvitations }}</p>
                                <p class="text-xs text-blue-600 mt-1">Event selesai</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
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
                                <p class="text-sm text-gray-500 font-medium">Total Kehadiran</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $attendedCount }}</p>
                                <p class="text-xs text-green-600 mt-1">{{ $attendanceRate }}% tingkat hadir</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <div class="stats-icon stats-icon-orange">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 font-medium">Event Terlewat</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $missedEventsCount }}</p>
                                <p class="text-xs text-red-600 mt-1">
                                    {{ $totalInvitations > 0 ? round(($missedEventsCount / $totalInvitations) * 100) : 0 }}%
                                    dari total</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
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
                                <p class="text-sm text-gray-500 font-medium">Tingkat Kehadiran</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $attendanceRate }}%</p>
                                <p class="text-xs text-yellow-600 mt-1">
                                    @if ($attendanceRate >= 90)
                                        Excellent!
                                    @elseif($attendanceRate >= 80)
                                        Good!
                                    @elseif($attendanceRate >= 70)
                                        Fair
                                    @else
                                        Needs Improvement
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upcoming Events List --}}
                <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900">Event Akan Datang</h3>
                        <p class="text-gray-500 mt-1">Jadwal event yang akan segera dimulai. Pastikan untuk hadir dan
                            tingkatkan tingkat kehadiran Anda!</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse ($upcomingEvents as $event)
                            <a href="{{ route('participant.events.show', $event) }}"
                                class="block p-6 hover:bg-yellow-50 transition-colors duration-200">
                                <div
                                    class="flex flex-col lg:flex-row justify-between lg:items-center space-y-3 lg:space-y-0">
                                    <div class="flex items-start space-x-4">
                                        <div class="p-3 bg-yellow-100 rounded-xl">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-lg">{{ $event->title }}</h4>
                                            <p class="text-gray-500 mt-1">{{ $event->location }}</p>
                                            <div class="flex items-center space-x-4 mt-2">
                                                @if ($event->category)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $event->category->name }}
                                                    </span>
                                                @endif
                                                <span
                                                    class="text-sm text-gray-500">{{ $event->participants->count() }}
                                                    peserta</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-left lg:text-right">
                                        <p class="text-lg font-bold text-yellow-600">
                                            {{ $event->start_time->format('d M Y, H:i') }} WIB
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $event->status }}</p>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-2">
                                            {{ $event->status }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Event Mendatang</h3>
                                <p class="text-gray-500">Tidak ada event yang dijadwalkan dalam waktu dekat.</p>
                            </div>
                        @endforelse
                    </div>
                    @if ($upcomingEvents->isNotEmpty())
                        <div class="p-6 border-t border-gray-100 text-center">
                            <a href="{{ route('participant.events.index') }}"
                                class="text-yellow-600 hover:text-yellow-700 font-semibold transition-colors duration-200">
                                Lihat Semua Event →
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Riwayat Event Anda --}}
                <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                    <div class="p-6 border-b border-gray-100">
                        <div
                            class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Riwayat Event Anda
                                    ({{ $totalHistoryEvents }})</h3>
                                <p class="text-gray-500 mt-1">Event yang pernah Anda ikuti atau diundang, dengan status
                                    kehadiran. Gunakan pencarian untuk menemukan event spesifik berdasarkan judul,
                                    lokasi,
                                    atau kategori.</p>
                            </div>
                            <form method="GET" action="{{ route('participant.dashboard') }}"
                                class="flex space-x-3">
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Cari title, lokasi, kategori..."
                                    class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 w-64" />
                                <select name="history_period"
                                    class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                    <option value="all" {{ $historyPeriod == 'all' ? 'selected' : '' }}>Semua
                                    </option>
                                    <option value="last_year" {{ $historyPeriod == 'last_year' ? 'selected' : '' }}>1
                                        Tahun Terakhir</option>
                                    <option value="last_6_months"
                                        {{ $historyPeriod == 'last_6_months' ? 'selected' : '' }}>6 Bulan Terakhir
                                    </option>
                                    <option value="this_year" {{ $historyPeriod == 'this_year' ? 'selected' : '' }}>
                                        Tahun
                                        Ini</option>
                                </select>
                                <button type="submit"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-xl hover:bg-yellow-600 transition-colors">Cari</button>
                                <a href="{{ route('participant.dashboard') }}"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-xl hover:bg-gray-600 transition-colors">Reset</a>
                            </form>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                        @forelse ($historyEvents as $index => $event)
                            <a href="{{ route('participant.events.show', $event) }}"
                                class="block p-6 hover:bg-yellow-50 transition-colors duration-200">
                                <div
                                    class="flex flex-col lg:flex-row justify-between lg:items-center space-y-3 lg:space-y-0">
                                    <div class="flex items-start space-x-4">
                                        <div
                                            class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full text-gray-700 font-semibold">
                                            {{ $historyEvents->firstItem() + $index }}
                                        </div>
                                        <div
                                            class="p-3 {{ $event->start_time->isFuture() ? 'bg-gray-100' : ($event->attendances->isNotEmpty() ? 'bg-green-100' : 'bg-red-100') }} rounded-xl">
                                            <svg class="w-6 h-6 {{ $event->start_time->isFuture() ? 'text-gray-600' : ($event->attendances->isNotEmpty() ? 'text-green-600' : 'text-red-600') }}"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $event->start_time->isFuture() ? 'M8 7V3m8 4V3m-9 8h10' : ($event->attendances->isNotEmpty() ? 'M9 12l2 2 4-4' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2') }}m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-gray-900 text-lg truncate">{{ $event->title }}
                                            </h4>
                                            <p class="text-gray-500 mt-1 truncate">{{ $event->location }}</p>
                                            <div class="flex items-center space-x-4 mt-2">
                                                @if ($event->category)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $event->category->name }}
                                                    </span>
                                                @endif
                                                <span
                                                    class="text-sm {{ $event->start_time->isFuture() ? 'text-gray-600' : ($event->attendances->isNotEmpty() ? 'text-green-600' : 'text-red-600') }} font-medium">
                                                    {{ $event->start_time->isFuture() ? 'Belum Berlangsung' : ($event->attendances->isNotEmpty() ? 'Hadir' : 'Tidak Hadir') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-left lg:text-right">
                                        <p class="text-lg font-bold text-yellow-600">
                                            {{ $event->start_time->format('d M Y, H:i') }} WIB
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $event->status }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Event</h3>
                                <p class="text-gray-500">Riwayat akan muncul setelah Anda diundang ke event.</p>
                            </div>
                        @endforelse
                    </div>
                    @if ($historyEvents->hasPages())
                        <div class="p-6 border-t border-gray-100">
                            <x-yellow-pagination :paginator="$historyEvents" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // No additional JavaScript needed - Alpine.js handles animations
            </script>
        @endpush
</x-app-layout>
