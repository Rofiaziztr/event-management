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
                                        <div class="prose max-w-none">{!! $notulensi->content !!}</div>
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
                                                <x-bladewind::tag label="✔ ANDA SUDAH HADIR" color="green" />
                                                <p class="text-xs text-gray-500 mt-1">Check-in pada:
                                                    {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('d M Y, H:i') }}
                                                </p>
                                            @else
                                                <x-bladewind::tag label="Belum Melakukan Presensi" color="yellow" />
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="mb-3">
                                        <dt class="text-sm font-medium text-gray-500">Status Event</dt>
                                        <dd class="mt-1">
                                            @if ($event->status == 'Terjadwal')
                                                <x-bladewind::tag label="{{ $event->status }}" color="blue" />
                                            @elseif($event->status == 'Berlangsung')
                                                <x-bladewind::tag label="{{ $event->status }}" color="green" />
                                            @elseif($event->status == 'Selesai')
                                                <x-bladewind::tag label="{{ $event->status }}" color="gray" />
                                            @else
                                                <x-bladewind::tag label="{{ $event->status }}" color="red" />
                                            @endif
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
                        <x-bladewind::button tag="a" href="{{ route('participant.events.index') }}"
                            color="gray">
                            Kembali ke Daftar Event
                        </x-bladewind::button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
