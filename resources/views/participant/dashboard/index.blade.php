<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Peserta') }}
                </h2>
                <p class="text-gray-600 mt-1">Selamat datang, {{ auth()->user()->full_name }}</p>
                <p class="text-sm text-yellow-600 font-medium flex items-center">
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

    <div class="w-full py-6 space-y-8">

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

        <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 w-full">
            <div class="w-full space-y-8 py-8">

                {{-- Statistics Cards --}}
                <div class="px-4 sm:px-6 lg:px-8">
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
                </div>

                {{-- Google Calendar Integration --}}
                <div class="px-4 sm:px-6 lg:px-8 space-y-6">
                    {{-- Google Calendar Integration - Main Card --}}
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-xl border border-yellow-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                            {{-- Left: Title & Description --}}
                            <div class="flex-1">
                                <div class="flex items-start space-x-4">
                                    <div class="p-3 bg-blue-100 rounded-lg flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-2xl font-bold text-gray-900">Google Calendar</h3>
                                        <p class="text-gray-600 mt-1">Otomatis simpan event Anda ke Google Calendar dan
                                            terima notifikasi reminder</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Right: Status Badge --}}
                            <div id="google-calendar-status" class="flex flex-col items-center lg:items-end gap-3">
                                {{-- Loading state initially --}}
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span class="text-sm font-medium">Memeriksa status...</span>
                                </div>
                            </div>
                        </div>


                        {{-- Panduan Pengguna - Collapsible --}}
                        <div x-data="{ guideOpen: false }" class="mt-3 pt-3 ">
                            <button @click="guideOpen = !guideOpen"
                                class="w-full flex items-center justify-between px-4 py-3 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-semibold text-gray-900">Panduan Pengguna Selengkapnya</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-600 transition-transform"
                                    :class="{ 'transform rotate-180': guideOpen }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </button>

                            {{-- Expandable Content --}}
                            <div x-show="guideOpen" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="mt-4 space-y-4">

                                {{-- Step 1: Connect --}}
                                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            1</div>
                                        <h4 class="ml-3 font-semibold text-gray-900">Hubungkan Google Calendar</h4>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-3 ml-11">Klik tombol di atas untuk menghubungkan
                                        akun Google Anda</p>
                                    <ol class="text-sm text-gray-700 space-y-2 list-decimal list-inside ml-11">
                                        <li>Pilih akun Google Anda</li>
                                        <li>Berikan izin akses ke Calendar</li>
                                        <li>Selesai! Event akan tersimpan otomatis</li>
                                    </ol>
                                </div>

                                {{-- Step 2: Sync --}}
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            2</div>
                                        <h4 class="ml-3 font-semibold text-gray-900">Sinkronkan Event Anda</h4>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-3 ml-11">Setelah terhubung, sinkronkan event
                                        yang sudah berlangsung</p>
                                    <div class="ml-11 bg-white border border-blue-100 rounded p-3">
                                        <p class="text-sm text-gray-700 font-semibold mb-2">Klik "Sync Ulang" untuk:
                                        </p>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li>✓ Update event terbaru</li>
                                            <li>✓ Ambil event yang sudah lalu</li>
                                            <li>✓ Refresh data kalender</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Step 3: Disconnect --}}
                                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            3</div>
                                        <h4 class="ml-3 font-semibold text-gray-900">Putuskan Koneksi (Opsional)</h4>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-3 ml-11">Jika ingin memutuskan akses Google
                                        Calendar:</p>
                                    <ol class="text-sm text-gray-700 space-y-2 list-decimal list-inside ml-11">
                                        <li>Buka <a href="https://myaccount.google.com/permissions" target="_blank"
                                                class="text-blue-600 hover:underline font-semibold">myaccount.google.com</a>
                                        </li>
                                        <li>Pilih menu "Keamanan"</li>
                                        <li>Cari "Aplikasi pihak ketiga" atau "Third-party apps"</li>
                                        <li>Cari dan pilih "Event Management"</li>
                                        <li>Klik "Hapus akses" atau "Remove access"</li>
                                    </ol>
                                </div>

                                {{-- Tips Section --}}
                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Tips & Trik
                                    </h4>
                                    <ul class="text-sm text-gray-700 space-y-2">
                                        <li><strong>💡 Sync Otomatis:</strong> Tidak perlu sync manual setiap kali,
                                            event akan tersimpan otomatis saat Anda hadir</li>
                                        <li><strong>🔔 Reminder:</strong> Aktifkan notifikasi di Google Calendar untuk
                                            mendapat pengingat sebelum event dimulai</li>
                                        <li><strong>📱 Multiple Device:</strong> Event akan tersinkronisasi di semua
                                            perangkat Anda yang terhubung dengan Google Account</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> {{-- Upcoming Events List --}}
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-2xl font-bold text-gray-900">Event Akan Datang</h3>
                            <p class="text-gray-500 mt-1">Jadwal event yang akan segera dimulai. Pastikan untuk hadir
                                dan
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
                                                <svg class="w-6 h-6 text-yellow-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
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
                </div>

                {{-- Riwayat Event Anda --}}
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                        <div class="p-6 border-b border-gray-100">
                            <div
                                class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Riwayat Event Anda
                                        ({{ $totalHistoryEvents }})</h3>
                                    <p class="text-gray-500 mt-1">Riwayat event yang telah berlangsung dengan status
                                        kehadiran Anda. Gunakan pencarian untuk menemukan event spesifik berdasarkan
                                        judul,
                                        lokasi, atau kategori.</p>
                                </div>
                                {{-- Mobile-friendly search form --}}
                                <form method="GET" action="{{ route('participant.dashboard') }}"
                                    class="w-full lg:w-auto space-y-2 lg:space-y-0 lg:flex lg:gap-2">
                                    <input type="text" name="search" value="{{ $search }}"
                                        placeholder="Cari title, lokasi, kategori..."
                                        class="w-full lg:w-64 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 placeholder-gray-400" />
                                    <select name="history_period"
                                        class="w-full lg:w-auto bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        <option value="all" {{ $historyPeriod == 'all' ? 'selected' : '' }}>Semua
                                        </option>
                                        <option value="last_year"
                                            {{ $historyPeriod == 'last_year' ? 'selected' : '' }}>1 Tahun
                                        </option>
                                        <option value="last_6_months"
                                            {{ $historyPeriod == 'last_6_months' ? 'selected' : '' }}>6 Bulan
                                        </option>
                                        <option value="this_year"
                                            {{ $historyPeriod == 'this_year' ? 'selected' : '' }}>Tahun Ini
                                        </option>
                                    </select>
                                    <div class="flex gap-2">
                                        <button type="submit"
                                            class="flex-1 lg:flex-none bg-yellow-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-yellow-600 transition-colors min-h-10 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            Cari
                                        </button>
                                        <a href="{{ route('participant.dashboard') }}"
                                            class="flex-1 lg:flex-none bg-gray-400 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-500 transition-colors min-h-10 flex items-center justify-center">
                                            Reset
                                        </a>
                                    </div>
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
                                                class="p-3 {{ $event->attendances->isNotEmpty() ? 'bg-green-100' : 'bg-red-100' }} rounded-xl">
                                                <svg class="w-6 h-6 {{ $event->attendances->isNotEmpty() ? 'text-green-600' : 'text-red-600' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="{{ $event->attendances->isNotEmpty() ? 'M9 12l2 2 4-4' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2' }}m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-gray-900 text-lg truncate">
                                                    {{ $event->title }}
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
                                                        class="text-sm {{ $event->attendances->isNotEmpty() ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                        {{ $event->attendances->isNotEmpty() ? 'Hadir' : 'Tidak Hadir' }}
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
                                    <p class="text-gray-500">Riwayat akan muncul setelah event yang Anda ikuti telah
                                        berlangsung.</p>
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
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Check Google Calendar status on page load
                    checkGoogleCalendarStatus();

                    // Check for sync status from session flash messages
                    @if (session('success'))
                        // If there's a success message containing sync info, show success status
                        if (document.querySelector('[x-data*="isSyncing"]')) {
                            const successMessage = "{{ session('success') }}";
                            if (successMessage.includes('sinkronkan')) {
                                // Set success status for sync button
                                const syncContainer = document.querySelector('[x-data*="isSyncing"]');
                                if (syncContainer && syncContainer._x_dataStack) {
                                    syncContainer._x_dataStack[0].syncStatus = 'success';
                                    syncContainer._x_dataStack[0].syncMessage = successMessage;
                                }
                            }
                        }
                    @endif

                    @if (session('error'))
                        // If there's an error message related to sync, show error status
                        if (document.querySelector('[x-data*="isSyncing"]')) {
                            const errorMessage = "{{ session('error') }}";
                            if (errorMessage.includes('sinkronkan') || errorMessage.includes('Google Calendar')) {
                                const syncContainer = document.querySelector('[x-data*="isSyncing"]');
                                if (syncContainer && syncContainer._x_dataStack) {
                                    syncContainer._x_dataStack[0].syncStatus = 'error';
                                    syncContainer._x_dataStack[0].syncMessage = errorMessage;
                                }
                            }
                        }
                    @endif
                });

                // Function to check Google Calendar connection status
                async function checkGoogleCalendarStatus() {
                    const statusContainer = document.getElementById('google-calendar-status');
                    if (!statusContainer) return;

                    try {
                        // Make AJAX call to validate access
                        const response = await fetch('{{ route('google-calendar.validate-access') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({})
                        });

                        const data = await response.json();

                        if (data.success) {
                            // User has valid access - show connected state with sync button
                            statusContainer.innerHTML = `
                                <div class="flex items-center space-x-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium">Terhubung</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="https://calendar.google.com/calendar/u/0/r"
                                        target="_blank"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Buka Calendar
                                    </a>
                                    <div x-data="{ isSyncing: false, syncStatus: null, syncMessage: '' }" class="inline">
                                    @php
                                        $hasSyncedEvents = \App\Models\EventCalendarSync::where('user_id', auth()->id())->exists();
                                    @endphp
                                    <form method="POST" action="{{ route('participant.events.sync-calendar') }}"
                                        class="inline" @submit="isSyncing = true; syncStatus = null; syncMessage = ''"
                                        x-bind:disabled="isSyncing">
                                        @csrf
                                        <button type="submit" x-bind:disabled="isSyncing"
                                            class="px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors flex items-center relative"
                                            x-bind:class="{
                                                'bg-green-600 hover:bg-green-700': !isSyncing && syncStatus !== 'error' && syncStatus !== 'success',
                                                'bg-red-600 hover:bg-red-700': syncStatus === 'error' && !isSyncing,
                                                'bg-green-600': syncStatus === 'success' && !isSyncing,
                                                'bg-green-600 opacity-75 cursor-not-allowed': isSyncing
                                            }">
                                            <svg x-show="isSyncing" x-cloak class="w-4 h-4 mr-2 animate-spin"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <svg x-show="syncStatus === 'success' && !isSyncing" x-cloak
                                                class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <svg x-show="syncStatus === 'error' && !isSyncing" x-cloak
                                                class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <svg x-show="!isSyncing && syncStatus !== 'success' && syncStatus !== 'error'"
                                                class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span
                                                x-text="isSyncing ? 'Menyinkronkan...' : (syncStatus === 'success' ? 'Berhasil!' : (syncStatus === 'error' ? 'Gagal' : '{{ $hasSyncedEvents ? 'Sync Ulang' : 'Sync Events' }}'))"></span>
                                        </button>
                                    </form>
                                    <div x-show="syncMessage && !isSyncing" x-text="syncMessage" x-transition
                                        class="absolute bottom-full mb-2 px-3 py-2 bg-gray-800 text-white text-sm rounded-lg shadow-lg z-50 whitespace-nowrap max-w-xs">
                                    </div>
                                </div>
                            `;
                        } else {
                            // User doesn't have valid access - show connect button
                            statusContainer.innerHTML = `
                                <a href="{{ route('google-calendar.auth') }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Hubungkan Google Calendar</span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            `;
                        }
                    } catch (error) {
                        console.error('Error checking Google Calendar status:', error);
                        // On error, show connect button as fallback
                        statusContainer.innerHTML = `
                            <a href="{{ route('google-calendar.auth') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Hubungkan Google Calendar</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        `;
                    }
                }
            </script>
        @endpush
</x-app-layout>
