<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard Admin') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Selamat datang, {{ auth()->user()->full_name }}</p>
            </div>
            <x-bladewind::button tag="a" href="{{ route('admin.events.create') }}" size="small" color="indigo">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Event Baru
            </x-bladewind::button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-6">
                    <x-bladewind::alert type="success" class="shadow-sm">
                        {{ session('success') }}
                    </x-bladewind::alert>
                </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-blue-100 text-sm">Total Event</p>
                            <p class="text-2xl font-bold">{{ $totalEvents ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-green-100 text-sm">Total Peserta</p>
                            <p class="text-2xl font-bold">{{ $totalParticipants ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-purple-100 text-sm">Event Aktif</p>
                            <p class="text-2xl font-bold">{{ $activeEvents ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-orange-100 text-sm">Total Presensi</p>
                            <p class="text-2xl font-bold">{{ $totalAttendances ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alert for Events Needing Attention --}}
            @if(isset($eventsNeedingAttention) && $eventsNeedingAttention->count() > 0)
                <div class="bg-gradient-to-r from-amber-400 to-amber-500 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-amber-100 text-sm">Perhatian Diperlukan</p>
                                <p class="text-xl font-bold">{{ $eventsNeedingAttention->count() }} event membutuhkan perhatian</p>
                                <p class="text-amber-100 text-sm">Event yang akan dimulai atau sedang berlangsung</p>
                            </div>
                        </div>
                        <x-bladewind::button tag="a" href="{{ route('admin.events.index') }}" 
                            color="white" size="small" class="bg-white bg-opacity-20 border-white text-white hover:bg-opacity-30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Detail
                        </x-bladewind::button>
                    </div>
                </div>
            @endif

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Section - Recent Events --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="text-lg font-semibold text-white">Event Terbaru</h3>
                                </div>
                                <x-bladewind::button tag="a" href="{{ route('admin.events.index') }}" 
                                    color="white" size="tiny" class="bg-white bg-opacity-20 border-white text-white hover:bg-opacity-30">
                                    Kelola Semua
                                </x-bladewind::button>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            @forelse ($recentEvents ?? [] as $event)
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.events.show', $event) }}" 
                                               class="block hover:text-indigo-600 transition-colors">
                                                <p class="font-medium text-gray-900 truncate">{{ $event->title }}</p>
                                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>{{ $event->start_time->format('d M Y, H:i') }}</span>
                                                </div>
                                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <span>{{ $event->participants->count() ?? 0 }} peserta</span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="ml-4 flex flex-col items-end space-y-2">
                                            {{-- Event Status --}}
                                            @php $dynamicStatus = $event->status; @endphp
                                            @if ($dynamicStatus === 'Terjadwal')
                                                <x-bladewind::tag label="{{ $dynamicStatus }}" color="blue" size="sm" />
                                            @elseif($dynamicStatus === 'Berlangsung')
                                                <x-bladewind::tag label="{{ $dynamicStatus }}" color="green" size="sm" />
                                            @elseif($dynamicStatus === 'Selesai')
                                                <x-bladewind::tag label="{{ $dynamicStatus }}" color="gray" size="sm" />
                                            @else
                                                <x-bladewind::tag label="{{ $dynamicStatus }}" color="red" size="sm" />
                                            @endif

                                            {{-- Attendance Rate --}}
                                            @php
                                                $totalParticipants = $event->participants->count();
                                                $totalAttendees = $event->attendances->count();
                                                $attendanceRate = $totalParticipants > 0 ? round(($totalAttendees / $totalParticipants) * 100) : 0;
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $attendanceRate >= 80 ? 'bg-green-100 text-green-800' : 
                                                   ($attendanceRate >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $attendanceRate }}% hadir
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm mb-2">Belum ada event yang dibuat</p>
                                    <x-bladewind::button tag="a" href="{{ route('admin.events.create') }}" 
                                        color="indigo" size="tiny">
                                        Buat Event Pertama
                                    </x-bladewind::button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Right Section - Quick Actions & System Info --}}
                <div class="space-y-6">
                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <x-bladewind::button tag="a" href="{{ route('admin.events.create') }}" 
                                color="indigo" size="small" class="w-full" icon="plus">
                                Buat Event Baru
                            </x-bladewind::button>
                            
                            <x-bladewind::button tag="a" href="{{ route('admin.events.index') }}" 
                                color="blue" size="small" class="w-full" icon="calendar">
                                Kelola Semua Event
                            </x-bladewind::button>
                            
                            <x-bladewind::button tag="a" href="{{ route('profile.edit') }}" 
                                color="purple" size="small" class="w-full" icon="user">
                                Edit Profil
                            </x-bladewind::button>
                        </div>
                    </div>

                    {{-- System Overview --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-violet-500 to-violet-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Ringkasan Sistem</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Event Bulan Ini:</span>
                                <span class="font-semibold text-gray-900">{{ $monthlyEvents ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Presensi Bulan Ini:</span>
                                <span class="font-semibold text-gray-900">{{ $monthlyAttendances ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Rata-rata Kehadiran:</span>
                                <span class="font-semibold text-gray-900">{{ $averageAttendanceRate ?? '0' }}%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Dokumen:</span>
                                <span class="font-semibold text-gray-900">{{ $totalDocuments ?? 0 }}</span>
                            </div>

                            <hr class="border-gray-200">

                            <div class="text-center">
                                <div class="text-2xl font-bold text-violet-600">{{ $systemHealth ?? '100' }}%</div>
                                <div class="text-sm text-gray-500">Kesehatan Sistem</div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Activity --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-500 to-slate-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Aktivitas Terbaru</h3>
                        </div>
                        <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                            @forelse ($recentActivities ?? [] as $activity)
                                <div class="p-3 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($activity['type'] === 'event_created')
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </div>
                                            @elseif($activity['type'] === 'attendance')
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center">
                                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">Belum ada aktivitas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>