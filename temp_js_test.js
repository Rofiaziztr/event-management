    // scripts
        <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js "></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const resultsElement = document.getElementById('qr-reader-results');
                let html5QrcodeScanner = null;
                let isInitialized = false;

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
                            let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;

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
                            borderDiv.className = 'absolute border-4 border-green-400 rounded-lg pointer-events-none animate-pulse';
                            borderDiv.style.left = minX + 'px';
                            borderDiv.style.top = minY + 'px';
                            borderDiv.style.width = width + 'px';
                            borderDiv.style.height = height + 'px';
                            borderDiv.style.boxShadow = '0 0 20px rgba(34, 197, 94, 0.5)';

                            overlay.appendChild(borderDiv);

                            // Add corner markers
                            const corners = [
                                { x: minX, y: minY }, // top-left
                                { x: maxX, y: minY }, // top-right
                                { x: maxX, y: maxY }, // bottom-right
                                { x: minX, y: maxY }  // bottom-left
                            ];

                            corners.forEach((corner, index) => {
                                const cornerDiv = document.createElement('div');
                                cornerDiv.className = 'absolute w-4 h-4 bg-green-400 rounded-full border-2 border-white';
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
    // end scripts
