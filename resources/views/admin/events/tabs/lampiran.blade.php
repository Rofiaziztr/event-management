<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Left Section - Upload Form --}}
    <div class="space-y-6">
        {{-- Upload New Document --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Unggah Lampiran Baru</h3>
                </div>
                <p class="text-emerald-100 text-sm mt-1">Tambahkan dokumen, foto, atau video untuk acara</p>
            </div>

            <form action="{{ route('admin.events.documents.store', $event) }}" method="POST"
                enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-6">
                    {{-- Document Title --}}
                    <div>
                        <label for="document_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Lampiran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="document_title" required value="{{ old('title') }}"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Masukkan judul lampiran...">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Document Type --}}
                    <div>
                        <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Lampiran <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="document_type" required
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">-- Pilih Tipe Lampiran --</option>
                            <option value="Materi" {{ old('type') == 'Materi' ? 'selected' : '' }}>
                                📄 Materi Presentasi
                            </option>
                            <option value="Foto" {{ old('type') == 'Foto' ? 'selected' : '' }}>
                                📷 Dokumentasi Foto
                            </option>
                            <option value="Video" {{ old('type') == 'Video' ? 'selected' : '' }}>
                                🎥 Video Recording
                            </option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- File Upload --}}
                    <div>
                        <x-bladewind::filepicker name="document_file" id="document_file" label="Pilih File"
                            required="true"
                            max_file_size="10mb" />
                    </div>

                    {{-- Upload Button --}}
                    <div class="flex justify-end pt-6">
                        <x-bladewind::button can_submit="true" color="green" icon="cloud-upload">
                            Unggah Lampiran
                        </x-bladewind::button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Upload Guidelines --}}
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Panduan Upload</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Materi:</strong> PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX</li>
                            <li><strong>Foto:</strong> JPG, JPEG, PNG, GIF (max 10MB)</li>
                            <li><strong>Video:</strong> MP4, AVI, MOV (max 10MB)</li>
                            <li>Gunakan nama file yang deskriptif</li>
                            <li>Pastikan file tidak mengandung informasi sensitif</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Section - Documents List --}}
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Lampiran Terunggah</h3>
                    </div>
                    @php
                        $documents = $event->documents->where('type', '!=', 'Notulensi');
                    @endphp
                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        {{ $documents->count() }}
                    </span>
                </div>
            </div>

            <div class="max-h-[600px] overflow-y-auto">
                @forelse ($documents as $index => $document)
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1 min-w-0">
                                {{-- File Type Icon --}}
                                <div class="flex-shrink-0">
                                    @php
                                        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                        $iconClass =
                                            'w-10 h-10 rounded-lg flex items-center justify-center text-white text-xs font-semibold';
                                    @endphp

                                    @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                        <div class="{{ $iconClass }} bg-green-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @elseif(in_array(strtolower($extension), ['mp4', 'avi', 'mov']))
                                        <div class="{{ $iconClass }} bg-red-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @elseif(strtolower($extension) === 'pdf')
                                        <div class="{{ $iconClass }} bg-red-600">
                                            PDF
                                        </div>
                                    @elseif(in_array(strtolower($extension), ['doc', 'docx']))
                                        <div class="{{ $iconClass }} bg-blue-600">
                                            DOC
                                        </div>
                                    @elseif(in_array(strtolower($extension), ['ppt', 'pptx']))
                                        <div class="{{ $iconClass }} bg-orange-600">
                                            PPT
                                        </div>
                                    @elseif(in_array(strtolower($extension), ['xls', 'xlsx']))
                                        <div class="{{ $iconClass }} bg-green-600">
                                            XLS
                                        </div>
                                    @else
                                        <div class="{{ $iconClass }} bg-gray-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Document Info --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate"
                                        title="{{ $document->title }}">
                                        {{ $document->title }}
                                    </h4>

                                    <div class="flex items-center mt-1 space-x-4">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $document->type === 'Materi'
                                                ? 'bg-blue-100 text-blue-800'
                                                : ($document->type === 'Foto'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-purple-100 text-purple-800') }}">
                                            @if ($document->type === 'Materi')
                                                📄
                                            @elseif($document->type === 'Foto')
                                                📷
                                            @else
                                                🎥
                                            @endif
                                            {{ $document->type }}
                                        </span>

                                        <span class="text-xs text-gray-500">
                                            {{ strtoupper($extension) }}
                                        </span>
                                    </div>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Diupload {{ $document->created_at->diffForHumans() }}
                                    </p>

                                    {{-- File Size (if available) --}}
                                    @if (Storage::exists($document->file_path))
                                        <p class="text-xs text-gray-500">
                                            {{ number_format(Storage::size($document->file_path) / 1024, 1) }} KB
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center space-x-2 ml-4">
                                {{-- Preview/Download Button --}}
                                <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                    class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-150"
                                    title="Lihat/Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- Delete Button --}}
                                <form action="{{ route('admin.documents.destroy', $document) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus {{ $document->title }}?')"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 text-sm mb-2">Belum ada lampiran yang diupload</p>
                        <p class="text-gray-400 text-xs">Upload dokumen pertama menggunakan form di sebelah kiri</p>
                    </div>
                @endforelse
            </div>

            {{-- Footer with bulk actions --}}
            @if ($documents->count() > 0)
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            Total {{ $documents->count() }} lampiran
                        </span>
                        <div class="flex space-x-3">
                            <button onclick="downloadAll()"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download All
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>


        function downloadAll() {
            alert('Fitur download all sedang dalam pengembangan');
            // Implement bulk download functionality here
        }
    </script>
@endpush
