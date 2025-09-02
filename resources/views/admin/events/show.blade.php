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
                        <x-bladewind::alert type="success" class="mb-6">
                            {{ session('success') }}
                        </x-bladewind::alert>
                    @endif

                    {{-- BAGIAN 1: INFO UTAMA --}}
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold border-b pb-2">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Dibuat oleh: {{ $event->creator->full_name }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        {{-- KOLOM KIRI: DESKRIPSI & DOKUMEN --}}
                        <div class="md:col-span-2 space-y-6">
                            <div>
                                <h4 class="font-bold text-lg mb-2">Deskripsi</h4>
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $event->description }}</p>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-2">Dokumen Acara</h4>
                                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h5 class="font-semibold mb-3">Unggah Dokumen Baru</h5>
                                        <form action="{{ route('admin.events.documents.store', $event) }}"
                                            method="POST" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Judul</label>
                                                <x-bladewind::input name="title" required="true" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Tipe</label>
                                                <x-bladewind::select name="type" required="true" :data="[
                                                    ['label' => 'Materi', 'value' => 'Materi'],
                                                    ['label' => 'Foto', 'value' => 'Foto'],
                                                    ['label' => 'Video', 'value' => 'Video'],
                                                ]" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Pilih
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
                                        <ul class="divide-y divide-gray-200 border rounded-md">
                                            @forelse ($event->documents->where('type', '!=', 'Notulensi') as $document)
                                                <li class="p-3 flex items-center justify-between">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $document->title }}</p>
                                                        <p class="text-sm text-gray-500">Tipe: {{ $document->type }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <x-bladewind::button tag="a"
                                                            href="{{ Storage::url($document->file_path) }}"
                                                            target="_blank" size="tiny" icon="eye" />
                                                        <form action="{{ route('admin.documents.destroy', $document) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-bladewind::button can_submit="true" type="submit" size="tiny"
                                                                color="red" icon="trash"
                                                                onclick="return confirm('Yakin ingin menghapus dokumen ini?')" />
                                                        </form>
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="p-3 text-sm text-gray-500">Belum ada dokumen.</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: INFORMASI ACARA --}}
                        <div class="bg-gray-50 p-4 rounded-lg self-start">
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
                                        @else<x-bladewind::tag label="{{ $event->status }}" color="red" />
                                        @endif
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

                    {{-- BAGIAN NOTULENSI (FULL WIDTH) --}}
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
</x-app-layout>
