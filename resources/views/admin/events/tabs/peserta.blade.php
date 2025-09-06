<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    {{-- Left Section - Add Participants Forms --}}
    <div class="xl:col-span-2 space-y-6">
        {{-- Individual Internal Participants --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-5-2a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Undang Peserta Internal</h3>
                </div>
                <p class="text-blue-100 text-sm mt-1">{{ $potentialParticipants->count() }} calon peserta tersedia</p>
            </div>

            <form action="{{ route('admin.events.participants.store', $event) }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="user_ids" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Peserta Internal
                        </label>
                        <div class="relative">
                            <select name="user_ids[]" id="user_ids" multiple required
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                size="8">
                                @forelse ($potentialParticipants as $user)
                                    <option value="{{ $user->id }}" class="py-2 px-3 hover:bg-blue-50">
                                        {{ $user->full_name }} ({{ $user->nip }})
                                        @if($user->division)
                                            - {{ $user->division }}
                                        @endif
                                    </option>
                                @empty
                                    <option disabled class="py-2 px-3 text-gray-500">Tidak ada calon peserta internal.</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mt-2 flex items-center text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tahan <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Ctrl</kbd>
                            atau <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Cmd</kbd>
                            untuk memilih multiple
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <x-bladewind::button can_submit="true" color="blue" icon="plus">
                            Undang Peserta Terpilih
                        </x-bladewind::button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Bulk Invite --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Undang Peserta Massal</h3>
                </div>
                <p class="text-green-100 text-sm mt-1">Undang berdasarkan divisi atau semua sekaligus</p>
            </div>

            <form id="bulk-invite-form" action="{{ route('admin.events.participants.store.bulk', $event) }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="invite_method" id="invite_method_input">
                
                <div class="space-y-4">
                    <div>
                        <label for="division" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Divisi (Opsional)
                        </label>
                        <select name="division" id="division" 
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Pilih Divisi Tertentu --</option>
                            @php $divisions = $potentialParticipants->pluck('division')->unique()->filter()->sort(); @endphp
                            @foreach ($divisions as $division)
                                <option value="{{ $division }}">{{ $division }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <x-bladewind::button type="button" color="green" size="small" 
                            onclick="submitBulkInvite('division')" icon="user-group">
                            Undang Per Divisi
                        </x-bladewind::button>
                        <x-bladewind::button type="button" color="yellow" size="small" 
                            onclick="submitBulkInvite('all')" icon="users">
                            Undang Semua Internal
                        </x-bladewind::button>
                    </div>
                </div>
            </form>
        </div>

        {{-- External Participants --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Undang Peserta Eksternal</h3>
                </div>
                <p class="text-purple-100 text-sm mt-1">Tambahkan peserta dari luar organisasi</p>
            </div>

            <form action="{{ route('admin.events.participants.store.external', $event) }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="full_name" id="full_name" required 
                            value="{{ old('full_name') }}"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required 
                            value="{{ old('email') }}"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500"
                            placeholder="nama@email.com">
                    </div>

                    <div>
                        <label for="institution" class="block text-sm font-medium text-gray-700 mb-1">
                            Instansi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="institution" id="institution" required 
                            value="{{ old('institution') }}"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Nama instansi/perusahaan">
                    </div>

                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">
                            Posisi/Jabatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="position" id="position" required 
                            value="{{ old('position') }}"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Jabatan/posisi">
                    </div>

                    <div class="md:col-span-2">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                            No. Telepon
                        </label>
                        <input type="text" name="phone_number" id="phone_number" 
                            value="{{ old('phone_number') }}"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500"
                            placeholder="08123456789 (opsional)">
                    </div>
                </div>

                @if ($errors->external->any())
                    <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Error pada form eksternal:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->external->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end pt-6">
                    <x-bladewind::button can_submit="true" color="purple" icon="plus">
                        Undang Peserta Eksternal
                    </x-bladewind::button>
                </div>
            </form>
        </div>
    </div>

    {{-- Right Section - Participants List --}}
    <div class="xl:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Daftar Peserta</h3>
                    </div>
                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        {{ $event->participants->count() }}
                    </span>
                </div>
            </div>

            <div class="max-h-[600px] overflow-y-auto">
                @forelse ($event->participants->sortBy('full_name') as $index => $participant)
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3 flex-1 min-w-0">
                                {{-- Avatar --}}
                                <div class="flex-shrink-0">
                                    @if($participant->nip)
                                        {{-- Internal participant --}}
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-semibold text-sm">
                                                {{ substr($participant->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                    @else
                                        {{-- External participant --}}
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <span class="text-purple-600 font-semibold text-sm">
                                                {{ substr($participant->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate" title="{{ $participant->full_name }}">
                                        {{ $participant->full_name }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate" title="{{ $participant->email }}">
                                        {{ $participant->email }}
                                    </p>
                                    
                                    @if ($participant->nip)
                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Internal
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">NIP: {{ $participant->nip }}</p>
                                        @if($participant->division)
                                            <p class="text-xs text-gray-600">{{ $participant->division }}</p>
                                        @endif
                                    @else
                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                                Eksternal
                                            </span>
                                        </div>
                                        @if($participant->institution)
                                            <p class="text-xs text-gray-600 mt-1 truncate" title="{{ $participant->institution }}">
                                                {{ $participant->institution }}
                                            </p>
                                        @endif
                                        @if($participant->position)
                                            <p class="text-xs text-gray-600">{{ $participant->position }}</p>
                                        @endif
                                    @endif

                                    {{-- Attendance Status --}}
                                    @php
                                        $attendance = $participant->attendances->where('event_id', $event->id)->first();
                                    @endphp
                                    @if($attendance)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Sudah Hadir
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('d M, H:i') }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                Belum Hadir
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Delete Button --}}
                            <div class="flex-shrink-0 ml-2">
                                <form action="{{ route('admin.events.participants.destroy', [$event, $participant]) }}" method="POST" 
                                    onsubmit="return confirm('Yakin ingin menghapus {{ $participant->full_name }} dari event ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-gray-500 text-sm">Belum ada peserta yang diundang</p>
                        <p class="text-gray-400 text-xs mt-1">Mulai undang peserta menggunakan form di sebelah kiri</p>
                    </div>
                @endforelse
            </div>

            @if($event->participants->count() > 0)
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="text-center">
                        <button onclick="exportAllParticipants()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Daftar Peserta
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportAllParticipants() {
    // Implement export functionality
    alert('Fitur export daftar peserta sedang dalam pengembangan');
}
</script>
@endpush