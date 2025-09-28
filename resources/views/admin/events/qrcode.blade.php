<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="bg-yellow-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m6-5h-6m1-11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zm-9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">QR Code Presensi</h2>
                    <p class="text-gray-600 mt-1">{{ $event->title }}</p>
                    @if ($event->category)
                        <p class="text-sm text-yellow-600 font-medium">{{ $event->category->name }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $dynamicStatus = $event->status; // Gunakan accessor dinamis
                @endphp
                @if ($dynamicStatus === 'Terjadwal')
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus === 'Berlangsung')
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                        {{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus === 'Selesai')
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 5px rgba(245, 158, 11, 0.3); }
            50% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.6); }
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        .animate-slide-up {
            animation: slideUp 0.3s ease-out;
        }
        .qr-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }
    </style>
    @endpush

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-in">
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Undangan</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $event->participants->count() }}</p>
                            <p class="text-xs text-blue-600 mt-1">peserta diundang</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Sudah Hadir</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $event->attendances->count() ?? 0 }}</p>
                            <p class="text-xs text-green-600 mt-1">
                                {{ $event->participants->count() > 0 ? round(($event->attendances->count() / $event->participants->count()) * 100) : 0 }}% tingkat hadir
                            </p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Belum Hadir</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $event->participants->count() - ($event->attendances->count() ?? 0) }}</p>
                            <p class="text-xs text-orange-600 mt-1">menunggu presensi</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status QR</p>
                            <p class="text-lg font-bold text-gray-900">
                                @if($event->isActiveForAttendance())
                                    Aktif
                                @else
                                    Nonaktif
                                @endif
                            </p>
                            <p class="text-xs {{ $event->isActiveForAttendance() ? 'text-green-600' : 'text-red-600' }} mt-1">
                                @if($event->isActiveForAttendance())
                                    siap digunakan
                                @else
                                    tidak dapat digunakan
                                @endif
                            </p>
                        </div>
                        <div class="p-4 bg-gradient-to-br {{ $event->isActiveForAttendance() ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600' }} rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m6-5h-6m1-11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zm-9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                {{-- Event Information Sidebar --}}
                <div class="xl:col-span-1 space-y-6">
                    {{-- Event Details Card --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-slide-up">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-6 rounded-t-2xl">
                            <h3 class="text-xl font-bold text-white">Informasi Event</h3>
                            <p class="text-yellow-100 text-sm mt-1">Detail lengkap acara</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="p-3 bg-blue-100 rounded-xl flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-500">Tanggal & Waktu</p>
                                    <p class="font-bold text-gray-900 mt-1">
                                        {{ $event->start_time->format('d F Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }} WIB
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="p-3 bg-green-100 rounded-xl flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-500">Lokasi</p>
                                    <p class="font-bold text-gray-900 mt-1">{{ $event->location }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="p-3 bg-purple-100 rounded-xl flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-500">Dibuat oleh</p>
                                    <p class="font-bold text-gray-900 mt-1">{{ $event->creator->full_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Instructions Card --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-slide-up">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-3 bg-amber-100 rounded-xl">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Cara Menggunakan</h3>
                                    <p class="text-sm text-gray-500">Panduan QR Code presensi</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">1</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Tampilkan QR Code</p>
                                        <p class="text-xs text-gray-500 mt-1">Tunjukkan kepada peserta acara</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">2</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Peserta Scan</p>
                                        <p class="text-xs text-gray-500 mt-1">Menggunakan menu Scan Presensi</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">3</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Otomatis Tercatat</p>
                                        <p class="text-xs text-gray-500 mt-1">Sistem mencatat kehadiran</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">4</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Monitor Real-time</p>
                                        <p class="text-xs text-gray-500 mt-1">Lihat kehadiran di tab Peserta</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- QR Code Main Section --}}
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-fade-in">
                        <!-- Enhanced Header -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-t-2xl text-center">
                            <div class="flex items-center justify-center space-x-3 mb-3">
                                <div class="p-2 bg-white bg-opacity-20 rounded-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m6-5h-6m1-11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zm-9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-white">QR Code Presensi</h3>
                            </div>
                            <p class="text-green-100 text-lg">
                                @if ($event->isActiveForAttendance())
                                    Pindai kode ini untuk melakukan presensi
                                @elseif ($event->status === 'Terjadwal')
                                    QR Code akan aktif saat event dimulai
                                @else
                                    QR Code tidak aktif untuk presensi baru
                                @endif
                            </p>
                        </div>

                        <div class="p-8">
                            <!-- Event Code Display -->
                            <div class="text-center mb-8">
                                <p class="text-sm font-medium text-gray-500 mb-3">Kode Event</p>
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 inline-block border border-gray-200">
                                    <p class="text-3xl font-mono font-bold text-gray-900 tracking-wider">
                                        {{ $event->code ?? 'EVT-' . $event->id }}
                                    </p>
                                </div>
                            </div>

                            <!-- QR Code Container -->
                            <div class="flex justify-center mb-8">
                                @if ($event->isActiveForAttendance())
                                    <div class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-xl border-4 border-yellow-200 qr-glow">
                                        <div class="bg-white p-6 rounded-xl shadow-inner">
                                            <img src="{{ $qrCodeDataUri }}" alt="QR Code for {{ $event->title }}" 
                                                 class="mx-auto" style="max-width: 320px; height: auto;">
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-8 rounded-2xl shadow-xl border-4 border-gray-200">
                                        <div class="text-center text-gray-500">
                                            <svg class="w-32 h-32 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            <p class="text-lg font-medium">
                                                @if ($event->status === 'Terjadwal')
                                                    QR Code akan tersedia saat event dimulai
                                                @else
                                                    QR Code tidak tersedia
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mb-8">
                                @if ($event->isActiveForAttendance())
                                    <button onclick="printQRCode()" 
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl font-semibold text-white hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-lg transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        Print QR Code
                                    </button>

                                    <button onclick="downloadQRCode()" 
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl font-semibold text-white hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 shadow-lg transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download QR
                                    </button>

                                    <button onclick="toggleFullscreen()" 
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl font-semibold text-white hover:from-purple-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-lg transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                        </svg>
                                        Fullscreen
                                    </button>
                                @else
                                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600 italic font-medium">
                                            @if ($event->status === 'Terjadwal')
                                                Fitur QR Code akan aktif saat event dimulai
                                            @else
                                                Fitur QR Code dinonaktifkan untuk event selesai
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Live Status Indicator -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 mb-8 border border-gray-200">
                                <div class="flex items-center justify-center">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 @if($event->isActiveForAttendance()) bg-green-500 @else bg-gray-400 @endif rounded-full animate-pulse"></div>
                                        <span class="font-medium text-gray-700">
                                            @if($event->isActiveForAttendance())
                                                QR Code aktif dan siap digunakan
                                            @elseif($event->status === 'Terjadwal')
                                                QR Code akan aktif pada {{ $event->start_time->format('d M Y, H:i') }} WIB
                                            @else
                                                QR Code tidak aktif
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 text-center mt-2">
                                    Terakhir diperbarui: <span id="last-updated">{{ now()->format('H:i:s') }}</span>
                                </p>
                            </div>

                            <!-- Warning Message for Finished Events -->
                            @if($event->status === 'Selesai')
                                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-6 mb-8">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-red-100 rounded-lg mr-4">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-red-800 text-lg">Event Telah Selesai</h4>
                                            <p class="text-red-700 mt-1">QR Code tidak dapat digunakan untuk presensi baru. Data kehadiran masih dapat dilihat untuk keperluan laporan.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Description Text -->
                            <div class="text-center">
                                <p class="text-lg text-gray-700 leading-relaxed">
                                    @if ($event->isActiveForAttendance())
                                        <span class="font-semibold text-green-600">Tampilkan kode ini kepada peserta acara</span><br>
                                        untuk melakukan presensi dengan mudah dan cepat
                                    @elseif ($event->status === 'Terjadwal')
                                        <span class="font-semibold text-blue-600">Kode ini akan aktif untuk presensi</span><br>
                                        saat event dimulai sesuai jadwal
                                    @else
                                        <span class="font-semibold text-gray-600">Kode ini hanya untuk referensi</span><br>
                                        presensi tidak lagi dapat dilakukan
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="flex justify-between items-center animate-fade-in">
                <a href="{{ route('admin.events.show', $event) }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl font-medium text-white shadow-sm hover:from-gray-600 hover:to-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Detail Event
                </a>

                <!-- Quick Actions -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'peserta']) }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-sm font-medium text-white hover:from-blue-600 hover:to-blue-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        Lihat Peserta
                    </a>

                    <a href="{{ route('admin.events.export', $event) }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-sm font-medium text-white hover:from-green-600 hover:to-green-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Fullscreen Modal --}}
    <div id="fullscreen-modal" class="fixed inset-0 bg-black bg-opacity-95 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-8">
            <div class="text-center max-w-2xl w-full">
                <div class="bg-white p-8 rounded-3xl shadow-2xl inline-block">
                    <div class="mb-6">
                        <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600">{{ $event->start_time->format('d F Y, H:i') }} WIB</p>
                        <p class="text-sm text-gray-500">{{ $event->location }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-blue-50 p-8 rounded-2xl mb-6">
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code for {{ $event->title }}" 
                             class="mx-auto" style="max-width: 400px; height: auto;">
                    </div>
                    
                    <div class="mb-6">
                        <p class="text-lg font-mono font-bold text-gray-800 mb-2">{{ $event->code ?? 'EVT-' . $event->id }}</p>
                        <p class="text-sm text-gray-600">Kode Event untuk Presensi</p>
                    </div>
                    
                    <button onclick="toggleFullscreen()" 
                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-8 rounded-xl transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tutup Fullscreen
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function printQRCode() {
            const qrImage = document.querySelector('img[src="{{ $qrCodeDataUri }}"]');
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>QR Code - {{ $event->title }}</title>
                    <style>
                        body { 
                            font-family: 'Segoe UI', Arial, sans-serif; 
                            text-align: center; 
                            padding: 40px; 
                            background: #f8fafc;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            background: white;
                            padding: 40px;
                            border-radius: 20px;
                            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                        }
                        .header { 
                            margin-bottom: 30px; 
                            border-bottom: 2px solid #f59e0b;
                            padding-bottom: 20px;
                        }
                        .header h1 {
                            color: #1f2937;
                            font-size: 28px;
                            margin-bottom: 10px;
                        }
                        .header h2 {
                            color: #374151;
                            font-size: 20px;
                            margin-bottom: 15px;
                        }
                        .event-details {
                            color: #6b7280;
                            font-size: 16px;
                            line-height: 1.6;
                        }
                        .qr-container { 
                            margin: 30px 0; 
                            padding: 20px;
                            background: linear-gradient(135deg, #f0fdf4 0%, #dbeafe 100%);
                            border-radius: 15px;
                        }
                        .event-code { 
                            font-size: 24px; 
                            font-weight: bold; 
                            font-family: monospace; 
                            margin: 20px 0;
                            color: #1f2937;
                            background: #f3f4f6;
                            padding: 15px;
                            border-radius: 10px;
                            border: 2px solid #e5e7eb;
                        }
                        .instructions { 
                            margin-top: 30px; 
                            font-size: 14px; 
                            color: #6b7280; 
                            background: #fffbeb;
                            padding: 20px;
                            border-radius: 10px;
                            border-left: 4px solid #f59e0b;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>QR Code Presensi</h1>
                            <h2>{{ $event->title }}</h2>
                            <div class="event-details">
                                <p><strong>Tanggal:</strong> {{ $event->start_time->format('d F Y, H:i') }} WIB</p>
                                <p><strong>Lokasi:</strong> {{ $event->location }}</p>
                            </div>
                        </div>
                        <div class="qr-container">
                            <img src="{{ $qrCodeDataUri }}" alt="QR Code" style="max-width: 300px;">
                        </div>
                        <div class="event-code">
                            Kode Event: {{ $event->code ?? 'EVT-' . $event->id }}
                        </div>
                        <div class="instructions">
                            <p><strong>Cara Penggunaan:</strong></p>
                            <p>1. Tampilkan QR Code ini kepada peserta</p>
                            <p>2. Peserta scan menggunakan aplikasi atau menu Scan Presensi</p>
                            <p>3. Sistem akan mencatat kehadiran secara otomatis</p>
                        </div>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        function downloadQRCode() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();
            
            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                
                const link = document.createElement('a');
                link.download = 'qr-code-{{ Str::slug($event->title) }}-{{ $event->start_time->format("Y-m-d") }}.png';
                link.href = canvas.toDataURL();
                link.click();
            };
            
            img.src = '{{ $qrCodeDataUri }}';
        }

        function toggleFullscreen() {
            const modal = document.getElementById('fullscreen-modal');
            modal.classList.toggle('hidden');
        }

        // Update timestamp every second
        setInterval(() => {
            document.getElementById('last-updated').textContent = new Date().toLocaleTimeString('id-ID');
        }, 1000);

        // Close fullscreen modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('fullscreen-modal').classList.add('hidden');
            }
        });

        // Animation observer for scroll animations
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            const animatedElements = document.querySelectorAll('.animate-slide-up');
            animatedElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.6s ease-out';
                observer.observe(el);
            });
        });
    </script>
    @endpush
</x-app-layout>