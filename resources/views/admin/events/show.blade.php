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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
                    </div>

                    <div class="border-t pt-6 mb-8">
                        <h4 class="font-bold text-lg mb-4">Dokumen Acara</h4>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold mb-3">Unggah Dokumen Baru</h5>
                                <form action="{{ route('admin.events.documents.store', $event) }}" method="POST"
                                    enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="title"
                                            class="block text-sm font-medium text-gray-700">Judul</label>
                                        <x-bladewind::input name="title" required="true" />
                                    </div>
                                    <div>
                                        <label for="type"
                                            class="block text-sm font-medium text-gray-700">Tipe</label>
                                        <x-bladewind::select name="type" required="true" :data="[
                                            ['label' => 'Materi', 'value' => 'Materi'],
                                            ['label' => 'Foto', 'value' => 'Foto'],
                                            ['label' => 'Video', 'value' => 'Video'],
                                        ]" />
                                    </div>
                                    <div>
                                        <label for="document_file" class="block text-sm font-medium text-gray-700">Pilih
                                            File</label>
                                        <x-bladewind::filepicker name="document_file" required="true" />
                                    </div>
                                    <div class="text-right">
                                        <x-bladewind::button size="small"
                                            can_submit="true">Unggah</x-bladewind::button>
                                    </div>
                                </form>
                            </div>
                            <div>
                                <h5 class="font-semibold mb-2">Dokumen Terunggah</h5>
                                <ul class="divide-y divide-gray-200 border rounded-md" id="documents-list">
                                    @forelse ($event->documents->where('type', '!=', 'Notulensi') as $document)
                                        <li class="p-3 flex items-center justify-between document-item"
                                            data-id="{{ $document->id }}">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $document->title }}</p>
                                                <p class="text-sm text-gray-500">Tipe: {{ $document->type }}</p>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <x-bladewind::button tag="a"
                                                    href="{{ Storage::url($document->file_path) }}" target="_blank"
                                                    size="tiny">Lihat</x-bladewind::button>
                                                <button type="button"
                                                    class="bw-button bg-red-600 text-white !px-3 !py-2 rounded text-sm delete-document"
                                                    data-id="{{ $document->id }}" data-title="{{ $document->title }}">
                                                    Hapus
                                                </button>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="p-3 text-sm text-gray-500">Belum ada dokumen yang diunggah.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <h4 class="font-bold text-lg mb-4">Notulensi Rapat</h4>
                        <form action="{{ route('admin.events.notulensi.store', $event) }}" method="POST">
                            @csrf
                            <div>
                                <x-bladewind::textarea name="content" toolbar="simple" rows="15"
                                    :selected_value="optional($event->documents->firstWhere('type', 'Notulensi'))->content ??
                                        ''" />
                            </div>
                            <div class="text-right mt-4">
                                <x-bladewind::button can_submit="true" color="green">Simpan
                                    Notulensi</x-bladewind::button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-8 border-t pt-4 flex justify-end items-center gap-3">
                        <x-bladewind::button tag="a" href="{{ route('admin.events.index') }}"
                            color="gray">Kembali</x-bladewind::button>
                        <x-bladewind::button tag="a" href="{{ route('admin.events.qrcode', $event) }}"
                            color="green">Tampilkan QR Code</x-bladewind::button>
                        <x-bladewind::button tag="a" href="{{ route('admin.events.edit', $event) }}"
                            color="indigo">Edit Event</x-bladewind::button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-confirm-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-96">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>
            <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus dokumen "<span
                    id="document-title"></span>"?</p>
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancel-delete" class="bw-button bg-gray-300 text-gray-700">Batal</button>
                <form id="delete-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bw-button bg-red-600 text-white">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tangani klik tombol hapus
            document.querySelectorAll('.delete-document').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const documentId = this.getAttribute('data-id');
                    const documentTitle = this.getAttribute('data-title');

                    // Set data untuk modal konfirmasi
                    document.getElementById('document-title').textContent = documentTitle;
                    document.getElementById('delete-form').action =
                    `/admin/documents/${documentId}`;

                    // Tampilkan modal
                    document.getElementById('delete-confirm-modal').classList.remove('hidden');
                });
            });

            // Tangani klik tombol batal
            document.getElementById('cancel-delete').addEventListener('click', function() {
                document.getElementById('delete-confirm-modal').classList.add('hidden');
            });

            // Tangani submit form hapus
            document.getElementById('delete-form').addEventListener('submit', function(e) {
                // Tidak perlu mencegah default, biarkan form submit normal
                // Sembunyikan modal setelah submit
                document.getElementById('delete-confirm-modal').classList.add('hidden');
            });

            // Tutup modal jika klik di luar area modal
            document.getElementById('delete-confirm-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    document.getElementById('delete-confirm-modal').classList.add('hidden');
                }
            });
        });
    </script>

    <style>
        .bw-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
        }

        .bw-button:focus {
            outline: none;
            ring: 2px;
            ring-color: rgba(59, 130, 246, 0.5);
        }

        #delete-confirm-modal {
            transition: opacity 0.3s, visibility 0.3s;
        }
    </style>
</x-app-layout>
