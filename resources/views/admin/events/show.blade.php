<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h2>
                <p class="text-sm text-gray-600 mt-1">Dibuat oleh {{ $event->creator->full_name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @if ($event->status == 'Terjadwal')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                        {{ $event->status }}
                    </span>
                @elseif($event->status == 'Berlangsung')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                        {{ $event->status }}
                    </span>
                @elseif($event->status == 'Selesai')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                        {{ $event->status }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                        {{ $event->status }}
                    </span>
                @endif

                <x-bladewind::button tag="a" href="{{ route('admin.events.qrcode', $event) }}" color="green"
                    size="small" icon="qr-code">
                    <span class="hidden lg:inline">QR Code</span>
                </x-bladewind::button>

                <x-bladewind::button tag="a" href="{{ route('admin.events.edit', $event) }}" color="indigo"
                    size="small" icon="pencil">
                    <span class="hidden lg:inline">Edit</span>
                </x-bladewind::button>

            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="mb-6">
                    <x-bladewind::alert type="success" class="shadow-sm">
                        {{ session('success') }}
                    </x-bladewind::alert>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6">
                    <x-bladewind::alert type="error" class="shadow-sm">
                        {{ session('error') }}
                    </x-bladewind::alert>
                </div>
            @endif
            @if ($errors->any() && !$errors->hasBag('external'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Event Info Cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-blue-100 text-sm">Total Peserta</p>
                            <p class="text-2xl font-bold">{{ $event->participants->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-green-100 text-sm">Sudah Hadir</p>
                            <p class="text-2xl font-bold">{{ $event->attendances->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-purple-100 text-sm">Dokumen</p>
                            <p class="text-2xl font-bold">
                                {{ $event->documents->where('type', '!=', 'Notulensi')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-orange-100 text-sm">Waktu Tersisa</p>
                            <p class="text-lg font-bold">{{ $event->countdown_status }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Tabs --}}
            <div class="bg-white rounded-xl shadow-sm mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'detail']) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab == 'detail' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Detail Acara</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'peserta']) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab == 'peserta' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <span>Peserta ({{ $event->participants->count() }})</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'lampiran']) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab == 'lampiran' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span>Lampiran
                                    ({{ $event->documents->where('type', '!=', 'Notulensi')->count() }})</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'notulensi']) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab == 'notulensi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Notulensi</span>
                            </div>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Tab Content --}}
            @if ($activeTab == 'detail')
                @include('admin.events.tabs.detail', ['event' => $event])
            @elseif ($activeTab == 'peserta')
                @include('admin.events.tabs.peserta', [
                    'event' => $event,
                    'potentialParticipants' => $potentialParticipants,
                    'errors' => $errors,
                ])
            @elseif ($activeTab == 'lampiran')
                @include('admin.events.tabs.lampiran', ['event' => $event])
            @elseif ($activeTab == 'notulensi')
                @include('admin.events.tabs.notulensi', ['event' => $event])
            @endif

            {{-- Back Button --}}
            <div class="mt-8 flex justify-between items-center">
                <x-bladewind::button tag="a" href="{{ route('admin.events.index') }}" color="gray"
                    icon="arrow-left">
                    Kembali ke Daftar Event
                </x-bladewind::button>

                <div class="flex space-x-3">
                    <x-bladewind::button tag="a" href="{{ route('admin.events.qrcode', $event) }}"
                        color="green" icon="qr-code">
                        Tampilkan QR Code
                    </x-bladewind::button>
                    <x-bladewind::button tag="a" href="{{ route('admin.events.edit', $event) }}"
                        color="indigo" icon="pencil">
                        Edit Event
                    </x-bladewind::button>
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
