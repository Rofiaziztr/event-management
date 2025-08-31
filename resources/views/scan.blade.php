<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pindai QR Code Presensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="qr-reader" style="width: 100%;"></div>
                    <div id="qr-reader-results" class="mt-4 text-center"></div>

                    {{-- Form tersembunyi untuk mengirim hasil scan --}}
                    <form action="{{ route('scan.verify') }}" method="POST" id="scan-form" class="hidden">
                        @csrf
                        <input type="hidden" name="event_code" id="event_code">
                    </form>
                </div>
            </div>
            <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                <p><strong>Petunjuk:</strong> Arahkan kamera ke QR Code yang ditampilkan oleh panitia. Proses presensi
                    akan berjalan otomatis setelah kode berhasil dipindai.</p>
            </div>
        </div>
    </div>

    {{-- Script untuk menjalankan scanner --}}
    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            // Menjalankan script HANYA setelah seluruh halaman (DOM) selesai dimuat
            document.addEventListener('DOMContentLoaded', function() {

                function onScanSuccess(decodedText, decodedResult) {
                    // Hentikan proses logging di console agar lebih bersih
                    // console.log(`Code matched = ${decodedText}`, decodedResult);

                    // Isi form tersembunyi dengan hasil scan
                    document.getElementById('event_code').value = decodedText;

                    // Kirim form secara otomatis
                    document.getElementById('scan-form').submit();

                    // Hentikan scanner setelah berhasil
                    html5QrcodeScanner.clear().catch(error => {
                        // Abaikan error jika scanner sudah tidak ada
                    });
                }

                function onScanFailure(error) {
                    // Fungsi ini sengaja dibiarkan kosong agar tidak menampilkan error di console
                    // saat kamera tidak menemukan QR code.
                }

                let html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader", {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    /* verbose= */
                    false
                );

                // Render scanner
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            });
        </script>
    @endpush
</x-app-layout>
