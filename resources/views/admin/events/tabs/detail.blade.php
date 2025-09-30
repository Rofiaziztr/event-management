<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10">
    {{-- Main Content - Description & Details --}}
    <div class="lg:col-span-2 space-y-6 md:space-y-8">
        {{-- Event Description --}}
    <div class="bg-white rounded-2xl p-6 md:p-8 lg:p-10 shadow-xl border border-yellow-100">
            <div class="flex items-center mb-3 md:mb-4 lg:mb-5">
                <div class="p-2 md:p-2.5 lg:p-3 bg-blue-100 rounded-lg shadow-sm">
                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4 lg:w-5 lg:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-sm md:text-base lg:text-lg font-semibold text-gray-900 ml-2 md:ml-3">Deskripsi Acara</h3>
            </div>
            <div class="prose max-w-none">
                @if ($event->description)
                    <p class="text-gray-700 text-xs md:text-sm leading-relaxed whitespace-pre-wrap">{{ $event->description }}</p>
                @else
                    <p class="text-gray-400 text-xs md:text-sm italic">Belum ada deskripsi untuk acara ini.</p>
                @endif
            </div>
        </div>

        {{-- Notulensi Preview --}}
        @php
            $notulensi = $event->documents->whereNull('file_path')->first();
        @endphp
    <div class="bg-white rounded-2xl p-6 md:p-8 lg:p-10 shadow-xl border border-yellow-100">
            <div class="flex items-center justify-between mb-4 md:mb-6 lg:mb-8">
                <div class="flex items-center">
                    <div class="p-2 md:p-2.5 lg:p-3 bg-violet-100 rounded-lg shadow-sm">
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 lg:w-5 lg:h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-sm md:text-base lg:text-lg font-semibold text-gray-900 ml-2 md:ml-3">Notulensi Acara</h3>
                </div>
                @if ($notulensi && $notulensi->content)
                    <button type="button" onclick="previewNotulensi()"
                        class="inline-flex items-center px-3 md:px-4 py-1.5 md:py-2 bg-gradient-to-r from-blue-500 to-blue-600 shadow-md text-xs md:text-sm font-medium rounded-lg text-white hover:from-blue-600 hover:to-blue-700 transition-colors duration-200 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-1.5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span class="hidden md:inline">Lihat Notulensi</span>
                        <span class="inline md:hidden">Lihat</span>
                    </button>
                @endif
            </div>
            <div class="prose max-w-none">
                @if ($notulensi && $notulensi->content)
                    <div id="notulensi-content" class="text-gray-700 text-xs md:text-sm leading-relaxed">
                        {{ \Illuminate\Support\Str::limit(strip_tags($notulensi->content), 200) }}
                    </div>
                    <div id="notulensi-full-content" class="hidden">{!! $notulensi->content !!}</div>
                @else
                    <p class="text-gray-400 text-xs md:text-sm italic">Belum ada notulensi untuk acara ini.</p>
                @endif
            </div>
        </div>

        {{-- Event Timeline --}}
    <div class="bg-white rounded-2xl p-6 md:p-8 lg:p-10 shadow-xl border border-yellow-100">
            <div class="flex items-center mb-4 md:mb-5 lg:mb-6">
                <div class="p-2 md:p-2.5 lg:p-3 bg-indigo-100 rounded-lg shadow-sm">
                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4 lg:w-5 lg:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-sm md:text-base lg:text-lg font-semibold text-gray-900 ml-2 md:ml-3">Timeline Acara</h3>
            </div>

            <div class="space-y-6">
                {{-- Start Time --}}
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-3 h-3 md:w-4 md:h-4 lg:w-5 lg:h-5 bg-green-500 rounded-full mr-3 md:mr-4 lg:mr-5 shadow-sm"></div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-xs md:text-sm font-medium text-gray-900">Mulai Acara</span>
                            <span class="text-xs md:text-sm text-gray-600 mt-1 sm:mt-0">{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $event->start_time->diffForHumans() }}</div>
                    </div>
                </div>

                {{-- Duration Line --}}
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-3 h-12 border-l-2 border-dashed border-gray-300 ml-1.5 mr-3 md:mr-4 lg:mr-5"></div>
                    <div class="flex-1">
                        <span class="text-xs md:text-sm text-gray-500 bg-gray-50 px-2 py-0.5 rounded-md">
                            Durasi: {{ $event->start_time->diffInHours($event->end_time) }} jam
                            {{ $event->start_time->diffInMinutes($event->end_time) % 60 }} menit
                        </span>
                    </div>
                </div>

                {{-- End Time --}}
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-3 h-3 md:w-4 md:h-4 lg:w-5 lg:h-5 bg-red-500 rounded-full mr-3 md:mr-4 lg:mr-5 shadow-sm"></div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-xs md:text-sm font-medium text-gray-900">Selesai Acara</span>
                            <span class="text-xs md:text-sm text-gray-600 mt-1 sm:mt-0">{{ $event->end_time->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $event->end_time->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="mt-6 md:mt-8 flex flex-wrap gap-3 justify-center md:justify-end">
            {{-- Tombol Unduh Laporan --}}
            <a href="{{ route('admin.events.export', $event) }}"
                class="inline-flex items-center px-5 md:px-6 py-2.5 md:py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg text-sm md:text-base text-white shadow-md hover:shadow-lg hover:from-yellow-600 hover:to-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200">
                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 md:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="whitespace-nowrap font-medium">Unduh Laporan</span>
            </a>

            {{-- Tombol QR Code --}}
            <a href="{{ route('admin.events.qrcode', $event) }}"
                class="inline-flex items-center px-4 md:px-5 py-2.5 md:py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-sm md:text-base text-white shadow-md hover:shadow-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 md:mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m6-5h-6m1-11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zm-9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z"/>
                </svg>
                <span class="whitespace-nowrap font-medium">QR Code</span>
            </a>
        </div>
    </div>

    {{-- Sidebar - Event Info --}}
    <div class="space-y-8">
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 md:px-6 lg:px-8 py-3 md:py-4 lg:py-4">
                <h3 class="text-sm md:text-base font-medium text-white">Informasi Acara</h3>
            </div>
            <div class="p-6 md:p-8 lg:p-10 space-y-4 md:space-y-6 lg:space-y-8">
                {{-- Location --}}
                <div>
                    <div class="flex items-center mb-1">
                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5 text-gray-400 mr-1.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-xs font-medium text-gray-500">Lokasi</span>
                    </div>
                    <p class="text-xs md:text-sm font-medium text-gray-900 pl-5 md:pl-5">{{ $event->location }}</p>
                </div>

                <hr class="border-gray-200">

                {{-- Creator --}}
                <div>
                    <div class="flex items-center mb-1">
                        <svg class="w-3 h-3 text-gray-400 mr-1.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-xs font-medium text-gray-500">Dibuat oleh</span>
                    </div>
                    <p class="text-xs font-medium text-gray-900 pl-5">{{ $event->creator->full_name }}</p>
                    @if ($event->creator->position)
                        <p class="text-xs text-gray-500 pl-5">{{ $event->creator->position }}</p>
                    @endif
                </div>

                {{-- Created Date --}}
                <div>
                    <div class="flex items-center mb-1">
                        <svg class="w-3 h-3 text-gray-400 mr-1.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-medium text-gray-500">Dibuat pada</span>
                    </div>
                    <p class="text-xs font-medium text-gray-900 pl-5">{{ $event->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>
        </div>

        {{-- Event Stats --}}
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 md:px-8 lg:px-10 py-4 md:py-5 lg:py-6">
                <h3 class="text-base md:text-lg font-semibold text-white">Statistik</h3>
            </div>
            <div class="p-6 md:p-8 lg:p-10 space-y-4 md:space-y-6 lg:space-y-8">
                <div class="flex items-center justify-between">
                    <span class="text-xs md:text-sm text-gray-500">Total Undangan</span>
                    <span class="text-sm md:text-base font-bold text-gray-900">{{ $event->participants->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Sudah Hadir</span>
                    <span class="text-xs md:text-sm font-bold text-green-600">{{ $event->attendances->count() ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Belum Hadir</span>
                    <span class="text-xs md:text-sm font-bold text-orange-600">{{ $event->participants->count() - ($event->attendances->count() ?? 0) }}</span>
                </div>

                @if ($event->participants->count() > 0)
                    <hr class="border-gray-200">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Tingkat Kehadiran</span>
                            <span class="font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ round((($event->attendances->count() ?? 0) / $event->participants->count()) * 100, 1) }}%</span>
                        </div>
                        <div class="flex items-center w-full bg-gray-200 rounded-full h-1.5 md:h-2">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-1.5 md:h-2 rounded-full"
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
<div id="preview-modal" style="display:none;" class="fixed inset-0 z-50">
    <!-- Backdrop dengan animasi fade-in -->
    <div id="modal-backdrop" class="fixed inset-0 transition-opacity duration-300 ease-out opacity-0" 
         style="background-color: rgba(0,0,0,0.6); backdrop-filter: blur(4px);" 
         onclick="closePreview()"></div>
    
    <div class="flex items-center justify-center min-h-screen p-4 relative z-[51]">
        <!-- Modal dengan animasi slide-up -->
        <div id="modal-content" class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[85vh] overflow-hidden border border-yellow-100 transition-all duration-300 ease-out transform translate-y-8 opacity-0">
            
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-white">
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">Notulensi Lengkap</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $event->title }}</p>
                </div>
                <button onclick="closePreview()" class="bg-white hover:bg-gray-200 transition-colors duration-200 rounded-full p-1.5 border border-gray-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal content -->
            <div id="preview-content-wrapper" class="p-6 overflow-y-auto max-h-[60vh] bg-white">
                <div id="preview-content" class="prose max-w-none text-sm md:text-base bg-white">
                    <!-- Konten notulensi akan ditampilkan di sini -->
                </div>
                <!-- Fallback untuk konten kosong -->
                <div id="empty-content" class="hidden text-center py-12 bg-white">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-4 text-gray-500">Belum ada isi notulensi untuk acara ini</p>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="flex justify-between items-center p-4 border-t border-gray-200 bg-white">
                <div class="text-xs text-gray-500">
                    @if ($notulensi)
                        Diperbarui: {{ $notulensi->updated_at->format('d M Y, H:i') }} WIB
                    @endif
                </div>
                <button onclick="closePreview()" class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Definisi fungsi global untuk modal notulensi
    function previewNotulensi() {
        try {
            const fullContentElement = document.getElementById('notulensi-full-content');
            const previewContent = document.getElementById('preview-content');
            const emptyContent = document.getElementById('empty-content');
            const modal = document.getElementById('preview-modal');
            const modalBackdrop = document.getElementById('modal-backdrop');
            const modalContent = document.getElementById('modal-content');
            
            if (!previewContent || !emptyContent || !modal || !modalBackdrop || !modalContent) {
                console.error('Required preview elements not found');
                return;
            }
            
            // Cek apakah ada konten notulensi
            if (fullContentElement && fullContentElement.innerHTML.trim()) {
                // Render as HTML (safe because content is from admin)
                previewContent.innerHTML = fullContentElement.innerHTML;
                previewContent.classList.remove('hidden');
                emptyContent.classList.add('hidden');
            } else {
                // Tampilkan pesan konten kosong
                previewContent.classList.add('hidden');
                emptyContent.classList.remove('hidden');
            }
            
            // Tampilkan modal dengan animasi
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling
            
            // Trigger animasi
            setTimeout(() => {
                modalBackdrop.classList.add('opacity-100');
                modalContent.classList.remove('translate-y-8', 'opacity-0');
            }, 10);
        } catch (error) {
            console.error('Error in previewNotulensi:', error);
        }
    }

    function closePreview() {
        try {
            const modal = document.getElementById('preview-modal');
            const modalBackdrop = document.getElementById('modal-backdrop');
            const modalContent = document.getElementById('modal-content');
            
            if (!modal || !modalBackdrop || !modalContent) {
                console.error('Modal elements not found');
                return;
            }
            
            // Animasi keluar
            modalBackdrop.classList.remove('opacity-100');
            modalContent.classList.add('translate-y-8', 'opacity-0');
            
            // Tunggu animasi selesai, lalu sembunyikan modal
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = ''; // Re-enable scrolling
            }, 300);
        } catch (error) {
            console.error('Error in closePreview:', error);
        }
    }
</script>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                try {
                    const modal = document.getElementById('preview-modal');
                    if (event.key === 'Escape' && modal && modal.style.display === 'block') {
                        closePreview();
                    }
                } catch (error) {
                    console.error('Error in keydown handler:', error);
                }
            });
        });
    </script>
@endpush