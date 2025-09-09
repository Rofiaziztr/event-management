<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard Peserta') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Selamat datang, {{ auth()->user()->full_name }}</p>
            </div>
            <x-bladewind::button tag="a" href="{{ route('scan.index') }}" size="small" color="green">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M9 16h4.01"/>
                </svg>
                Scan Presensi
            </x-bladewind::button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Messages --}}
            @if (session('status'))
                <div class="mb-6">
                    <x-bladewind::alert type="success" class="shadow-sm">
                        {{ session('status') }}
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-green-100 text-sm">Presensi Saya</p>
                            <p class="text-2xl font-bold">{{ $myAttendance ?? 0 }}</p>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-orange-100 text-sm">Tingkat Kehadiran</p>
                            <p class="text-2xl font-bold">{{ $attendanceRate ?? '0' }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Cards for Ongoing Events --}}
            @if(isset($ongoingEvents) && $ongoingEvents->count() > 0)
                <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-yellow-100 text-sm">Event Sedang Berlangsung</p>
                                <p class="text-xl font-bold">Ada {{ $ongoingEvents->count() }} event yang perlu presensi</p>
                                <p class="text-yellow-100 text-sm">Jangan lupa melakukan presensi sekarang!</p>
                            </div>
                        </div>
                        <x-bladewind::button tag="a" href="{{ route('scan.index') }}" 
                            color="white" size="small" class="bg-white bg-opacity-20 border-white text-white hover:bg-opacity-30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M9 16h4.01"/>
                            </svg>
                            Presensi Sekarang
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
                                <x-bladewind::button tag="a" href="{{ route('participant.events.index') }}" 
                                    color="white" size="tiny" class="bg-white bg-opacity-20 border-white text-white hover:bg-opacity-30">
                                    Lihat Semua
                                </x-bladewind::button>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            @forelse ($recentEvents ?? [] as $event)
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('participant.events.show', $event) }}" 
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
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    </svg>
                                                    <span>{{ $event->location }}</span>
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

                                            {{-- Attendance Status --}}
                                            @if (isset($attendedEventIds) && in_array($event->id, $attendedEventIds))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Hadir
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Belum
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Belum ada event terbaru</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Right Section - Quick Actions & Profile --}}
                <div class="space-y-6">
                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <x-bladewind::button tag="a" href="{{ route('participant.events.index') }}" 
                                color="blue" size="small" class="w-full" icon="calendar">
                                Lihat Semua Event
                            </x-bladewind::button>
                            
                            <x-bladewind::button tag="a" href="{{ route('scan.index') }}" 
                                color="green" size="small" class="w-full" icon="qr-code">
                                Scan Presensi
                            </x-bladewind::button>
                            
                            <x-bladewind::button tag="a" href="{{ route('profile.edit') }}" 
                                color="indigo" size="small" class="w-full" icon="user">
                                Edit Profil
                            </x-bladewind::button>
                        </div>
                    </div>

                    {{-- Profile Summary --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-violet-500 to-violet-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Profil Saya</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span class="text-violet-600 font-bold text-xl">
                                        {{ substr(auth()->user()->full_name, 0, 1) }}
                                    </span>
                                </div>
                                <h4 class="font-semibold text-gray-900">{{ auth()->user()->full_name }}</h4>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                            </div>

                            <hr class="border-gray-200">

                            <div class="space-y-2 text-sm">
                                @if(auth()->user()->nip)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">NIP:</span>
                                        <span class="font-medium">{{ auth()->user()->nip }}</span>
                                    </div>
                                @endif
                                @if(auth()->user()->division)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Divisi:</span>
                                        <span class="font-medium">{{ auth()->user()->division }}</span>
                                    </div>
                                @endif
                                @if(auth()->user()->position)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Posisi:</span>
                                        <span class="font-medium">{{ auth()->user()->position }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Bergabung:</span>
                                    <span class="font-medium">{{ auth()->user()->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Attendance Chart/Summary --}}
@if(isset($myAttendance) && $myAttendance > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Ringkasan Kehadiran</h3>
        </div>
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="text-3xl font-bold text-amber-600">{{ $attendanceRate ?? '0' }}%</div>
                <div class="text-sm text-gray-500">Tingkat Kehadiran</div>
                @if(isset($canceledEvents) && $canceledEvents > 0)
                    <p class="text-xs text-gray-400 mt-1">
                        *Tidak termasuk {{ $canceledEvents }} event yang dibatalkan
                    </p>
                @endif
            </div>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Total Event:</span>
                    <span class="font-medium">{{ $totalEvents ?? 0 }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Sudah Hadir:</span>
                    <span class="font-medium text-green-600">{{ $myAttendance ?? 0 }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Belum Hadir:</span>
                    <span class="font-medium text-red-600">{{ ($totalEvents ?? 0) - ($myAttendance ?? 0) }}</span>
                </div>
                @if(isset($canceledEvents) && $canceledEvents > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Event Dibatalkan:</span>
                        <span class="font-medium text-gray-600">{{ $canceledEvents }}</span>
                    </div>
                @endif
            </div>
            @if(($totalEvents ?? 0) > 0)
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ $attendanceRate ?? 0 }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>