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
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md"
                            role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

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
                                <div class="mb-3">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
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
                                <div class="mb-3">
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

                    <div class="mt-8 border-t pt-6 space-y-6">
                        <div>
                            <h4 class="font-bold text-lg mb-4">Dokumen & Notulensi</h4>
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Kolom Dokumen --}}
                                <div class="space-y-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h5 class="font-semibold mb-3">Unggah Dokumen Baru</h5>
                                        <form action="{{ route('admin.events.documents.store', $event) }}"
                                            method="POST" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label for="title"
                                                    class="block text-sm font-medium text-gray-700">Judul
                                                    Dokumen</label>
                                                <input type="text" name="title" id="title"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                                    required>
                                            </div>
                                            <div>
                                                <label for="type"
                                                    class="block text-sm font-medium text-gray-700">Tipe</label>
                                                <select name="type" id="type"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                    <option value="Materi">Materi</option>
                                                    <option value="Foto">Foto</option>
                                                    <option value="Video">Video</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="document_file"
                                                    class="block text-sm font-medium text-gray-700">Pilih File</label>
                                                <input type="file" name="document_file" id="document_file"
                                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                                    required>
                                            </div>
                                            <div class="text-right">
                                                <button type="submit"
                                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                    Unggah
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold mb-2">Dokumen Terunggah</h5>
                                        <ul class="divide-y divide-gray-200 border rounded-md">
                                            @forelse ($event->documents->where('type', '!=', 'Notulensi') as $document)
                                                <li class="p-3 flex items-center justify-between">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $document->title }}</p>
                                                        <p class="text-sm text-gray-500">Tipe: {{ $document->type }}
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <a href="{{ Storage::url($document->file_path) }}"
                                                            target="_blank"
                                                            class="text-indigo-600 hover:text-indigo-900 text-sm">Lihat</a>
                                                        <form
                                                            action="{{ route('admin.documents.destroy', $document) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-600 hover:text-red-900 text-sm"
                                                                onclick="return confirm('Yakin ingin menghapus dokumen ini?')">Hapus</button>
                                                        </form>
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="p-3 text-sm text-gray-500">Belum ada dokumen yang diunggah.
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                                {{-- Kolom Notulensi --}}
                                <div>
                                    <h5 class="font-semibold mb-2">Notulensi Rapat</h5>
                                    <form action="{{ route('admin.events.notulensi.store', $event) }}" method="POST">
                                        @csrf
                                        <div>
                                            <textarea name="content" id="content" rows="15" class="block w-full border-gray-300 rounded-md shadow-sm"
                                                placeholder="Tulis notulensi rapat di sini...">{{ optional($event->documents->where('type', 'Notulensi')->first())->content }}</textarea>
                                        </div>
                                        <div class="text-right mt-4">
                                            <button type="submit"
                                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                Simpan Notulensi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t pt-4 flex justify-end items-center gap-3">
                        <a href="{{ route('admin.events.index') }}"
                            class="text-gray-600 hover:text-gray-900 font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
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
