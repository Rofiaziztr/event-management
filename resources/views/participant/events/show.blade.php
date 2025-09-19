<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h2>
                <div class="flex items-center space-x-2 text-sm text-gray-600 mt-1">
                    <span>Dibuat oleh {{ $event->creator->full_name }}</span>
                    @if ($event->category)
                        <span>&bull;</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ $event->category->name }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $dynamicStatus = $event->status;
                @endphp
                @if ($dynamicStatus == 'Terjadwal')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                        <div class="w-2 h-2 bg-teal-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Berlangsung')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></div>
                        {{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Selesai')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @endif

                @if ($dynamicStatus === 'Berlangsung' && !$attendance)
                    <x-bladewind::button tag="a" href="{{ route('scan.index') }}" color="emerald"
                        size="small" icon="qr-code">
                        <span class="hidden lg:inline">Lakukan Presensi</span>
                        <span class="lg:hidden">Presensi</span>
                    </x-bladewind::button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6">
                    <x-bladewind::alert type="success" class="shadow-sm">
                        {{ session('success') }}
                    </x-bladewind::alert>
                </div>
            @endif

            <div class="mb-8">
                @if ($attendance)
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-emerald-100 text-sm">Status Kehadiran</p>
                                <p class="text-2xl font-bold">Anda Sudah Hadir</p>
                                <p class="text-emerald-100 text-sm">Check-in pada: {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>
                @else
                    @if ($dynamicStatus === 'Berlangsung')
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-amber-100 text-sm">Status Kehadiran</p>
                                        <p class="text-2xl font-bold">Belum Melakukan Presensi</p>
                                        <p class="text-amber-100 text-sm">Event sedang berlangsung - segera lakukan presensi</p>
                                    </div>
                                </div>
                                <x-bladewind::button tag="a" href="{{ route('scan.index') }}" 
                                    color="white" size="small" class="bg-white bg-opacity-20 border-white text-white hover:bg-opacity-30">
                                    Presensi Sekarang
                                </x-bladewind::button>
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-100 text-sm">Status Kehadiran</p>
                                    <p class="text-2xl font-bold">Belum Melakukan Presensi</p>
                                    <p class="text-gray-100 text-sm">
                                        @if ($dynamicStatus === 'Terjadwal')
                                            Event belum dimulai
                                        @else
                                            Event telah selesai
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-teal-100 text-sm">Total Peserta</p>
                            <p class="text-2xl font-bold">{{ $event->participants->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-emerald-100 text-sm">Sudah Hadir</p>
                            <p class="text-2xl font-bold">{{ $event->attendances->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-purple-100 text-sm">Dokumen</p>
                            <p class="text-2xl font-bold">{{ $event->documents->whereNotNull('file_path')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-amber-100 text-sm">Waktu Tersisa</p>
                            <p class="text-lg font-bold">{{ $event->countdown_status }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-white">Deskripsi Event</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="prose max-w-none">
                                @if($event->description)
                                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $event->description }}</p>
                                @else
                                    <p class="text-gray-400 italic">Belum ada deskripsi untuk event ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-white">Notulensi</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            @php
                                $notulensi = $event->documents->whereNotNull('content')->first();
                                $previewLimit = 200;
                            @endphp
                            @if ($notulensi && $notulensi->content)
                                <div class="prose max-w-none">
                                    <div id="notulensi-preview" class="text-gray-700 leading-relaxed">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($notulensi->content), $previewLimit) }}
                                    </div>
                                    @if (strlen(strip_tags($notulensi->content)) > $previewLimit)
                                        <button onclick="toggleNotulensi()" class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm">Lihat Lebih Lanjut</button>
                                        <div id="notulensi-full" class="hidden mt-2 text-gray-700 leading-relaxed">
                                            {!! $notulensi->content !!}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500">Notulensi belum tersedia.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Informasi Event</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500">Lokasi</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->location }}</p>
                            </div>

                            <hr class="border-gray-200">

                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500">Waktu Mulai</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->start_time->format('l, d F Y - H:i') }} WIB</p>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500">Penyelenggara</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->creator->full_name }}</p>
                                @if($event->creator->position)
                                    <p class="text-xs text-gray-500 pl-6">{{ $event->creator->position }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-white">Dokumen Terkait</h3>
                                @php
                                    $documents = $event->documents->whereNotNull('file_path');
                                @endphp
                                <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $documents->count() }}
                                </span>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @forelse ($documents as $document)
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $document->title }}</p>
                                        </div>
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                            class="ml-3 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center">
                                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-500">Belum ada dokumen tersedia</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <x-bladewind::button tag="a" href="{{ route('participant.events.index') }}" 
                    color="gray" icon="arrow-left">
                    Kembali ke Daftar Event
                </x-bladewind::button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleNotulensi() {
                const preview = document.getElementById('notulensi-preview');
                const full = document.getElementById('notulensi-full');
                const button = event.target;
                if (full.classList.contains('hidden')) {
                    full.classList.remove('hidden');
                    preview.style.display = 'none';
                    button.textContent = 'Tutup';
                } else {
                    full.classList.add('hidden');
                    preview.style.display = 'block';
                    button.textContent = 'Lihat Lebih Lanjut';
                }
            }
        </script>
    @endpush
</x-app-layout>