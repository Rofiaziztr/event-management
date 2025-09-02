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

                    @if (session('error'))
                        <x-bladewind::alert type="error" class="mb-6">
                            {{ session('error') }}
                        </x-bladewind::alert>
                    @endif

                    {{-- BAGIAN 1: INFO UTAMA --}}
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold border-b pb-2">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Dibuat oleh: {{ $event->creator->full_name }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        {{-- KOLOM KIRI: DESKRIPSI & MANAJEMEN PESERTA --}}
                        <div class="md:col-span-2 space-y-6">
                            <div>
                                <h4 class="font-bold text-lg mb-2">Deskripsi</h4>
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $event->description }}</p>
                            </div>

                            <div class="border-t pt-6">
                                <h4 class="font-bold text-lg mb-4">Manajemen Peserta</h4>
                                <div class="bg-gray-50 p-4 rounded-lg space-y-6">

                                    @if ($errors->any())
                                        <div class="bg-red-100 p-4 mb-4 rounded">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <p>Calon peserta yang tersedia: {{ $potentialParticipants->count() }}</p>

                                    {{-- Form ini tidak berubah --}}
                                    <form action="{{ route('admin.events.participants.store', $event) }}" method="POST"
                                        class="space-y-4">
                                        @csrf
                                        <div>
                                            <h5 class="font-semibold mb-2 text-gray-800">Undang Peserta Pilihan</h5>
                                            <select name="user_ids[]" multiple required
                                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                size="8">
                                                @forelse ($potentialParticipants as $user)
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->full_name }} ({{ $user->nip }})
                                                    </option>
                                                @empty
                                                    <option disabled>Tidak ada calon peserta yang tersedia.</option>
                                                @endforelse
                                            </select>
                                            <p class="text-xs text-gray-500 mt-2">* Tahan <kbd
                                                    class="px-2 py-1.5 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Ctrl</kbd>
                                                atau <kbd
                                                    class="px-2 py-1.5 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Cmd</kbd>
                                                untuk memilih lebih dari satu.</p>
                                        </div>
                                        <div class="text-right">
                                            <x-bladewind::button size="small" can_submit="true">Undang
                                                Terpilih</x-bladewind::button>
                                        </div>
                                    </form>

                                    <hr class="border-gray-200" />

                                    {{-- Form Undang Massal --}}
                                    <form id="bulk-invite-form"
                                        action="{{ route('admin.events.participants.store.bulk', $event) }}"
                                        method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="invite_method" id="invite_method_input">
                                        <div>
                                            <h5 class="font-semibold mb-2 text-gray-800">Undang Peserta Massal</h5>
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-grow">
                                                    {{-- MENGGANTI BLADEWIND DENGAN SELECT HTML BIASA --}}
                                                    <select name="division" id="division"
                                                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                        <option value="">Pilih unit kerja/divisi...</option>
                                                        @php
                                                            // Ambil semua divisi unik yang tidak kosong dari calon peserta
                                                            $divisions = $potentialParticipants
                                                                ->pluck('division')
                                                                ->unique()
                                                                ->filter()
                                                                ->sort();
                                                        @endphp
                                                        @foreach ($divisions as $division)
                                                            <option value="{{ $division }}">{{ $division }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- Tombol-tombol ini tetap menggunakan Bladewind karena berfungsi baik --}}
                                                <x-bladewind::button type="button" size="small"
                                                    onclick="submitBulkInvite('division')">
                                                    Undang per Divisi
                                                </x-bladewind::button>
                                                <x-bladewind::button type="button" size="small" color="yellow"
                                                    onclick="submitBulkInvite('all')">
                                                    Undang Semua
                                                </x-bladewind::button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: INFORMASI & DAFTAR PESERTA --}}
                        <div class="space-y-6 self-start">
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
                                            {{ $event->start_time->format('l, d F Y - H:i') }} WIB
                                        </dd>
                                    </div>
                                    <div class="mb-3">
                                        <dt class="text-sm font-medium text-gray-500">Waktu Selesai</dt>
                                        <dd class="mt-1 font-semibold text-gray-900">
                                            {{ $event->end_time->format('l, d F Y - H:i') }} WIB
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                                        <dd class="mt-1 font-semibold text-gray-900">{{ $event->location }}</dd>
                                    </div>
                                </dl>
                            </div>

                            {{-- Daftar Peserta --}}
                            <div>
                                <h4 class="font-bold text-lg mb-2">Daftar Peserta ({{ $event->participants->count() }})
                                </h4>
                                <div class="border rounded-md max-h-96 overflow-y-auto bg-white">
                                    <ul class="divide-y divide-gray-200">
                                        @forelse ($event->participants->sortBy('full_name') as $participant)
                                            <li class="p-3 flex items-start justify-between hover:bg-gray-50">
                                                <div class="flex items-start space-x-3">
                                                    <div
                                                        class="bg-indigo-100 text-indigo-600 font-semibold rounded-full size-10 flex items-center justify-center flex-shrink-0">
                                                        {{ substr($participant->full_name, 0, 1) }}
                                                    </div>
                                                    <div class="flex-grow">
                                                        <p class="text-sm font-bold text-gray-900">
                                                            {{ $participant->full_name }}</p>
                                                        <p class="text-xs text-gray-500 truncate"
                                                            title="{{ $participant->email }}">
                                                            {{ $participant->email }}
                                                        </p>
                                                        <p class="text-xs text-gray-700 mt-1">NIP:
                                                            {{ $participant->nip }}</p>
                                                        <div class="mt-2 flex flex-wrap gap-1">
                                                            <x-bladewind::tag size="tiny"
                                                                label="{{ $participant->position }}" />
                                                            <x-bladewind::tag size="tiny" color="cyan"
                                                                label="{{ $participant->division }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <form
                                                    action="{{ route('admin.events.participants.destroy', [$event, $participant]) }}"
                                                    method="POST" class="pl-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-bladewind::button type="submit" size="tiny" color="red"
                                                        icon="trash" can_submit="true"
                                                        onclick="return confirm('Yakin ingin menghapus peserta ini dari event?')" />
                                                </form>
                                            </li>
                                        @empty
                                            <li class="p-4 text-sm text-gray-500 text-center">Belum ada peserta.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- DOKUMEN ACARA --}}
                        <div>
                            <h4 class="font-bold text-lg mb-2">Dokumen Acara</h4>
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <h5 class="font-semibold mb-3">Unggah Dokumen Baru</h5>
                                <form action="{{ route('admin.events.documents.store', $event) }}" method="POST"
                                    enctype="multipart/form-data" class="space-y-4">
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
                                        <label class="block text-sm font-medium text-gray-700">Pilih File</label>
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
                                <div class="border rounded-md max-h-60 overflow-y-auto">
                                    <ul class="divide-y divide-gray-200">
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
                                                        <x-bladewind::button type="submit" size="tiny"
                                                            color="red" icon="trash" can_submit="true"
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

                        {{-- BAGIAN NOTULENSI --}}
                        <div>
                            <h4 class="font-bold text-lg mb-2">Notulensi</h4>
                            <form action="{{ route('admin.events.notulensi.store', $event) }}" method="POST">
                                @csrf
                                <div>
                                    <x-bladewind::textarea name="content" toolbar="simple" rows="15"
                                        :selected_value="optional($event->documents->firstWhere('type', 'Notulensi'))
                                            ->content ?? ''" />
                                </div>
                                <div class="text-right mt-4">
                                    <x-bladewind::button can_submit="true" color="green">Simpan
                                        Notulensi</x-bladewind::button>
                                </div>
                            </form>
                        </div>
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

    @push('scripts')
        <script>
            function submitBulkInvite(method) {
                document.getElementById('invite_method_input').value = method;
                if (method === 'all') {
                    document.getElementById('division').value = '';
                }
                document.getElementById('bulk-invite-form').submit();
            }
        </script>
    @endpush

</x-app-layout>
