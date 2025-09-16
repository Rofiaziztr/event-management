<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">QR Code Presensi</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $event->title }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $dynamicStatus = $event->status; // Gunakan accessor dinamis
                @endphp
                @if ($dynamicStatus === 'Terjadwal')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus === 'Berlangsung')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                        {{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus === 'Selesai')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Section - Event Info --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Event Details Card --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Informasi Acara</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500">Tanggal & Waktu</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">
                                    {{ $event->start_time->format('d F Y') }}
                                </p>
                                <p class="text-sm text-gray-600 pl-6">
                                    {{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }} WIB
                                </p>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500">Lokasi</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->location }}</p>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500">Total Peserta</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->participants->count() }} orang</p>
                            </div>
                        </div>
                    </div>

                    {{-- Instructions Card --}}
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl border border-yellow-200 p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Cara Menggunakan</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>Tampilkan QR Code ini kepada peserta</li>
                                        <li>Peserta scan menggunakan aplikasi atau menu Scan Presensi</li>
                                        <li>Sistem akan mencatat kehadiran secara otomatis</li>
                                        <li>Monitor kehadiran real-time di tab Peserta</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Center Section - QR Code --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 text-center">
                            <h3 class="text-xl font-bold text-white">Kode QR Presensi</h3>
                            <p class="text-green-100 text-sm mt-1">
                                @if ($event->isActiveForAttendance())
                                    Pindai kode ini untuk melakukan presensi
                                @elseif ($event->status === 'Terjadwal')
                                    QR Code akan aktif saat event dimulai
                                @else
                                    QR Code tidak aktif untuk presensi baru
                                @endif
                            </p>
                        </div>

                        <div class="p-8 text-center">
                            <!-- Event Code Display -->
                            <div class="mb-6">
                                <p class="text-sm font-medium text-gray-500 mb-2">Kode Event</p>
                                <div class="bg-gray-100 rounded-lg p-3 inline-block">
                                    <p class="text-2xl font-mono font-bold text-gray-900 tracking-wider">
                                        {{ $event->code ?? 'EVT-' . $event->id }}
                                    </p>
                                </div>
                            </div>

                            <!-- QR Code Container -->
                            <div class="flex justify-center mb-6">
                                <div class="bg-white p-6 rounded-2xl shadow-lg border-4 border-gray-100">
                                    <div class="bg-gradient-to-br from-green-50 to-blue-50 p-4 rounded-xl">
                                        @if ($event->isActiveForAttendance())
                                            <img src="{{ $qrCodeDataUri }}" alt="QR Code for {{ $event->title }}" 
                                                 class="mx-auto" style="max-width: 300px; height: auto;">
                                        @else
                                            <div class="text-center text-gray-500">
                                                <svg class="w-24 h-24 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="mt-2">
                                                    @if ($event->status === 'Terjadwal')
                                                        QR Code akan tersedia saat event dimulai
                                                    @else
                                                        QR Code tidak tersedia
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mb-6">
                                @if ($event->isActiveForAttendance())
                                    <button onclick="printQRCode()" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Print QR Code
                                    </button>

                                    <button onclick="downloadQRCode()" 
                                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Download QR Code
                                    </button>

                                    <button onclick="toggleFullscreen()" 
                                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                        </svg>
                                        Fullscreen
                                    </button>
                                @else
                                    <span class="text-sm text-gray-500 italic">
                                        @if ($event->status === 'Terjadwal')
                                            Fitur QR Code akan aktif saat event dimulai
                                        @else
                                            Fitur QR Code dinonaktifkan untuk event selesai
                                        @endif
                                    </span>
                                @endif
                            </div>

                            <!-- Usage Statistics -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-blue-600">{{ $event->participants->count() }}</p>
                                        <p class="text-sm text-blue-800 font-medium">Total Undangan</p>
                                    </div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-600">{{ $event->attendances->count() ?? 0 }}</p>
                                        <p class="text-sm text-green-800 font-medium">Sudah Hadir</p>
                                    </div>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-orange-600">{{ $event->participants->count() - ($event->attendances->count() ?? 0) }}</p>
                                        <p class="text-sm text-orange-800 font-medium">Belum Hadir</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Status -->
                            <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center justify-center">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 @if($event->isActiveForAttendance()) bg-green-500 @else bg-gray-400 @endif rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-gray-700">
                                            @if($event->isActiveForAttendance())
                                                QR Code aktif dan siap digunakan
                                            @elseif($event->status === 'Terjadwal')
                                                QR Code akan aktif saat event dimulai pada {{ $event->start_time->format('d M Y, H:i') }} WIB
                                            @else
                                                QR Code tidak aktif
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">Terakhir diperbarui: <span id="last-updated">{{ now()->format('H:i:s') }}</span></p>
                            </div>

                            <!-- Warning Message -->
                            @if($event->status === 'Selesai')
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        <p class="text-sm text-red-800 font-medium">Peringatan: Event sudah selesai, QR Code tidak dapat digunakan untuk presensi baru.</p>
                                    </div>
                                </div>
                            @endif

                            <p class="text-lg text-gray-600 font-medium">
                                @if ($event->isActiveForAttendance())
                                    Tampilkan kode ini kepada peserta acara untuk melakukan presensi
                                @elseif ($event->status === 'Terjadwal')
                                    Kode ini akan aktif untuk presensi saat event dimulai
                                @else
                                    Kode ini hanya untuk referensi, presensi tidak lagi aktif
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="mt-8 text-center">
                <x-bladewind::button tag="a" href="{{ route('admin.events.show', $event) }}" 
                    color="indigo" icon="arrow-left">
                    Kembali ke Detail Event
                </x-bladewind::button>
            </div>
        </div>
    </div>

    {{-- Fullscreen Modal --}}
    <div id="fullscreen-modal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-8">
            <div class="text-center">
                <div class="bg-white p-8 rounded-3xl shadow-2xl inline-block">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $event->title }}</h3>
                    <div class="bg-gradient-to-br from-green-50 to-blue-50 p-8 rounded-2xl mb-4">
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code for {{ $event->title }}" 
                             class="mx-auto" style="max-width: 400px; height: auto;">
                    </div>
                    <p class="text-lg font-mono font-bold text-gray-800 mb-6">{{ $event->code ?? 'EVT-' . $event->id }}</p>
                    <button onclick="toggleFullscreen()" 
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">
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
                            font-family: Arial, sans-serif; 
                            text-align: center; 
                            padding: 40px; 
                        }
                        .header { 
                            margin-bottom: 30px; 
                        }
                        .qr-container { 
                            margin: 30px 0; 
                        }
                        .event-code { 
                            font-size: 24px; 
                            font-weight: bold; 
                            font-family: monospace; 
                            margin: 20px 0; 
                        }
                        .instructions { 
                            margin-top: 30px; 
                            font-size: 14px; 
                            color: #666; 
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>QR Code Presensi</h1>
                        <h2>{{ $event->title }}</h2>
                        <p>{{ $event->start_time->format('d F Y, H:i') }} WIB</p>
                        <p>{{ $event->location }}</p>
                    </div>
                    <div class="qr-container">
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code" style="max-width: 300px;">
                    </div>
                    <div class="event-code">
                        Kode Event: {{ $event->code ?? 'EVT-' . $event->id }}
                    </div>
                    <div class="instructions">
                        <p>Scan QR Code ini untuk melakukan presensi</p>
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
                link.download = 'qr-code-{{ Str::slug($event->title) }}.png';
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
    </script>
    @endpush
</x-app-layout>