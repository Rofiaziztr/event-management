<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold text-2xl">
                        {{ substr($user->full_name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->full_name }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            

            {{-- Main Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Section - Profile Info + Quick Actions --}}
                <div class="space-y-6">
                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <x-bladewind::button tag="a" href="{{ route('admin.users.edit', $user) }}"
                                color="indigo" size="small" class="w-full inline-flex items-center text-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                                Edit Pengguna
                            </x-bladewind::button>

                            @if ($user->participatedEvents->count() > 0)
                                <button onclick="exportUserData()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export Data Pengguna
                                </button>
                            @endif

                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')"
                                class="w-full">
                                @csrf
                                @method('DELETE')
                                <x-bladewind::button can_submit="true" color="red" size="small" class="w-full"
                                    icon="trash">
                                    Hapus Pengguna
                                </x-bladewind::button>
                            </form>
                        </div>
                    </div>

                    {{-- Profile Information --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-white">Informasi Profil</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->full_name }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->email }}</p>
                            </div>

                            @if ($user->position)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Posisi/Jabatan</label>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->position }}</p>
                                </div>
                            @endif

                            @if ($user->division)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Divisi</label>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->division }}</p>
                                </div>
                            @endif

                            @if ($user->phone_number)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nomor Telepon</label>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->phone_number }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">
                                    {{ $user->created_at->format('d M Y, H:i') }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Terakhir Diperbarui</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">
                                    {{ $user->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Section - Events & Attendances --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Event Participation --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="text-lg font-semibold text-white">Event yang Diikuti</h3>
                                </div>
                                <span
                                    class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $user->participatedEvents->count() }}
                                </span>
                            </div>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse ($user->participatedEvents->sortByDesc('start_time') as $event)
                                <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.events.show', $event) }}"
                                                class="block hover:text-blue-600 transition-colors">
                                                <p class="font-medium text-gray-900 truncate">{{ $event->title }}</p>
                                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>{{ $event->start_time->format('d M Y, H:i') }}</span>
                                                </div>
                                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    </svg>
                                                    <span>{{ $event->location }}</span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="ml-4 flex flex-col items-end space-y-2">
                                            {{-- Event Status --}}
                                            @php $dynamicStatus = $event->status; @endphp
                                            @if ($dynamicStatus === 'Terjadwal')
                                                <x-bladewind::tag label="{{ $dynamicStatus }}" color="blue"
                                                    size="sm" />
                                            @elseif($dynamicStatus === 'Berlangsung')
                                                <x-bladewind::tag label="{{ $dynamicStatus }}" color="green"
                                                    size="sm" />
                                            @elseif($dynamicStatus === 'Selesai')
                                                <x-bladewind::tag label="{{ $dynamicStatus }}" color="gray"
                                                    size="sm" />
                                            @else
                                                <x-bladewind::tag label="{{ $dynamicStatus }}" color="red"
                                                    size="sm" />
                                            @endif

                                            {{-- Attendance Status --}}
                                            @php
                                                $attendance = $user->attendances
                                                    ->where('event_id', $event->id)
                                                    ->first();
                                            @endphp
                                            @if ($attendance)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Hadir
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm4.93-11.07a.75.75 0 10-1.06-1.06L10 9.94 6.13 6.07a.75.75 0 00-1.06 1.06L8.94 10.94a1.5 1.5 0 002.12 0l3.87-3.87z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Absen
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Belum mengikuti event apapun</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Attendance History --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="text-lg font-semibold text-white">Riwayat Kehadiran</h3>
                                </div>
                                <span
                                    class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $user->attendances->count() }}
                                </span>
                            </div>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse ($user->attendances->sortByDesc('check_in_time') as $attendance)
                                <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.events.show', $attendance->event) }}"
                                                class="block hover:text-green-600 transition-colors">
                                                <p class="font-medium text-gray-900 truncate">
                                                    {{ $attendance->event->title }}</p>
                                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Check-in:
                                                        {{ $attendance->check_in_time->format('d M Y, H:i') }}</span>
                                                </div>
                                                @if ($attendance->check_out_time)
                                                    <div class="flex items-center mt-1 text-sm text-gray-500">
                                                        <svg class="w-4 h-4 mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1" />
                                                        </svg>
                                                        <span>Check-out:
                                                            {{ $attendance->check_out_time->format('d M Y, H:i') }}</span>
                                                    </div>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="ml-4">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Hadir
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Belum ada riwayat kehadiran</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-8 flex justify-center">
            <x-bladewind::button tag="a" href="{{ route('admin.users.index') }}" color="gray"
                icon="arrow-left">
                Kembali ke Daftar Pengguna
            </x-bladewind::button>
        </div>
    </div>

    @push('scripts')
        <script>
            function exportUserData() {
                alert('Fitur export data pengguna sedang dalam pengembangan');
            }
        </script>
    @endpush
</x-app-layout>