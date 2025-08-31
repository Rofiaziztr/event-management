<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold border-b pb-2">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Dibuat oleh: {{ $event->creator->full_name }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <h4 class="font-bold text-lg mb-2">Deskripsi</h4>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $event->description }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-bold text-lg mb-3">Informasi Acara</h4>
                            <dl>
                                <div class="mb-2">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if ($event->status == 'Scheduled') bg-blue-100 text-blue-800 
                                            @elseif($event->status == 'Ongoing') bg-green-100 text-green-800 
                                            @elseif($event->status == 'Completed') bg-gray-100 text-gray-800 
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $event->status }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="mb-2">
                                    <dt class="text-sm font-medium text-gray-500">Waktu Mulai</dt>
                                    <dd class="mt-1 font-semibold text-gray-900">
                                        {{ $event->start_time->format('l, d F Y - H:i') }} WIB</dd>
                                </div>
                                <div class="mb-2">
                                    <dt class="text-sm font-medium text-gray-500">Waktu Selesai</dt>
                                    <dd class="mt-1 font-semibold text-gray-900">
                                        {{ $event->end_time->format('l, d F Y - H:i') }} WIB</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                                    <dd class="mt-1 font-semibold text-gray-900">{{ $event->location }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- PASTIKAN BLOK INI ADA --}}
                    <div class="mt-8 border-t pt-4 flex justify-end items-center gap-3">
                        <a href="{{ route('admin.events.index') }}"
                            class="text-gray-600 hover:text-gray-900 font-bold py-2 px-4 rounded">
                            Kembali
                        </a>

                        {{-- INI ADALAH TOMBOL YANG HILANG --}}
                        <a href="{{ route('admin.events.qrcode', $event) }}"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Tampilkan QR Code
                        </a>

                        <a href="{{ route('admin.events.edit', $event) }}"
                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Edit Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
