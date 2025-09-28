<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">{{ $event->title }}</h2>
                <p class="text-gray-600 mt-1">Dibuat oleh {{ $event->creator->full_name }}</p>
                @if ($event->category)
                    <p class="text-sm text-yellow-600 font-medium">{{ $event->category->name }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $dynamicStatus = $event->status;
                @endphp
                @if ($dynamicStatus == 'Terjadwal')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                        <div class="w-2 h-2 bg-cyan-400 rounded-full mr-2"></div>{{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Berlangsung')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></div>{{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Selesai')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>{{ $dynamicStatus }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>{{ $dynamicStatus }}
                    </span>
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
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
    @endpush

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Status Kehadiran & Tombol Presensi --}}
            <div class="animate-fade-in">
                 @if ($attendance)
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold">Anda Sudah Hadir</p>
                                <p class="text-emerald-100 text-sm">Check-in pada: {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>
                @elseif ($dynamicStatus === 'Berlangsung')
                     <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                            <div class="flex items-center">
                                <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-2xl font-bold">Event Sedang Berlangsung</p>
                                    <p class="text-yellow-100 text-sm">Segera lakukan presensi dengan scan QR Code.</p>
                                </div>
                            </div>
                            <a href="{{ route('scan.index') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 bg-white text-yellow-600 font-bold rounded-lg shadow-md hover:bg-yellow-50 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M9 16h4.01" /></svg>
                                Lakukan Presensi
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Konten Utama: Detail, Notulensi, dan Lampiran --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in">
                {{-- Kolom Kiri (Main) --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Deskripsi Acara --}}
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Deskripsi Acara</h3>
                        </div>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            @if ($event->description)
                                <p class="whitespace-pre-wrap">{{ $event->description }}</p>
                            @else
                                <p class="text-gray-400 italic">Belum ada deskripsi untuk acara ini.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Notulensi --}}
                    @php $notulensi = $event->documents->whereNull('file_path')->first(); @endphp
                     <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-violet-100 rounded-lg">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Notulensi Acara</h3>
                        </div>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            @if ($notulensi && $notulensi->content)
                                {!! $notulensi->content !!}
                            @else
                                <p class="text-gray-400 italic">Notulensi belum tersedia untuk acara ini.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan (Sidebar) --}}
                <div class="space-y-6">
                    {{-- Info Event --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Informasi Acara</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <div class="flex items-center mb-2"><svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg><span class="text-sm font-medium text-gray-500">Lokasi</span></div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->location }}</p>
                            </div>
                            <hr class="border-gray-200">
                            <div>
                                <div class="flex items-center mb-2"><svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg><span class="text-sm font-medium text-gray-500">Dibuat oleh</span></div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->creator->full_name }}</p>
                            </div>
                            <hr class="border-gray-200">
                            <div>
                                <div class="flex items-center mb-2"><svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg><span class="text-sm font-medium text-gray-500">Waktu</span></div>
                                <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->start_time->format('d M Y, H:i') }} - {{ $event->end_time->format('H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Lampiran --}}
                    @php $documents = $event->documents->whereNotNull('file_path'); @endphp
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Lampiran</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $documents->count() }} File</span>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($documents as $document)
                                <div class="p-3 hover:bg-yellow-50/50 flex items-center justify-between">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-200 text-gray-500 text-xs font-semibold">
                                                {{ strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) }}
                                            </div>
                                        </div>
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $document->title }}</p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-1 ml-2 flex-shrink-0">
                                        {{-- Tombol Lihat --}}
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                           class="p-2 text-gray-400 hover:bg-blue-100 hover:text-blue-600 rounded-full transition-colors duration-150"
                                           title="Lihat File">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        {{-- Tombol Download --}}
                                         <a href="{{ Storage::url($document->file_path) }}" download
                                           class="p-2 text-gray-400 hover:bg-green-100 hover:text-green-600 rounded-full transition-colors duration-150"
                                           title="Unduh File">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="p-4 text-sm text-gray-500 text-center">Tidak ada lampiran.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Kembali --}}
            <div class="mt-8 flex justify-end">
                 <a href="{{ route('participant.events.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar Acara
                </a>
            </div>
        </div>
    </div>
</x-app-layout>