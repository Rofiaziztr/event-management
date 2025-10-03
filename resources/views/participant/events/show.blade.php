<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">{{ $event->title }}</h2>
                <p class="text-gray-600 mt-1">Dibuat oleh {{ $event->creator->full_name }}</p>
                @if ($event->category)
                    <p class="text-sm text-yellow-600 font-medium">{{ $event->category->name }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $dynamicStatus = $event->status;
                @endphp
                @if ($dynamicStatus == 'Terjadwal')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                        <div class="w-2 h-2 bg-cyan-400 rounded-full mr-2"></div>{{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Berlangsung')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></div>{{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Selesai')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>{{ $dynamicStatus }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>{{ $dynamicStatus }}
                    </span>
                @endif

                {{-- Status Kehadiran --}}

            </div>
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

            .animate-fade-in {
                animation: fadeIn 0.6s ease-out;
            }
        </style>
    @endpush

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Alert Reminder Presensi - Fixed Position --}}
            @if ($dynamicStatus === 'Berlangsung' && !$attendance)
                <div class="fixed top-24 right-4 z-40 max-w-xs" id="presensi-alert">
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 rounded-r-lg p-3 shadow-lg animate-fade-in">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-4 h-4 text-yellow-600 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-2 flex-1">
                                <p class="text-yellow-800 font-medium text-sm">Event aktif</p>
                                <p class="text-yellow-700 text-xs">Presensi belum dilakukan</p>
                                <a href="{{ route('scan.index') }}"
                                    class="mt-2 inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                    Presensi Sekarang
                                </a>
                            </div>
                            <button onclick="dismissAlert('presensi-alert')"
                                class="ml-2 text-yellow-600 hover:text-yellow-800 flex-shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Konten Utama: Detail, Notulensi, dan Lampiran --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in">
                {{-- Kolom Kiri (Main) --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Deskripsi Acara --}}
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Deskripsi Acara</h3>
                        </div>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            @if ($event->description)
                                <p class="whitespace-pre-wrap">{{ $event->description }}</p>
                            @else
                                <p class="text-gray-400 italic">Belum ada deskripsi untuk acara ini.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Notulensi --}}
                    @php $notulensi = $event->documents->whereNull('file_path')->first(); @endphp
                    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-xl border border-yellow-200">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-violet-100 rounded-lg">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Notulensi Acara</h3>
                        </div>
                        <div class="prose max-w-none text-gray-700 leading-relaxed overflow-x-auto">
                            @if ($notulensi && $notulensi->content)
                                {!! $notulensi->content !!}
                            @else
                                <p class="text-gray-400 italic">Notulensi belum tersedia untuk acara ini.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan (Sidebar) --}}
                <div class="space-y-6">
                    {{-- Info Event --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Informasi Acara</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <div class="flex items-center mb-2"><svg class="w-4 h-4 text-gray-400 mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg><span class="text-sm font-medium text-gray-500">Lokasi</span></div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->location }}</p>
                            </div>
                            <hr class="border-gray-200">
                            <div>
                                <div class="flex items-center mb-2"><svg class="w-4 h-4 text-gray-400 mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg><span class="text-sm font-medium text-gray-500">Dibuat oleh</span></div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->creator->full_name }}
                                </p>
                            </div>
                            <hr class="border-gray-200">
                            <div>
                                <div class="flex items-center mb-2"><svg class="w-4 h-4 text-gray-400 mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg><span class="text-sm font-medium text-gray-500">Waktu</span></div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">
                                    {{ $event->start_time->format('d M Y, H:i') }} -
                                    {{ $event->end_time->format('H:i') }} WIB</p>
                            </div>
                            @if ($attendance)
                                <hr class="border-gray-200">
                                <div>
                                    <div class="flex items-center mb-2"><svg class="w-4 h-4 text-green-500 mr-2"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg><span class="text-sm font-medium text-gray-500">Status Kehadiran</span>
                                    </div>
                                    <p class="text-sm font-semibold text-green-700 pl-6">
                                        ✓ Sudah check-in pada
                                        {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('d M Y, H:i') }}
                                        WIB
                                    </p>
                                </div>
                            @elseif($dynamicStatus === 'Selesai')
                                <hr class="border-gray-200">
                                <div>
                                    <div class="flex items-center mb-2"><svg class="w-4 h-4 text-red-500 mr-2"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg><span class="text-sm font-medium text-gray-500">Status Kehadiran</span>
                                    </div>
                                    <p class="text-sm font-semibold text-red-700 pl-6">
                                        ✗ Tidak hadir
                                    </p>
                                </div>
                            @elseif($dynamicStatus === 'Terjadwal')
                                <hr class="border-gray-200">
                                <div>
                                    <div class="flex items-center mb-2"><svg class="w-4 h-4 text-blue-500 mr-2"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg><span class="text-sm font-medium text-gray-500">Status Kehadiran</span>
                                    </div>
                                    <p class="text-sm font-semibold text-blue-700 pl-6">
                                        Belum dimulai
                                    </p>
                                </div>
                            @elseif($dynamicStatus === 'Berlangsung')
                                <hr class="border-gray-200">
                                <div>
                                    <div class="flex items-center mb-2"><svg class="w-4 h-4 text-orange-500 mr-2"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg><span class="text-sm font-medium text-gray-500">Status Kehadiran</span>
                                    </div>
                                    <p class="text-sm font-semibold text-orange-700 pl-6">
                                        Belum check-in
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Lampiran --}}
                    @php $documents = $event->documents->whereNotNull('file_path'); @endphp
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Lampiran</h3>
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $documents->count() }}
                                File</span>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($documents as $document)
                                <div class="p-3 hover:bg-yellow-50/50 flex items-center justify-between">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 mr-3">
                                            <div
                                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-200 text-gray-500 text-xs font-semibold">
                                                {{ strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) }}
                                            </div>
                                        </div>
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $document->title }}
                                        </p>
                                    </div>

                                    <div class="flex items-center space-x-1 ml-2 flex-shrink-0">
                                        {{-- Tombol Lihat --}}
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                            class="p-2 text-gray-400 hover:bg-blue-100 hover:text-blue-600 rounded-full transition-colors duration-150"
                                            title="Lihat File">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        {{-- Tombol Download --}}
                                        <a href="{{ Storage::url($document->file_path) }}" download
                                            class="p-2 text-gray-400 hover:bg-green-100 hover:text-green-600 rounded-full transition-colors duration-150"
                                            title="Unduh File">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="p-4 text-sm text-gray-500 text-center">Tidak ada lampiran.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Kembali --}}
            <div class="mt-8 flex justify-end">
                <a href="{{ route('participant.events.index') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 border border-gray-300 rounded-xl font-medium text-white shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar Acara
                </a>
            </div>
        </div>
    </div>

    <script>
        // Function to dismiss alert and save to localStorage
        function dismissAlert(alertId) {
            const alertElement = document.getElementById(alertId);
            if (alertElement) {
                alertElement.style.display = 'none';
                // Save dismissed state to localStorage with event ID
                const eventId = {{ $event->id }};
                const dismissedAlerts = JSON.parse(localStorage.getItem('dismissedAlerts') || '{}');
                dismissedAlerts['presensi_' + eventId] = Date.now(); // Save timestamp
                localStorage.setItem('dismissedAlerts', JSON.stringify(dismissedAlerts));
            }
        }

        // Check if alert was previously dismissed (within last 30 minutes)
        document.addEventListener('DOMContentLoaded', function() {
            const eventId = {{ $event->id }};
            const dismissedAlerts = JSON.parse(localStorage.getItem('dismissedAlerts') || '{}');
            const alertKey = 'presensi_' + eventId;

            if (dismissedAlerts[alertKey]) {
                // Check if dismissed more than 30 minutes ago
                const dismissedTime = dismissedAlerts[alertKey];
                const currentTime = Date.now();
                const minutesDiff = (currentTime - dismissedTime) / (1000 * 60);

                // If dismissed more than 30 minutes ago, show alert again
                if (minutesDiff > 30) {
                    delete dismissedAlerts[alertKey];
                    localStorage.setItem('dismissedAlerts', JSON.stringify(dismissedAlerts));
                } else {
                    // Still within 30 minutes, hide alert
                    const alertElement = document.getElementById('presensi-alert');
                    if (alertElement) {
                        alertElement.style.display = 'none';
                    }
                }
            }
        });
    </script>
</x-app-layout>
