<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            QR Code Presensi: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <h3 class="text-2xl font-bold mb-2">Pindai Kode Ini untuk Melakukan Presensi</h3>
                    <p class="text-gray-600 mb-4">Kode Event: <strong>{{ $event->code }}</strong></p>

                    <div class="flex justify-center my-6">
                        {{-- Tampilkan gambar QR Code dari Data URI --}}
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code for {{ $event->title }}">
                    </div>

                    <p class="text-lg text-gray-600">
                        Tampilkan kode ini kepada peserta acara.
                    </p>

                    <div class="mt-8">
                        <x-bladewind::button tag="a" href="{{ route('admin.events.show', $event) }}"
                            color="indigo" outline="true">
                            &lt;-- Kembali ke Detail Event
                        </x-bladewind::button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
