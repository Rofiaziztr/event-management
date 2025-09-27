<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main Content - Description & Details --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Event Description --}}
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Deskripsi Acara</h3>
            </div>
            <div class="prose max-w-none">
                @if ($event->description)
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $event->description }}</p>
                @else
                    <p class="text-gray-400 italic">Belum ada deskripsi untuk acara ini.</p>
                @endif
            </div>
        </div>

        {{-- Notulensi Preview --}}
        @php
            $notulensi = $event->documents->whereNull('file_path')->first();
        @endphp
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-2 bg-violet-100 rounded-lg">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Notulensi Acara</h3>
                </div>
                @if ($notulensi && $notulensi->content)
                    <button type="button" onclick="previewNotulensi()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview Notulensi
                    </button>
                @endif
            </div>
            <div class="prose max-w-none">
                @if ($notulensi && $notulensi->content)
                    <div id="notulensi-content" class="text-gray-700 leading-relaxed"
                        data-full-content="{{ $notulensi->content }}">
                        {{ \Illuminate\Support\Str::limit(strip_tags($notulensi->content), 200) }}</div>
                @else
                    <p class="text-gray-400 italic">Belum ada notulensi untuk acara ini.</p>
                @endif
            </div>
        </div>

        {{-- Event Timeline --}}
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Timeline Acara</h3>
            </div>

            <div class="space-y-4">
                {{-- Start Time --}}
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full"></div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">Mulai Acara</span>
                            <span class="text-sm text-gray-600">{{ $event->start_time->format('d M Y, H:i') }}
                                WIB</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $event->start_time->diffForHumans() }}</div>
                    </div>
                </div>

                {{-- Duration Line --}}
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-3 h-12 border-l-2 border-dashed border-gray-300 ml-1"></div>
                    <div class="ml-4 flex-1">
                        <span class="text-xs text-gray-500">
                            Durasi: {{ $event->start_time->diffInHours($event->end_time) }} jam
                            {{ $event->start_time->diffInMinutes($event->end_time) % 60 }} menit
                        </span>
                    </div>
                </div>

                {{-- End Time --}}
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-3 h-3 bg-red-500 rounded-full"></div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">Selesai Acara</span>
                            <span class="text-sm text-gray-600">{{ $event->end_time->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $event->end_time->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar - Event Info --}}
    <div class="space-y-6">
        <!-- Quick Info Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Informasi Acara</h3>
            </div>
            <div class="p-6 space-y-4">
                {{-- Location --}}
                <div>
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500">Lokasi</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->location }}</p>
                </div>

                <hr class="border-gray-200">

                {{-- Creator --}}
                <div>
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500">Dibuat oleh</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->creator->full_name }}</p>
                    @if ($event->creator->position)
                        <p class="text-xs text-gray-500 pl-6">{{ $event->creator->position }}</p>
                    @endif
                </div>

                {{-- Created Date --}}
                <div>
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500">Dibuat pada</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 pl-6">{{ $event->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
            </div>
            <div class="p-6 space-y-3">
    {{-- Tombol QR Code (Hijau) --}}
    <a href="{{ route('admin.events.qrcode', $event) }}"
        class="inline-flex items-center justify-center w-full px-4 py-2 
               bg-gradient-to-r from-green-500 to-green-600 
               border border-transparent rounded-xl 
               font-semibold text-white text-sm shadow-sm
               hover:from-green-600 hover:to-green-700 
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M9 16h4.01" />
        </svg>
        Tampilkan QR Code
    </a>

    {{-- Tombol Edit Event (Biru) --}}
    <a href="{{ route('admin.events.edit', $event) }}"
        class="inline-flex items-center justify-center w-full px-4 py-2 
               bg-gradient-to-r from-indigo-500 to-indigo-600 
               border border-transparent rounded-xl 
               font-semibold text-white text-sm shadow-sm
               hover:from-indigo-600 hover:to-indigo-700 
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                   a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        Edit Event
    </a>

    {{-- Tombol Export Peserta (Emas/Kuning) --}}
@if ($event->participants->count() > 0)
    <div class="relative" x-data="{ exportOpen: false }">
        <button @click="exportOpen = !exportOpen"
            class="inline-flex items-center justify-center w-full px-4 py-2 
                   bg-gradient-to-r from-yellow-400 to-yellow-500 
                   border border-transparent rounded-xl 
                   font-semibold text-white text-sm shadow-sm
                   hover:from-yellow-500 hover:to-yellow-600 
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export Peserta
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        
        <div x-show="exportOpen" x-transition @click.away="exportOpen = false"
             class="absolute bottom-full left-0 mb-2 w-64 bg-white rounded-lg shadow-lg border z-50">
            <div class="py-2">
                <a href="{{ route('admin.events.participants.export', ['event' => $event, 'type' => 'detailed']) }}" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📋 Detail Lengkap</a>
                <a href="{{ route('admin.events.participants.export', ['event' => $event, 'type' => 'summary']) }}" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📊 Ringkasan</a>
                <a href="{{ route('admin.events.participants.export', ['event' => $event, 'type' => 'attendance_only']) }}" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">⏰ Kehadiran</a>
            </div>
        </div>
    </div>
@endif
</div>

        </div>

        {{-- Event Stats --}}
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Statistik</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Total Undangan</span>
                    <span class="text-sm font-bold text-gray-900">{{ $event->participants->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Sudah Hadir</span>
                    <span class="text-sm font-bold text-green-600">{{ $event->attendances->count() ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Belum Hadir</span>
                    <span
                        class="text-sm font-bold text-orange-600">{{ $event->participants->count() - ($event->attendances->count() ?? 0) }}</span>
                </div>

                @if ($event->participants->count() > 0)
                    <hr class="border-gray-200">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Tingkat Kehadiran</span>
                            <span
                                class="font-medium">{{ round((($event->attendances->count() ?? 0) / $event->participants->count()) * 100, 1) }}%</span>
                        </div>
                        <div class="flex items-center w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-1.5 rounded-full"
                                style="width: {{ (($event->attendances->count() ?? 0) / $event->participants->count()) * 100 }}%">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div id="preview-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Preview Notulensi</h3>
                <button onclick="closePreview()" class="text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="preview-content" class="p-6 overflow-y-auto max-h-[60vh] prose max-w-none">
                <!-- Preview content will be inserted here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function exportParticipants() {
            alert('Fitur export sedang dalam pengembangan');
        }

        function previewNotulensi() {
            const contentElement = document.getElementById('notulensi-content');
            const fullContent = contentElement ? contentElement.getAttribute('data-full-content') || '' : '';
            // Render as HTML (safe because content is from admin)
            document.getElementById('preview-content').innerHTML = fullContent || 'Tidak ada notulensi yang tersedia.';
            document.getElementById('preview-modal').classList.remove('hidden');
        }

        function closePreview() {
            document.getElementById('preview-modal').classList.add('hidden');
        }
    </script>
@endpush
