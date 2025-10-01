<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="bg-yellow-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m6-5h-6m1-11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zm-9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z" />
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
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus === 'Berlangsung')
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                        {{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus === 'Selesai')
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-red-100 text-red-800 border border-red-200">
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
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideUp {
                from {
                    transform: translateY(20px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            @keyframes pulse-glow {

                0%,
                100% {
                    box-shadow: 0 0 8px rgba(245, 158, 11, 0.35);
                }

                50% {
                    box-shadow: 0 0 25px rgba(245, 158, 11, 0.65);
                }
            }

            @keyframes scale-in {
                from {
                    transform: scale(0.95);
                    opacity: 0;
                }

                to {
                    transform: scale(1);
                    opacity: 1;
                }
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
        <div class="max-w-5xl mx-auto px-6 py-8 space-y-8">
            {{-- Main Content --}}
            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-fade-in overflow-hidden">
                <!-- Enhanced Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-center">
                    <div class="flex items-center justify-center space-x-3 mb-4">
                        <div class="p-2 bg-white bg-opacity-20 rounded-xl">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m6-5h-6m1-11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zm-9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white tracking-wide">QR Code Presensi</h3>
                    </div>

                    <div class="flex items-center justify-center space-x-3 mt-2">
                        <div
                            class="w-4 h-4 rounded-full {{ $event->isActiveForAttendance() ? 'bg-green-300 animate-pulse' : 'bg-red-300' }}">
                        </div>
                        <p class="text-white text-lg font-medium">
                            @if ($event->isActiveForAttendance())
                                QR Code Aktif - Siap Digunakan
                            @elseif ($event->status === 'Terjadwal')
                                QR Code akan aktif saat event dimulai
                            @else
                                QR Code tidak aktif untuk presensi baru
                            @endif
                        </p>
                    </div>
                </div>

                <div class="p-8">
                    <!-- QR Code Container with Simplified Layout -->
                    <div class="flex justify-center mb-8">
                        @if ($event->isActiveForAttendance())
                            <div class="bg-white p-8 rounded-xl shadow-xl border-4 border-yellow-200 qr-glow max-w-md">
                                <img src="{{ $qrCodeDataUri }}" alt="QR Code untuk {{ $event->title }}"
                                    class="mx-auto w-full max-w-[320px] h-auto">
                                <div class="text-center mt-5">
                                    <p class="font-medium text-gray-900 text-lg">{{ $event->title }}</p>
                                    <p class="text-base text-gray-600 mt-1">
                                        {{ $event->start_time->format('d F Y, H:i') }} WIB</p>
                                </div>
                            </div>
                        @else
                            <div
                                class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 rounded-xl shadow-xl border-2 border-yellow-200 max-w-md">
                                <div class="text-center text-gray-700">
                                    <svg class="w-32 h-32 mx-auto text-yellow-500 mb-6" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <p class="text-xl font-medium leading-relaxed">
                                        @if ($event->status === 'Terjadwal')
                                            QR Code akan tersedia saat event dimulai pada<br>
                                            <span
                                                class="font-bold text-xl">{{ $event->start_time->format('d F Y, H:i') }}
                                                WIB</span>
                                        @else
                                            QR Code tidak tersedia<br>
                                            <span class="font-bold text-xl">Event telah selesai</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons - Simplified -->
                    @if ($event->isActiveForAttendance())
                        <div class="flex flex-col sm:flex-row justify-center items-center gap-5 mb-8">
                            <button onclick="printQRCode()"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl font-semibold text-white hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-lg transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print QR Code
                            </button>

                            <button onclick="downloadQRCode()"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl font-semibold text-white hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 shadow-lg transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download QR
                            </button>

                            <button onclick="toggleFullscreen()"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl font-semibold text-white hover:from-purple-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-lg transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                                Fullscreen
                            </button>
                        </div>
                    @endif

                    <!-- Simplified Instructions -->
                    <div class="bg-gray-50 rounded-xl p-6 max-w-xl mx-auto border border-gray-200 mb-6">
                        <h4 class="font-bold text-lg text-gray-900 text-center mb-6">Cara Menggunakan QR Code</h4>

                        <!-- Desktop View (Hidden on Mobile) -->
                        <div class="hidden md:flex justify-center items-center space-x-2">
                            <div class="flex items-center justify-center text-center">
                                <div>
                                    <div
                                        class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-md mx-auto mb-2">
                                        <span class="text-white font-bold text-lg">1</span>
                                    </div>
                                    <p class="font-medium text-gray-800 text-base">Tampilkan QR Code</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-center w-12">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>

                            <div class="flex items-center justify-center text-center">
                                <div>
                                    <div
                                        class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-md mx-auto mb-2">
                                        <span class="text-white font-bold text-lg">2</span>
                                    </div>
                                    <p class="font-medium text-gray-800 text-base">Peserta scan QR</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-center w-12">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>

                            <div class="flex items-center justify-center text-center">
                                <div>
                                    <div
                                        class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-md mx-auto mb-2">
                                        <span class="text-white font-bold text-lg">3</span>
                                    </div>
                                    <p class="font-medium text-gray-800 text-base">Presensi tercatat</p>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile View (Hidden on Desktop) -->
                        <div class="md:hidden">
                            <table class="w-full">
                                <tr>
                                    <td class="pb-5">
                                        <div class="flex items-center">
                                            <div
                                                class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-md">
                                                <span class="text-white font-bold text-lg">1</span>
                                            </div>
                                            <div class="ml-4">
                                                <p class="font-medium text-gray-800 text-base">Tampilkan QR Code</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-5 border-t border-gray-200">
                                        <div class="flex items-center">
                                            <div
                                                class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-md">
                                                <span class="text-white font-bold text-lg">2</span>
                                            </div>
                                            <div class="ml-4">
                                                <p class="font-medium text-gray-800 text-base">Peserta scan QR</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-5 border-t border-gray-200">
                                        <div class="flex items-center">
                                            <div
                                                class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center shadow-md">
                                                <span class="text-white font-bold text-lg">3</span>
                                            </div>
                                            <div class="ml-4">
                                                <p class="font-medium text-gray-800 text-base">Presensi tercatat</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Back Button --}}
            <div
                class="flex flex-col md:flex-row justify-center items-center animate-fade-in space-y-4 md:space-y-0 md:space-x-5">
                <a href="{{ route('admin.events.show', $event) }}"
                    class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 rounded-xl font-medium text-white shadow-sm hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Detail Event
                </a>

                <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'peserta']) }}"
                    class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl font-medium text-white shadow-sm hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    Lihat Data Peserta
                </a>
            </div>
        </div>
    </div>

    {{-- Enhanced Fullscreen Modal --}}
    <div id="fullscreen-modal" class="fixed inset-0 hidden z-50">
        <!-- Blurred background overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-85 backdrop-blur-md"></div>

        <!-- Glowing QR container -->
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl p-8 shadow-2xl transform transition-all duration-300 ease-in-out"
                style="max-width: 95vmin; max-height: 95vmin; box-shadow: 0 0 50px rgba(255, 255, 255, 0.5);">
                <img src="{{ $qrCodeDataUri }}" alt="QR Code for {{ $event->title }}" class="w-full h-auto mx-auto"
                    style="max-width: 320px; image-rendering: pixelated;">
                <div class="text-center mt-4">
                    <p class="text-xl font-semibold text-gray-900">{{ $event->title }}</p>
                    <p class="text-lg text-gray-700 mt-1">{{ $event->start_time->format('d F Y, H:i') }} WIB</p>
                </div>
            </div>
        </div>

        <!-- Close button -->
        <div class="absolute top-6 right-6 z-10">
            <button onclick="toggleFullscreen()"
                class="bg-black bg-opacity-60 hover:bg-opacity-80 text-white rounded-full p-3 transition-all duration-200 shadow-lg hover:scale-110">
                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
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
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            min-height: 100vh;
                            margin: 0;
                            padding: 20px;
                            background: white;
                        }
                        .content {
                            width: 100%;
                            max-width: 600px;
                            padding: 30px 20px;
                            border: 2px solid #f0b429;
                            border-radius: 16px;
                            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
                        }
                        .qr-container { 
                            margin: 30px auto;
                            width: 70vmin;
                            max-width: 450px;
                            border: 3px solid #f0b429;
                            border-radius: 12px;
                            padding: 15px;
                            background-color: white;
                        }
                        .qr-container img {
                            width: 100%;
                            height: auto;
                            display: block;
                        }
                        .title {
                            font-size: 28px;
                            font-weight: bold;
                            margin-bottom: 10px;
                            color: #1a202c;
                        }
                        .date {
                            font-size: 20px;
                            color: #4a5568;
                            margin-bottom: 30px;
                        }
                        .event-info {
                            margin-top: 20px;
                            font-size: 16px;
                            color: #718096;
                        }
                        @media print {
                            .content {
                                border: 2px solid #f0b429 !important;
                                -webkit-print-color-adjust: exact;
                                color-adjust: exact;
                                print-color-adjust: exact;
                            }
                            .qr-container {
                                border: 3px solid #f0b429 !important;
                                -webkit-print-color-adjust: exact;
                                color-adjust: exact;
                                print-color-adjust: exact;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="content">
                        <div class="title">{{ $event->title }}</div>
                        <div class="date">{{ $event->start_time->format('d F Y, H:i') }} WIB</div>
                        <div class="qr-container">
                            <img src="{{ $qrCodeDataUri }}" alt="QR Code">
                        </div>
                        <div class="event-info">Scan QR code untuk presensi kehadiran</div>
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
                    link.download =
                        'qr-code-{{ Str::slug($event->title) }}-{{ $event->start_time->format('Y-m-d') }}.png';
                    link.href = canvas.toDataURL();
                    link.click();
                };

                img.src = '{{ $qrCodeDataUri }}';
            }

            function toggleFullscreen() {
                const modal = document.getElementById('fullscreen-modal');

                if (modal.classList.contains('hidden')) {
                    // Show modal with animation
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    // Animate the QR code container on show
                    setTimeout(() => {
                        const qrContainer = modal.querySelector('div.bg-white');
                        // Start from smaller size and fade in
                        qrContainer.style.opacity = '0';
                        qrContainer.style.transform = 'scale(0.9)';

                        setTimeout(() => {
                            qrContainer.style.opacity = '1';
                            qrContainer.style.transform = 'scale(1)';
                        }, 50);
                    }, 10);

                    // Disable scrolling on body
                    document.body.style.overflow = 'hidden';
                } else {
                    // Animate out
                    const qrContainer = modal.querySelector('div.bg-white');
                    qrContainer.style.opacity = '0';
                    qrContainer.style.transform = 'scale(0.9)';

                    // Delay the actual hiding
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        // Reset properties for next open
                        qrContainer.style.opacity = '';
                        qrContainer.style.transform = '';

                        // Re-enable scrolling
                        document.body.style.overflow = 'auto';
                    }, 200);
                }
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
