<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="bg-yellow-100 p-3 rounded-xl">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Scan QR Code Presensi
                </h2>
                <p class="text-gray-600 mt-1">
                    Selamat datang, {{ Auth::user()->full_name ?? 'Peserta' }}! Arahkan kamera ke QR Code untuk melakukan presensi.
                </p>
                @if (Auth::user()->role !== 'participant')
                    <p class="text-red-600 font-medium text-sm mt-1">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Akses ditolak: Hanya peserta yang diizinkan.
                    </p>
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
        @keyframes pulse-scan {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
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
        .pulse-scan {
            animation: pulse-scan 2s ease-in-out infinite;
        }
        .scanner-overlay {
            background: radial-gradient(circle, transparent 30%, rgba(0,0,0,0.5) 70%);
        }
    </style>
    @endpush

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-6 py-8 space-y-8">

            <!-- Notifications -->
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

            @if (session('error'))
                <div class="animate-fade-in">
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="animate-fade-in">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <p class="text-yellow-800 font-medium">{{ session('warning') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Scanner Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Scanner Card - Takes 2 columns on large screens -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 card-hover animate-slide-up">
                        <!-- Scanner Header -->
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-6 rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="p-3 bg-white bg-opacity-20 rounded-xl pulse-scan">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">QR Code Scanner</h3>
                                        <p class="text-yellow-100 text-sm">Siap untuk memindai QR Code presensi</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center space-x-2 text-white">
                                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                        <span class="font-medium text-sm" id="scan-status">Scanner Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scanner Body -->
                        <div class="p-6">
                            <!-- Scanner Container with Enhanced Overlay -->
                            <div class="relative mb-6">
                                <div id="qr-reader" class="rounded-xl overflow-hidden shadow-lg" style="width: 100%;"></div>
                                
                                <!-- Enhanced Scanner Overlay -->
                                <div class="absolute inset-0 pointer-events-none scanner-overlay" id="scanner-overlay">
                                    <!-- Corner Indicators -->
                                    <div class="absolute top-6 left-6 w-8 h-8 border-l-4 border-t-4 border-yellow-400 rounded-tl-lg animate-pulse"></div>
                                    <div class="absolute top-6 right-6 w-8 h-8 border-r-4 border-t-4 border-yellow-400 rounded-tr-lg animate-pulse"></div>
                                    <div class="absolute bottom-6 left-6 w-8 h-8 border-l-4 border-b-4 border-yellow-400 rounded-bl-lg animate-pulse"></div>
                                    <div class="absolute bottom-6 right-6 w-8 h-8 border-r-4 border-b-4 border-yellow-400 rounded-br-lg animate-pulse"></div>
                                </div>
                            </div>

                            <!-- Results Area -->
                            <div id="qr-reader-results" class="min-h-[80px] flex items-center justify-center">
                                <div class="text-center">
                                    <div class="p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 inline-block">
                                        <svg class="w-10 h-10 mx-auto mb-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <p class="text-sm font-medium text-yellow-800">Posisikan QR Code di dalam frame</p>
                                        <p class="text-xs text-yellow-600 mt-1">Scanner akan otomatis mendeteksi QR Code</p>
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
                        <div class="p-6">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="p-3 bg-green-100 rounded-xl">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Status Scanner</h3>
                                    <p class="text-sm text-gray-500">Real-time scanner info</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                    <span class="text-sm font-medium text-green-800">Kamera</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-green-600 font-semibold">Aktif</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <span class="text-sm font-medium text-yellow-800">QR Detection</span>
                                    <span class="text-xs text-yellow-600 font-semibold">Siap</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-slide-up">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-3 bg-blue-100 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Petunjuk Scan</h3>
                                    <p class="text-sm text-gray-500">Tips untuk scan optimal</p>
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
                                        <p class="font-medium text-gray-900 text-sm">Pencahayaan Cukup</p>
                                        <p class="text-xs text-gray-500 mt-1">Pastikan area memiliki cahaya yang terang</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">2</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Posisi QR Code</p>
                                        <p class="text-xs text-gray-500 mt-1">Arahkan ke tengah frame scanner</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">3</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Jarak Optimal</p>
                                        <p class="text-xs text-gray-500 mt-1">15-30 cm dari kamera</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-white text-xs font-bold">4</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Auto Processing</p>
                                        <p class="text-xs text-gray-500 mt-1">Presensi tersimpan otomatis</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span class="text-sm font-bold text-green-800">Tips Pro:</span>
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
                const indicator = statusContainer.querySelector('.w-3');
                
                // Reset classes
                indicator.className = 'w-3 h-3 rounded-full';
                
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
                        <div class="p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border-2 border-green-200 inline-block">
                            <div class="flex items-center justify-center w-16 h-16 bg-green-500 rounded-full mx-auto mb-4 animate-bounce">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-green-800 mb-2">QR Code Berhasil Dipindai!</h4>
                            <p class="text-sm text-green-600">Sedang memproses presensi Anda...</p>
                            <div class="mt-4 flex items-center justify-center">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-500"></div>
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

            // Initialize scanner with enhanced configuration
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", 
                {
                    fps: 10,
                    qrbox: {
                        width: 300,
                        height: 300
                    },
                    aspectRatio: 1.0,
                    showTorchButtonIfSupported: true,
                    showZoomSliderIfSupported: true
                },
                false
            );

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