<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="bg-blue-100 p-2 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h-4.01M12 12l4-4m0 0v4m0-4h-4m4 4v4m0 0h4m0-4h-4m4-4h-4m0 0v4">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800">
                    Scan QR Code Presensi
                </h2>
                <p class="text-sm text-gray-600">
                    Selamat datang, {{ Auth::user()->full_name ?? 'Peserta' }}! Arahkan kamera ke QR Code untuk
                    melakukan presensi.

                    @if (Auth::user()->role !== 'participant')
                        <span class="text-red-600 font-medium">Akses ditolak: Hanya peserta yang diizinkan.</span>
                    @endif
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Notifications -->
            @if (session('success'))
                <div class="mb-6">
                    <x-bladewind::alert type="success">
                        {{ session('success') }}
                    </x-bladewind::alert>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6">
                    <x-bladewind::alert type="error">
                        {{ session('error') }}
                    </x-bladewind::alert>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6">
                    <x-bladewind::alert type="warning">
                        {{ session('warning') }}
                    </x-bladewind::alert>
                </div>
            @endif

            <div class="py-8">
                <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Scanner Card -->
                    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                    <span class="text-white font-medium">Scanner Aktif</span>
                                </div>
                                <div class="text-white text-sm opacity-75" id="scan-status">
                                    Mencari QR Code...
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Scanner Container -->
                            <div class="relative">
                                <div id="qr-reader" class="rounded-xl overflow-hidden" style="width: 100%;"></div>

                                <!-- Scanner Overlay -->
                                <div class="absolute inset-0 pointer-events-none" id="scanner-overlay">
                                    <div class="absolute inset-4 border-2 border-white rounded-xl opacity-50"></div>
                                    <div
                                        class="absolute top-4 left-4 w-6 h-6 border-l-4 border-t-4 border-blue-400 rounded-tl-lg">
                                    </div>
                                    <div
                                        class="absolute top-4 right-4 w-6 h-6 border-r-4 border-t-4 border-blue-400 rounded-tr-lg">
                                    </div>
                                    <div
                                        class="absolute bottom-4 left-4 w-6 h-6 border-l-4 border-b-4 border-blue-400 rounded-bl-lg">
                                    </div>
                                    <div
                                        class="absolute bottom-4 right-4 w-6 h-6 border-r-4 border-b-4 border-blue-400 rounded-br-lg">
                                    </div>
                                </div>
                            </div>

                            <!-- Results Area -->
                            <div id="qr-reader-results"
                                class="mt-6 text-center min-h-[60px] flex items-center justify-center">
                                <div class="text-gray-500">
                                    <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <p class="text-sm">Posisikan QR Code di dalam frame</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions Card -->
                    <div
                        class="mt-6 bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl border border-amber-200 p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="bg-amber-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-amber-800 mb-2">Petunjuk Penggunaan</h3>
                                <div class="space-y-2 text-sm text-amber-700">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                                        <span>Pastikan pencahayaan cukup terang</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                                        <span>Posisikan QR Code di tengah frame</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                                        <span>Jaga jarak sekitar 15-30 cm dari kamera</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                                        <span>Presensi akan otomatis tersimpan setelah scan berhasil</span>
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

            {{-- Script untuk menjalankan scanner --}}
            @push('scripts')
                <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const statusElement = document.getElementById('scan-status');
                        const resultsElement = document.getElementById('qr-reader-results');

                        function updateStatus(message, type = 'info') {
                            statusElement.textContent = message;

                            const colors = {
                                'scanning': 'text-blue-200',
                                'success': 'text-green-200',
                                'error': 'text-red-200'
                            };

                            statusElement.className = `text-sm opacity-75 ${colors[type] || 'text-white'}`;
                        }

                        function onScanSuccess(decodedText, decodedResult) {
                            // Update status
                            updateStatus('QR Code terdeteksi! Memproses...', 'success');

                            // Update results with success message
                            resultsElement.innerHTML = `
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-green-800 font-medium">QR Code berhasil dipindai!</span>
                            </div>
                            <p class="text-green-600 text-sm mt-1">Sedang memproses presensi...</p>
                        </div>
                    `;

                            // Isi form tersembunyi dengan hasil scan
                            document.getElementById('event_code').value = decodedText;

                            // Delay sedikit untuk user experience yang lebih baik
                            setTimeout(() => {
                                document.getElementById('scan-form').submit();
                            }, 1000);

                            // Hentikan scanner setelah berhasil
                            if (html5QrcodeScanner) {
                                html5QrcodeScanner.clear().catch(error => {
                                    console.log('Scanner cleared');
                                });
                            }
                        }

                        function onScanFailure(error) {
                            // Update status periodically to show scanner is active
                            const messages = [
                                'Mencari QR Code...',
                                'Siap untuk scan...',
                                'Posisikan QR Code...'
                            ];
                            const randomMessage = messages[Math.floor(Math.random() * messages.length)];
                            updateStatus(randomMessage, 'scanning');
                        }

                        let html5QrcodeScanner = new Html5QrcodeScanner(
                            "qr-reader", {
                                fps: 10,
                                qrbox: {
                                    width: 280,
                                    height: 280
                                },
                                aspectRatio: 1.0
                            },
                            false
                        );

                        // Update initial status
                        updateStatus('Mengaktifkan kamera...', 'scanning');

                        // Render scanner
                        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

                        // Update status when camera is ready
                        setTimeout(() => {
                            updateStatus('Siap untuk scan!', 'scanning');
                        }, 2000);
                    });
                </script>
            @endpush
</x-app-layout>
