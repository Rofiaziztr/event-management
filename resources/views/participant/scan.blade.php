<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="bg-yellow-100 p-3 rounded-xl">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="5" height="5" rx="1" fill="currentColor" opacity="0.3" />
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
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Scan QR Code Presensi
                </h2>
            </div>
        </div>
    </x-slot>

    @push('styles')
        <style>
        </style>
    @endpush

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-6 py-8 space-y-8">

            <!-- Main Scanner Section -->
            <div class="grid grid-cols-1 gap-8">

                <!-- Scanner Card - Responsive sizing -->
                <div class="max-w-4xl mx-auto w-full">
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                        <!-- Scanner Header -->
                        <div class="bg-yellow-500 p-4 rounded-t-2xl">
                            <div class="flex items-center justify-center">
                                <h3 class="text-lg font-bold text-white">QR Code Scanner</h3>
                            </div>
                        </div>

                        <!-- Scanner Body -->
                        <div class="p-6">
                            <!-- Scanner Container -->
                            <div class="relative mb-6">
                                <div id="qr-reader" class="rounded-xl overflow-hidden shadow-lg bg-gray-100 relative"
                                    style="width: 100%; min-height: 400px;">
                                    <!-- Dynamic QR detection overlay -->
                                    <div id="qr-detection-overlay"
                                        class="absolute inset-0 pointer-events-none z-10 hidden">
                                        <!-- This will be populated dynamically when QR codes are detected -->
                                    </div>
                                    <!-- Fallback content while loading -->
                                    <div class="flex items-center justify-center h-80 md:h-96 text-gray-500 absolute inset-0 z-20"
                                        id="scanner-loading">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto mb-4 animate-spin text-yellow-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <p class="text-sm font-medium">Memuat scanner kamera...</p>
                                            <p class="text-xs mt-1">Pastikan izinkan akses kamera di browser</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Results Area -->
                            <div id="qr-reader-results" class="min-h-[80px] flex items-center justify-center">
                                <div class="text-center">
                                    <div
                                        class="p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 inline-block">
                                        <svg class="w-10 h-10 mx-auto mb-2 text-yellow-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <p class="text-sm font-medium text-yellow-800">Posisikan QR Code di dalam frame
                                        </p>
                                        <p class="text-xs text-yellow-600 mt-1">Scanner akan otomatis mendeteksi QR Code
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- GPS required message - hidden by default -->
                            <div id="gps-required-message"
                                class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm text-center">
                                    <strong>Lokasi (GPS) diperlukan untuk presensi</strong>
                                    <p>Untuk melakukan presensi menggunakan QR Code, Anda harus mengaktifkan izin lokasi di perangkat Anda.
                                    Silakan aktifkan layanan lokasi dan refresh halaman, lalu coba scan kembali.</p>
                            </div>

                            <!-- Scan Tips Card -->
                            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Scan QR Code:</h4>
                                        <ul class="text-xs text-blue-700 space-y-1">
                                            <li>• Pastikan pencahayaan cukup terang</li>
                                            <li>• Posisikan QR code di tengah area scanning</li>
                                            <li>• Jaga jarak 20-40 cm dari kamera</li>
                                            <li>• Tahan posisi stabil selama scanning</li>
                                            <li>• Pastikan QR code terlihat jelas dan tidak buram</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form tersembunyi untuk mengirim hasil scan --}}
            <form action="{{ route('scan.verify') }}" method="POST" id="scan-form" class="hidden">
                @csrf
                <input type="hidden" name="event_code" id="event_code">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js "></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const resultsElement = document.getElementById('qr-reader-results');
                let html5QrcodeScanner = null;
                let isInitialized = false;

                // Get user location immediately
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            document.getElementById('latitude').value = lat;
                            document.getElementById('longitude').value = lng;
                            console.log('Location acquired:', lat, lng);
                            // Hide gps required message if shown
                            const gpsMsg = document.getElementById('gps-required-message');
                            if (gpsMsg) gpsMsg.classList.add('hidden');
                        },
                        function(error) {
                            console.warn('Location access denied or error:', error.message);
                            const gpsMsg = document.getElementById('gps-required-message');
                            if (gpsMsg) gpsMsg.classList.remove('hidden');
                        }, {
                            enableHighAccuracy: true,
                            timeout: 5000,
                            maximumAge: 0
                        }
                    );
                } else {
                    console.warn('Geolocation is not supported by this browser.');
                }

                function onScanSuccess(decodedText, decodedResult) {
                    console.log('QR Code detected:', decodedText);

                    // Draw dynamic overlay around detected QR code
                    drawQROverlay(decodedResult);

                    if (resultsElement) {
                        resultsElement.innerHTML = `
                            <div class="text-center">
                                <div class="p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border-2 border-green-200 inline-block">
                                    <div class="flex items-center justify-center w-16 h-16 bg-green-500 rounded-full mx-auto mb-4">
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
                    }

                    const eventCodeInput = document.getElementById('event_code');
                    if (eventCodeInput) {
                        eventCodeInput.value = decodedText;
                    }

                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.stop().then(() => {
                            html5QrcodeScanner.clear();
                        }).catch(() => {});
                    }

                    setTimeout(() => {
                        const scanForm = document.getElementById('scan-form');
                        const latitudeInput = document.getElementById('latitude');
                        const longitudeInput = document.getElementById('longitude');
                        if (!latitudeInput || !longitudeInput || !latitudeInput.value || !longitudeInput
                            .value) {
                            // Show GPS message and do not submit
                            const gpsMsg = document.getElementById('gps-required-message');
                            if (gpsMsg) gpsMsg.classList.remove('hidden');
                            return;
                        }
                        if (scanForm) {
                            scanForm.submit();
                        }
                    }, 1500);
                }

                function onScanFailure(error) {
                    // Hide overlay when no QR code is detected
                    hideQROverlay();

                    // Reduce console noise for common scanning errors
                    if (!error.includes('No MultiFormat Readers were able to detect the code')) {
                        console.debug('Scan failure:', error);
                    }
                }

                function drawQROverlay(decodedResult) {
                    const overlay = document.getElementById('qr-detection-overlay');
                    if (!overlay) return;

                    // Clear previous overlay
                    overlay.innerHTML = '';

                    // Get QR code corner points
                    if (decodedResult && decodedResult.result && decodedResult.result.points) {
                        const points = decodedResult.result.points;

                        if (points.length >= 4) {
                            // Calculate bounding box
                            let minX = Infinity,
                                minY = Infinity,
                                maxX = -Infinity,
                                maxY = -Infinity;

                            points.forEach(point => {
                                minX = Math.min(minX, point.x);
                                minY = Math.min(minY, point.y);
                                maxX = Math.max(maxX, point.x);
                                maxY = Math.max(maxY, point.y);
                            });

                            const width = maxX - minX;
                            const height = maxY - minY;

                            // Create overlay border
                            const borderDiv = document.createElement('div');
                            borderDiv.className =
                                'absolute border-4 border-green-400 rounded-lg pointer-events-none animate-pulse';
                            borderDiv.style.left = minX + 'px';
                            borderDiv.style.top = minY + 'px';
                            borderDiv.style.width = width + 'px';
                            borderDiv.style.height = height + 'px';
                            borderDiv.style.boxShadow = '0 0 20px rgba(34, 197, 94, 0.5)';

                            overlay.appendChild(borderDiv);

                            // Add corner markers
                            const corners = [{
                                    x: minX,
                                    y: minY
                                }, // top-left
                                {
                                    x: maxX,
                                    y: minY
                                }, // top-right
                                {
                                    x: maxX,
                                    y: maxY
                                }, // bottom-right
                                {
                                    x: minX,
                                    y: maxY
                                } // bottom-left
                            ];

                            corners.forEach((corner, index) => {
                                const cornerDiv = document.createElement('div');
                                cornerDiv.className =
                                    'absolute w-4 h-4 bg-green-400 rounded-full border-2 border-white';
                                cornerDiv.style.left = (corner.x - 8) + 'px';
                                cornerDiv.style.top = (corner.y - 8) + 'px';
                                overlay.appendChild(cornerDiv);
                            });
                        }
                    }

                    // Show overlay
                    overlay.classList.remove('hidden');
                }

                function hideQROverlay() {
                    const overlay = document.getElementById('qr-detection-overlay');
                    if (overlay) {
                        overlay.classList.add('hidden');
                        overlay.innerHTML = '';
                    }
                }

                function initializeScanner() {
                    if (isInitialized) return;

                    if (typeof Html5Qrcode === 'undefined') {
                        console.warn('HTML5 QR Code library not loaded yet, retrying...');
                        setTimeout(initializeScanner, 500);
                        return;
                    }

                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        console.error('Browser tidak mendukung kamera');
                        return;
                    }

                    try {
                        if (html5QrcodeScanner) {
                            html5QrcodeScanner.stop().then(() => {
                                html5QrcodeScanner.clear();
                            }).catch(() => {});
                        }

                        // Use Html5Qrcode class directly for better compatibility
                        html5QrcodeScanner = new Html5Qrcode("qr-reader");

                        const config = {
                            fps: 20,
                            aspectRatio: 1.0,
                            showTorchButtonIfSupported: false,
                            showZoomSliderIfSupported: false,
                            supportedScanTypes: [Html5QrcodeSupportedFormats.QR_CODE],
                            experimentalFeatures: {
                                useBarCodeDetectorIfSupported: false
                            },
                            videoConstraints: {
                                facingMode: "environment"
                            }
                        };

                        html5QrcodeScanner.start({
                                facingMode: "environment"
                            },
                            config,
                            onScanSuccess,
                            onScanFailure
                        ).then(() => {
                            console.log('Scanner initialized successfully');
                            isInitialized = true;

                            const loadingElement = document.getElementById('scanner-loading');
                            if (loadingElement) {
                                loadingElement.style.display = 'none';
                            }

                            const overlayElement = document.querySelector(
                                '.absolute.inset-0.pointer-events-none');
                            if (overlayElement) {
                                overlayElement.style.opacity = '0.3';
                            }
                        }).catch((error) => {
                            console.error('Scanner init error:', error);

                            if (error.name === 'NotAllowedError') {
                                console.warn('Camera permission denied');
                            } else {
                                console.warn('Scanner initialized with warnings');
                                setTimeout(() => {
                                    const loadingElement = document.getElementById('scanner-loading');
                                    if (loadingElement) {
                                        loadingElement.style.display = 'none';
                                    }
                                }, 2000);
                            }
                        });

                    } catch (error) {
                        console.error('Scanner setup error:', error);

                        setTimeout(() => {
                            const loadingElement = document.getElementById('scanner-loading');
                            if (loadingElement) {
                                loadingElement.style.display = 'none';
                            }
                        }, 1000);
                    }
                }

                setTimeout(() => {
                    initializeScanner();
                }, 1000);
            });
        </script>
    @endpush
</x-app-layout>
