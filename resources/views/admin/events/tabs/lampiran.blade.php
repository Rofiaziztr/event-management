@php
    // Memisahkan dokumen yang berupa file (lampiran) dari notulensi (teks saja)
    $documents = $event->documents->whereNotNull('file_path');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Kolom Konten Utama (Daftar Lampiran) --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
            {{-- Header Kartu --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Lampiran Acara</h3>
                            <p class="text-gray-500 mt-1">Dokumen, foto, atau video pendukung acara.</p>
                        </div>
                    </div>
                    <span class="mt-2 sm:mt-0 bg-blue-100 text-blue-800 text-sm font-medium px-4 py-1.5 rounded-full">
                        {{ $documents->count() }} File
                    </span>
                </div>
            </div>

            {{-- Daftar Lampiran --}}
            <div class="divide-y divide-gray-100">
                @forelse ($documents as $document)
                    <div class="p-4 hover:bg-yellow-50/50 transition-colors duration-150">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1 min-w-0">
                                {{-- Ikon Tipe File --}}
                                <div class="flex-shrink-0">
                                    @php
                                        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                        $iconClass = 'w-10 h-10 rounded-lg flex items-center justify-center text-white text-xs font-semibold';
                                        $fileIcons = [
                                            'image' => ['jpg', 'jpeg', 'png', 'gif'],
                                            'video' => ['mp4', 'avi', 'mov'],
                                            'pdf'   => ['pdf'],
                                            'doc'   => ['doc', 'docx'],
                                            'ppt'   => ['ppt', 'pptx'],
                                            'xls'   => ['xls', 'xlsx'],
                                        ];
                                        $fileType = 'other';
                                        foreach ($fileIcons as $type => $extensions) {
                                            if (in_array(strtolower($extension), $extensions)) {
                                                $fileType = $type;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if ($fileType === 'image')
                                        <div class="{{ $iconClass }} bg-green-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @elseif ($fileType === 'video')
                                        <div class="{{ $iconClass }} bg-red-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </div>
                                    @elseif ($fileType === 'pdf')
                                        <div class="{{ $iconClass }} bg-red-600">PDF</div>
                                    @elseif ($fileType === 'doc')
                                        <div class="{{ $iconClass }} bg-blue-600">DOC</div>
                                    @elseif ($fileType === 'ppt')
                                        <div class="{{ $iconClass }} bg-orange-600">PPT</div>
                                    @elseif ($fileType === 'xls')
                                        <div class="{{ $iconClass }} bg-green-600">XLS</div>
                                    @else
                                        <div class="{{ $iconClass }} bg-gray-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info Dokumen --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate" title="{{ $document->title }}">
                                        {{ $document->title }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Diupload {{ $document->created_at->diffForHumans() }}
                                    </p>
                                    @if (Storage::exists($document->file_path))
                                        <p class="text-xs text-gray-500">
                                            {{ strtoupper($extension) }} &middot; {{ number_format(Storage::size($document->file_path) / 1024, 1) }} KB
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- ======================= PERUBAHAN DI SINI ======================= --}}
                            {{-- Tombol Aksi --}}
                            <div class="flex items-center space-x-1 ml-4">
                                {{-- Tombol Lihat --}}
                                <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                   class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150"
                                   title="Lihat File">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- Tombol Download --}}
                                <a href="{{ Storage::url($document->file_path) }}" download
                                   class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-150"
                                   title="Unduh File">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.documents.destroy', $document) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus {{ $document->title }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                             {{-- ===================== AKHIR DARI PERUBAHAN ===================== --}}
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-gray-500 text-sm mb-2">Belum ada lampiran yang diunggah</p>
                        <p class="text-gray-400 text-xs">Gunakan form di sebelah kanan untuk menambahkan lampiran.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Kolom Sidebar (Form Unggah) --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <h3 class="text-lg font-semibold text-white">Unggah Lampiran Baru</h3>
                </div>
            </div>
            <form action="{{ route('admin.events.documents.store', $event) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="document_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Lampiran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="document_title" required value="{{ old('title') }}"
                               class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Contoh: Materi Presentasi Hari 1">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <x-bladewind::filepicker name="document_file" id="document_file" label="Pilih File"
                                                 required="true" max_file_size="10mb" />
                    </div>
                    <div class="flex justify-end pt-4">
                        <x-bladewind::button can_submit="true" color="indigo" icon="cloud-upload" has_spinner="true">
                            Unggah File
                        </x-bladewind::button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Panduan Upload --}}
        <div class="bg-blue-50 rounded-2xl border border-blue-200 p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Panduan Upload</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Format: PDF, DOC, PPT, XLS, Gambar, Video</li>
                            <li>Ukuran maksimal per file: 10MB</li>
                            <li>Gunakan nama file yang deskriptif</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>