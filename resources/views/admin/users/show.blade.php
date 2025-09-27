<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-4">
                {{-- Avatar --}}
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white shadow-lg">
                    <span class="font-bold text-3xl">
                        {{ substr($user->full_name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->full_name }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $user->email }}</p>
                    @if ($user->position)
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $user->position }}
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Kolom Sidebar (Info & Aksi) --}}
                <div class="space-y-6">
                    {{-- Profile Information --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                        <div class="p-6 border-b border-gray-100 flex items-center space-x-4">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4 text-sm">
                            <div>
                                <p class="text-gray-500">Divisi</p>
                                <p class="font-medium text-gray-800">{{ $user->division ?: '-' }}</p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <p class="text-gray-500">Institusi</p>
                                <p class="font-medium text-gray-800">{{ $user->institution ?: '-' }}</p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <p class="text-gray-500">Nomor Telepon</p>
                                <p class="font-medium text-gray-800">{{ $user->phone_number ?: '-' }}</p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <p class="text-gray-500">Bergabung pada</p>
                                <p class="font-medium text-gray-800">{{ $user->created_at->isoFormat('D MMMM YYYY') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                        <div class="p-6 border-b border-gray-100 flex items-center space-x-4">
                             <div class="p-2 bg-emerald-100 rounded-lg">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.users.edit', $user) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-indigo-600 hover:to-indigo-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Edit Pengguna
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-red-600 hover:to-red-700 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Hapus Pengguna
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Kolom Konten Utama (Event & Statistik) --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Statistik Partisipasi --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Event Diikuti</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $user->participatedEvents->count() }}</p>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Kehadiran</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $user->attendances->count() }}</p>
                            </div>
                            <div class="p-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Partisipasi Event --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Riwayat Partisipasi Event</h3>
                        </div>
                        <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                            @forelse ($user->participatedEvents->sortByDesc('start_time') as $event)
                                <div class="p-4 hover:bg-yellow-50/50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.events.show', $event) }}" class="block group">
                                                <p class="font-medium text-gray-900 group-hover:text-indigo-600 truncate transition-colors">{{ $event->title }}</p>
                                                <p class="text-sm text-gray-500 mt-1">{{ $event->start_time->isoFormat('dddd, D MMMM YYYY') }}</p>
                                            </a>
                                        </div>
                                        <div class="ml-4">
                                            @php
                                                $attendance = $user->attendances->where('event_id', $event->id)->first();
                                            @endphp
                                            @if ($attendance)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Hadir</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Absen</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    <p>Pengguna ini belum pernah mengikuti event apapun.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>