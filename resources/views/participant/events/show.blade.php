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
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 space-y-6">
                            {{-- DESKRIPSI --}}
                            <div>
                                <h4 class="font-bold text-lg mb-2">Deskripsi</h4>
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $event->description }}</p>
                            </div>

                            {{-- NOTULENSI --}}
                            <div>
                                <h4 class="font-bold text-lg mb-2">Notulensi Rapat</h4>
                                @php
                                    $notulensi = $event->documents->where('type', 'Notulensi')->first();
                                @endphp
                                <div class="p-4 border rounded-md bg-gray-50 h-full">
                                    @if ($notulensi && $notulensi->content)
                                        <p class="text-gray-700 whitespace-pre-wrap">{{ $notulensi->content }}</p>
                                    @else
                                        <p class="text-gray-500">Notulensi belum tersedia.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- INFORMASI ACARA & DOKUMEN --}}
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-bold text-lg mb-3">Informasi Acara</h4>
                                <dl>
                                    <div class="mb-3 pb-3 border-b">
                                        <dt class="text-sm font-medium text-gray-500">Status Kehadiran Anda</dt>
                                        <dd class="mt-1">
                                            @if ($attendance)
                                                <span
                                                    class="px-2 inline-flex text-sm leading-6 font-semibold rounded-full bg-green-100 text-green-800">
                                                    ✔ ANDA SUDAH HADIR
                                                </span>
                                                <p class="text-xs text-gray-500 mt-1">Check-in pada:
                                                    {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('d M Y, H:i') }}
                                                </p>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-sm leading-6 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Belum Melakukan Presensi
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="mb-3">
                                        <dt class="text-sm font-medium text-gray-500">Status Event</dt>
                                        <dd class="mt-1">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if ($event->status == 'Terjadwal') bg-blue-100 text-blue-800 
                                                @elseif($event->status == 'Berlangsung') bg-green-100 text-green-800 
                                                @elseif($event->status == 'Selesai') bg-gray-100 text-gray-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $event->status }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="mb-3">
                                        <dt class="text-sm font-medium text-gray-500">Waktu Mulai</dt>
                                        <dd class="mt-1 font-semibold text-gray-900">
                                            {{ $event->start_time->format('l, d F Y - H:i') }} WIB</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                                        <dd class="mt-1 font-semibold text-gray-900">{{ $event->location }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div>
                                <h5 class="font-semibold mb-2 text-gray-800">Dokumen Terkait</h5>
                                <ul class="divide-y divide-gray-200 border rounded-md">
                                    @php
                                        $files = $event->documents->where('type', '!=', 'Notulensi');
                                    @endphp
                                    @forelse ($files as $document)
                                        <li class="p-3 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $document->title }}</p>
                                                <p class="text-sm text-gray-500">Tipe: {{ $document->type }}</p>
                                            </div>
                                            <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                Lihat
                                            </a>
                                        </li>
                                    @empty
                                        <li class="p-3 text-sm text-gray-500">Belum ada dokumen yang tersedia.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t pt-4">
                        <a href="{{ route('participant.events.index') }}"
                            class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali ke Daftar Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
