<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3" x-data="slideIn('down', 100)">
            <div class="bg-yellow-100 p-3 rounded-xl animate-pulse hover-glow">
                <svg class="w-8 h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h4.5v4.5h-4.5v-4.5z"></path>
                </svg>
            </div>
            <div x-data="fadeIn(200)">
                <h2 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800 leading-tight flex items-center">
                    <span class="mr-2">📱</span>
                    Scan QR Code Presensi
                </h2>
                <p class="text-gray-600 md:text-lg lg:text-xl mt-1">
                    Selamat datang, {{ Auth::user()->full_name ?? 'Peserta' }}! Arahkan kamera ke QR Code untuk melakukan presensi.
                </p>
                @if (Auth::user()->role !== 'participant')
                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg" x-data="slideIn('up', 300)">
                        <p class="text-red-600 font-medium text-sm md:text-base lg:text-lg flex items-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            Akses ditolak: Hanya peserta yang diizinkan menggunakan fitur scan QR code.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        #qr-reader {
            border: 3px dashed #f59e0b;
            max-width: 100%;
            margin: 0 auto;
        }
        #qr-reader video {
            border-radius: 0.75rem;
            max-width: 100%;
            height: auto;
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-slide-up {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
        }
        .pulse-scan {
            animation: pulse-scan 2s ease-in-out infinite;
        }
        @keyframes pulse-scan {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
        .scanner-overlay {
            background: radial-gradient(circle, transparent 30%, rgba(0,0,0,0.5) 70%);
        }
        @media (min-width: 768px) {
            #qr-reader {
                border-width: 4px;
                max-width: 90%;
            }
        }
        @media (min-width: 1024px) {
            #qr-reader {
                border-width: 5px;
                max-width: 80%;
            }
        }
        .hover-glow {
            transition: all 0.3s ease;
        }
        .hover-glow:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.6);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
    @endpush

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8">

            <!-- Notifications -->
            @if (session('success'))
                <div class="animate-fade-in">
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-3 sm:p-4 shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-green-800 font-medium text-xs sm:text-sm md:text-base">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="animate-fade-in">
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-3 sm:p-4 shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-red-800 font-medium text-xs sm:text-sm md:text-base">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="animate-fade-in">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-3 sm:p-4 shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <p class="text-yellow-800 font-medium text-xs sm:text-sm md:text-base">{{ session('warning') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Scanner Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                
                <!-- Scanner Card - Takes 2 columns on large screens -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 card-hover animate-slide-up">
                        <!-- Scanner Header -->
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4 sm:p-6 rounded-t-2xl">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                                <div class="flex items-center space-x-3 sm:space-x-4">
                                    <div class="p-2 sm:p-3 bg-white bg-opacity-20 rounded-xl pulse-scan">
                                        <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-bold text-white">QR Code Scanner</h3>
                                        <p class="text-yellow-100 text-xs sm:text-sm">Siap untuk memindai QR Code presensi</p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right">
                                    <div class="flex items-center space-x-2 text-white">
                                        <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 bg-green-400 rounded-full animate-pulse"></div>
                                        <span class="font-medium text-xs sm:text-sm" id="scan-status">Scanner Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scanner Body -->
                        <div class="p-4 sm:p-6">
                            <!-- Scanner Container with Enhanced Overlay -->
                            <div class="relative mb-6 flex justify-center">
                                <div id="qr-reader" class="rounded-xl overflow-hidden shadow-lg w-full md:max-w-md lg:max-w-lg"></div>
                                
                                <!-- Enhanced Scanner Overlay -->
                                <div class="absolute inset-0 pointer-events-none scanner-overlay" id="scanner-overlay">
                                    <!-- Corner Indicators -->
                                    <div class="absolute top-6 left-6 w-6 h-6 md:w-8 md:h-8 border-l-3 md:border-l-4 border-t-3 md:border-t-4 border-yellow-400 rounded-tl-lg animate-pulse"></div>
                                    <div class="absolute top-6 right-6 w-6 h-6 md:w-8 md:h-8 border-r-3 md:border-r-4 border-t-3 md:border-t-4 border-yellow-400 rounded-tr-lg animate-pulse"></div>
                                    <div class="absolute bottom-6 left-6 w-6 h-6 md:w-8 md:h-8 border-l-3 md:border-l-4 border-b-3 md:border-b-4 border-yellow-400 rounded-bl-lg animate-pulse"></div>
                                    <div class="absolute bottom-6 right-6 w-6 h-6 md:w-8 md:h-8 border-r-3 md:border-r-4 border-b-3 md:border-b-4 border-yellow-400 rounded-br-lg animate-pulse"></div>
                                </div>
                            </div>

                            <!-- Results Area -->
                            <div id="qr-reader-results" class="min-h-[80px] flex items-center justify-center">
                                <div class="text-center w-full sm:max-w-md">
                                    <div class="p-3 sm:p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 inline-block sm:block mx-auto">
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-1 sm:mb-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <p class="text-xs sm:text-sm font-medium text-yellow-800">Posisikan QR Code di dalam frame</p>
                                        <p class="text-xs text-yellow-600 mt-0.5 sm:mt-1">Scanner akan otomatis mendeteksi QR Code</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions and Status Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Quick Status Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-slide-up">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center space-x-3 sm:space-x-4 mb-3 sm:mb-4">
                                <div class="p-2 sm:p-3 bg-green-100 rounded-xl">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-sm sm:text-base">Status Scanner</h3>
                                    <p class="text-xs sm:text-sm text-gray-500">Real-time scanner info</p>
                                </div>
                            </div>
                            
                            <div class="space-y-2 sm:space-y-3">
                                <div class="flex items-center justify-between p-2 sm:p-3 bg-green-50 rounded-lg border border-green-200">
                                    <span class="text-xs sm:text-sm font-medium text-green-800">Kamera</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-green-600 font-semibold">Aktif</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-2 sm:p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <span class="text-xs sm:text-sm font-medium text-yellow-800">QR Detection</span>
                                    <span class="text-xs text-yellow-600 font-semibold">Siap</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-slide-up">
                        <div class="p-4 sm:p-6 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 sm:p-3 bg-blue-100 rounded-xl">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-sm sm:text-base">Petunjuk Scan</h3>
                                    <p class="text-xs sm:text-sm text-gray-500">Tips untuk scan optimal</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="space-y-3 sm:space-y-4">
                                <div class="flex items-start space-x-2 sm:space-x-3">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">1</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-xs sm:text-sm">Pencahayaan Cukup</p>
                                        <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">Pastikan area memiliki cahaya yang terang</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-2 sm:space-x-3">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">2</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-xs sm:text-sm">Posisi QR Code</p>
                                        <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">Arahkan ke tengah frame scanner</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-2 sm:space-x-3">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">3</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-xs sm:text-sm">Jarak Optimal</p>
                                        <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">15-30 cm dari kamera</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-2 sm:space-x-3">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">4</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-xs sm:text-sm">Auto Processing</p>
                                        <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">Presensi tersimpan otomatis</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm font-bold text-green-800">Tips Pro:</span>
                                </div>
                                <p class="text-xs text-green-700 mt-1">Tahan perangkat stabil selama scanning untuk hasil terbaik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form tersembunyi untuk mengirim hasil scan --}}
            <form action="{{ route('scan.verify') }}" method="POST" id="scan-form" class="hidden">
                @csrf
                <input type="hidden" name="event_code" id="event_code">
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusElement = document.getElementById('scan-status');
            const resultsElement = document.getElementById('qr-reader-results');

            function updateStatus(message, type = 'info') {
                statusElement.textContent = message;
                
                const statusContainer = statusElement.parentElement;
                const indicator = statusContainer.querySelector('.w-2\.5, .w-3');
                
                // Reset classes
                indicator.className = 'w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full';
                
                switch(type) {
                    case 'scanning':
                        indicator.classList.add('bg-blue-400', 'animate-pulse');
                        break;
                    case 'success':
                        indicator.classList.add('bg-green-400', 'animate-pulse');
                        break;
                    case 'error':
                        indicator.classList.add('bg-red-400', 'animate-pulse');
                        break;
                    default:
                        indicator.classList.add('bg-yellow-400', 'animate-pulse');
                }
            }

            function onScanSuccess(decodedText, decodedResult) {
                updateStatus('QR Code terdeteksi! Memproses...', 'success');

                // Enhanced success feedback
                resultsElement.innerHTML = `
                    <div class="text-center animate-fade-in">
                        <div class="p-4 sm:p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border-2 border-green-200 inline-block sm:w-full sm:max-w-md mx-auto">
                            <div class="flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-green-500 rounded-full mx-auto mb-3 sm:mb-4 animate-bounce">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-green-800 mb-1 sm:mb-2">QR Code Berhasil Dipindai!</h4>
                            <p class="text-xs sm:text-sm text-green-600">Sedang memproses presensi Anda...</p>
                            <div class="mt-3 sm:mt-4 flex items-center justify-center">
                                <div class="animate-spin rounded-full h-5 w-5 sm:h-6 sm:w-6 border-b-2 border-green-500"></div>
                            </div>
                        </div>
                    </div>
                `;

                // Fill hidden form
                document.getElementById('event_code').value = decodedText;

                // Submit form with delay for better UX
                setTimeout(() => {
                    document.getElementById('scan-form').submit();
                }, 1500);

                // Stop scanner
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.clear().catch(error => {
                        console.log('Scanner cleared');
                    });
                }
            }

            function onScanFailure(error) {
                // Rotate through scanning messages
                const scanMessages = [
                    'Mencari QR Code...',
                    'Siap untuk scan...',
                    'Posisikan QR Code...',
                    'Scanning aktif...'
                ];
                const randomMessage = scanMessages[Math.floor(Math.random() * scanMessages.length)];
                updateStatus(randomMessage, 'scanning');
            }

            // Fungsi untuk mendapatkan ukuran QR box yang responsif berdasarkan lebar layar
            function getQRboxSize() {
                const width = window.innerWidth;
                if (width < 480) {
                    return { width: 240, height: 240 };
                } else if (width < 768) {
                    return { width: 280, height: 280 };
                } else {
                    return { width: 320, height: 320 };
                }
            }

            // Initialize scanner with enhanced configuration
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", 
                {
                    fps: 10,
                    qrbox: getQRboxSize(),
                    aspectRatio: 1.0,
                    showTorchButtonIfSupported: true,
                    showZoomSliderIfSupported: true,
                    formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
                },
                false
            );

            // Event listener untuk mereset ukuran qrbox saat ukuran window berubah
            window.addEventListener('resize', () => {
                // Recreate scanner with updated QR box size when window is resized
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.clear().then(() => {
                        html5QrcodeScanner = new Html5QrcodeScanner(
                            "qr-reader",
                            {
                                fps: 10,
                                qrbox: getQRboxSize(),
                                aspectRatio: 1.0,
                                showTorchButtonIfSupported: true,
                                showZoomSliderIfSupported: true,
                                formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
                            },
                            false
                        );
                        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                    }).catch(error => console.error("Error clearing scanner:", error));
                }
            });

            updateStatus('Mengaktifkan kamera...', 'scanning');

            // Render scanner
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);

            // Update status when scanner is ready
            setTimeout(() => {
                updateStatus('Siap untuk scan!', 'scanning');
            }, 2000);

            // Add animation observers
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