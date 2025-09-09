<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Event Saya') }}
            </h2>
            <x-bladewind::button tag="a" href="{{ route('scan.index') }}" size="small" color="green">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M9 16h4.01"/>
                </svg>
                Scan Presensi
            </x-bladewind::button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 px-4 sm:px-0">
                    <x-bladewind::alert type="success">
                        {{ session('success') }}
                    </x-bladewind::alert>
                </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-blue-100 text-sm">Total Event</p>
                            <p class="text-2xl font-bold">{{ $events->total() ?? 0 }}</p>
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
                            <p class="text-green-100 text-sm">Sudah Hadir</p>
                            <p class="text-2xl font-bold">{{ count($attendedEventIds ?? []) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-yellow-100 text-sm">Belum Hadir</p>
                            <p class="text-2xl font-bold">{{ ($events->total() ?? 0) - count($attendedEventIds ?? []) }}</p>
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
                            <p class="text-2xl font-bold">{{ $events->where('status', 'Berlangsung')->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Events Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
                @forelse ($events as $event)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6 flex-grow">
                            {{-- Card Header --}}
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-semibold text-lg text-gray-900 leading-tight">
                                    <a href="{{ route('participant.events.show', $event) }}" class="hover:text-indigo-600 transition-colors">
                                        {{ $event->title }}
                                    </a>
                                </h3>
                                
                                {{-- Event Status --}}
                                @php
                                    $dynamicStatus = $event->status;
                                @endphp
                                @if ($dynamicStatus == 'Terjadwal')
                                    <x-bladewind::tag label="{{ $dynamicStatus }}" color="blue" />
                                @elseif($dynamicStatus == 'Berlangsung')
                                    <x-bladewind::tag label="{{ $dynamicStatus }}" color="green" />
                                @elseif($dynamicStatus == 'Selesai')
                                    <x-bladewind::tag label="{{ $dynamicStatus }}" color="gray" />
                                @else
                                    <x-bladewind::tag label="{{ $dynamicStatus }}" color="red" />
                                @endif
                            </div>

                            {{-- Card Body with Details and Icons --}}
                            <div class="space-y-3 mt-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M10.5 3v2.25M14.25 3v2.25M18 3v2.25M4.5 7.5h15M4.5 12h15m-7.5 4.5h.008v.008H12v-.008z" />
                                    </svg>
                                    <span>{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $event->countdown_status }}</span>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-3 text-gray-400 mt-1 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    <span>{{ $event->location }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>{{ $event->participants->count() }} peserta</span>
                                </div>
                            </div>

                            {{-- Attendance Status --}}
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                @if (in_array($event->id, $attendedEventIds ?? []))
                                    <div class="flex items-center text-green-600">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-medium">Anda sudah hadir</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-yellow-600">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-medium">Belum melakukan presensi</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer with Actions --}}
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <x-bladewind::button tag="a" href="{{ route('participant.events.show', $event) }}" 
                                    size="tiny" color="indigo" icon="eye">
                                    Detail Event
                                </x-bladewind::button>
                                
                                @if ($dynamicStatus === 'Berlangsung' && !in_array($event->id, $attendedEventIds ?? []))
                                    <x-bladewind::button tag="a" href="{{ route('scan.index') }}" 
                                        size="tiny" color="green" icon="qr-code">
                                        Presensi
                                    </x-bladewind::button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                        <x-bladewind::empty-state
                            message="Anda belum terdaftar di event mana pun."
                            button_label="Refresh Halaman"
                            button_action="window.location.reload()"
                        />
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>