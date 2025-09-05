<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- Notifikasi --}}
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
                    @if ($errors->any() && !$errors->hasBag('external'))
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md"
                            role="alert">
                            <p class="font-bold">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 1. HEADER UTAMA --}}
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Dibuat oleh: {{ $event->creator->full_name }}</p>
                    </div>

                    {{-- 2. NAVIGASI TAB --}}
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                            <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'detail']) }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'detail' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Detail Acara
                            </a>
                            <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'peserta']) }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'peserta' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Manajemen Peserta
                            </a>
                            <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'lampiran']) }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'lampiran' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Lampiran
                            </a>
                            <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'notulensi']) }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'notulensi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Notulensi
                            </a>
                        </nav>
                    </div>

                    {{-- 3. KONTEN TAB --}}
                    <div class="pt-6">
                        @if ($activeTab == 'detail')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div class="md:col-span-2">
                                    <h4 class="font-bold text-lg mb-2">Deskripsi</h4>
                                    <p class="text-gray-700 whitespace-pre-wrap">
                                        {{ $event->description ?: 'Tidak ada deskripsi.' }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg self-start border">
                                    <h4 class="font-bold text-lg mb-3">Informasi Acara</h4>
                                    <dl class="space-y-4">
                                        <div>
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
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Waktu Mulai</dt>
                                            <dd class="mt-1 font-semibold text-gray-900">
                                                {{ $event->start_time->format('l, d F Y - H:i') }} WIB</dd>
                                        </div>
                                        <div>
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
                        @endif

                        @if ($activeTab == 'peserta')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div class="md:col-span-2 space-y-6">
                                    <div class="bg-gray-50 p-4 rounded-lg border space-y-6">
                                        <p class="text-sm text-gray-600">Calon peserta internal yang tersedia:
                                            {{ $potentialParticipants->count() }}</p>

                                        <form action="{{ route('admin.events.participants.store', $event) }}"
                                            method="POST" class="space-y-4">
                                            @csrf
                                            <div>
                                                <h5 class="font-semibold mb-2 text-gray-800">Undang Peserta Internal
                                                </h5>
                                                <select name="user_ids[]" multiple required
                                                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                    size="8">
                                                    @forelse ($potentialParticipants as $user)
                                                        <option value="{{ $user->id }}">{{ $user->full_name }}
                                                            ({{ $user->nip }})</option>
                                                    @empty
                                                        <option disabled>Tidak ada calon peserta internal.</option>
                                                    @endforelse
                                                </select>
                                                <p class="text-xs text-gray-500 mt-2">* Tahan <kbd
                                                        class="px-1.5 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Ctrl</kbd>
                                                    atau <kbd
                                                        class="px-1.5 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Cmd</kbd>
                                                    untuk memilih.</p>
                                            </div>
                                            <div class="text-right">
                                                <x-bladewind::button size="small" can_submit="true">Undang
                                                    Terpilih</x-bladewind::button>
                                            </div>
                                        </form>

                                        <hr class="border-gray-200" />

                                        <form id="bulk-invite-form"
                                            action="{{ route('admin.events.participants.store.bulk', $event) }}"
                                            method="POST" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="invite_method" id="invite_method_input">
                                            <div>
                                                <h5 class="font-semibold mb-2 text-gray-800">Undang Peserta Internal
                                                    Massal</h5>
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex-grow">
                                                        <select name="division" id="division"
                                                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                            <option value="">Pilih unit kerja/divisi...</option>
                                                            @php $divisions = $potentialParticipants->pluck('division')->unique()->filter()->sort(); @endphp
                                                            @foreach ($divisions as $division)
                                                                <option value="{{ $division }}">
                                                                    {{ $division }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <x-bladewind::button type="button" size="small"
                                                        onclick="submitBulkInvite('division')">Per
                                                        Divisi</x-bladewind::button>
                                                    <x-bladewind::button type="button" size="small" color="yellow"
                                                        onclick="submitBulkInvite('all')">Semua</x-bladewind::button>
                                                </div>
                                            </div>
                                        </form>

                                        <hr class="border-gray-200" />

                                        {{-- ======================================================= --}}
                                        {{--         FORM UNDANG EKSTERNAL DENGAN TOMBOL             --}}
                                        {{-- ======================================================= --}}
                                        <form action="{{ route('admin.events.participants.store.external', $event) }}"
                                            method="POST" class="space-y-4">
                                            @csrf
                                            <div>
                                                <h5 class="font-semibold mb-3 text-gray-800">Undang Peserta Eksternal
                                                </h5>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <x-bladewind::input name="full_name" label="Nama Lengkap"
                                                        required="true" value="{{ old('full_name') }}" />
                                                    <x-bladewind::input name="email" label="Alamat Email"
                                                        type="email" required="true"
                                                        value="{{ old('email') }}" />
                                                    <x-bladewind::input name="institution" label="Instansi"
                                                        required="true" value="{{ old('institution') }}" />
                                                    <x-bladewind::input name="position" label="Posisi/Jabatan"
                                                        required="true" value="{{ old('position') }}" />
                                                    <x-bladewind::input name="phone_number"
                                                        label="No. Telepon (Opsional)"
                                                        value="{{ old('phone_number') }}" />
                                                </div>
                                                @if ($errors->external->any())
                                                    <div class="mt-3 text-sm text-red-600">
                                                        <ul>
                                                            @foreach ($errors->external->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="submit"
                                                class="px-4 py-2 bg-teal-600 text-white rounded-md">
                                                Undang Eksternal
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="self-start">
                                    <h4 class="font-bold text-lg mb-2">Daftar Peserta
                                        ({{ $event->participants->count() }})</h4>
                                    <div class="border rounded-md max-h-[42rem] overflow-y-auto bg-white">
                                        <ul class="divide-y divide-gray-200">
                                            @forelse ($event->participants->sortBy('full_name') as $participant)
                                                <li class="p-3 flex items-center justify-between hover:bg-gray-50">
                                                    <div class="flex items-start space-x-3 min-w-0">
                                                        <div
                                                            class="bg-indigo-100 text-indigo-600 font-semibold rounded-full size-10 flex items-center justify-center flex-shrink-0">
                                                            {{ substr($participant->full_name, 0, 1) }}</div>
                                                        <div class="flex-grow min-w-0">
                                                            <p class="text-sm font-bold text-gray-900 truncate"
                                                                title="{{ $participant->full_name }}">
                                                                {{ $participant->full_name }}</p>
                                                            <p class="text-xs text-gray-500 truncate"
                                                                title="{{ $participant->email }}">
                                                                {{ $participant->email }}</p>
                                                            @if ($participant->nip)
                                                                <p class="text-xs text-gray-700 mt-1">NIP:
                                                                    {{ $participant->nip }}</p>
                                                            @elseif($participant->institution)
                                                                <p class="text-xs text-gray-700 mt-1 truncate"
                                                                    title="{{ $participant->institution }}">Instansi:
                                                                    {{ $participant->institution }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <form
                                                        action="{{ route('admin.events.participants.destroy', [$event, $participant]) }}"
                                                        method="POST" class="pl-2 flex-shrink-0">
                                                        @csrf @method('DELETE')
                                                        <x-bladewind::button type="submit" size="tiny"
                                                            color="red" icon="trash" can_submit="true"
                                                            onclick="return confirm('Yakin ingin menghapus peserta ini dari event?')" />
                                                    </form>
                                                </li>
                                            @empty
                                                <li class="p-4 text-sm text-gray-500 text-center">Belum ada peserta.
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($activeTab == 'lampiran')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="bg-gray-50 p-4 rounded-lg border">
                                    <h5 class="font-semibold mb-3">Unggah Lampiran Baru</h5>
                                    <form action="{{ route('admin.events.documents.store', $event) }}" method="POST"
                                        enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div><x-bladewind::input name="title" label="Judul Lampiran"
                                                required="true" /></div>
                                        <div><x-bladewind::select name="type" label="Tipe Lampiran"
                                                required="true" :data="[
                                                    ['label' => 'Materi', 'value' => 'Materi'],
                                                    ['label' => 'Foto', 'value' => 'Foto'],
                                                    ['label' => 'Video', 'value' => 'Video'],
                                                ]" /></div>
                                        <div><x-bladewind::filepicker name="document_file" label="Pilih File"
                                                required="true" /></div>
                                        <div class="text-right"><x-bladewind::button size="small"
                                                can_submit="true">Unggah</x-bladewind::button></div>
                                    </form>
                                </div>
                                <div>
                                    <h5 class="font-semibold mb-2">Lampiran Terunggah</h5>
                                    <div class="border rounded-md max-h-96 overflow-y-auto">
                                        <ul class="divide-y divide-gray-200">
                                            @forelse ($event->documents->where('type', '!=', 'Notulensi') as $document)
                                                <li class="p-3 flex items-center justify-between">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $document->title }}</p>
                                                        <p class="text-sm text-gray-500">Tipe: {{ $document->type }}
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <x-bladewind::button tag="a"
                                                            href="{{ Storage::url($document->file_path) }}"
                                                            target="_blank" size="tiny" icon="eye" />
                                                        <form
                                                            action="{{ route('admin.documents.destroy', $document) }}"
                                                            method="POST">
                                                            @csrf @method('DELETE')
                                                            <x-bladewind::button type="submit" size="tiny"
                                                                color="red" icon="trash" can_submit="true"
                                                                onclick="return confirm('Yakin ingin menghapus dokumen ini?')" />
                                                        </form>
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="p-3 text-sm text-gray-500 text-center">Belum ada lampiran.
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($activeTab == 'notulensi')
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
                        @endif
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
                    const divisionSelect = document.getElementById('division');
                    if (divisionSelect) divisionSelect.value = '';
                }
                document.getElementById('bulk-invite-form').submit();
            }
        </script>
    @endpush
</x-app-layout>
