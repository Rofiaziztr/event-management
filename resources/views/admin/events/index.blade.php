<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Event') }}
            </h2>
            <x-bladewind::button tag="a" href="{{ route('admin.events.create') }}" size="small">
                + Buat Event Baru
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
                @forelse ($events as $event)
                    <a href="{{ route('admin.events.show', $event) }}" class="block">
                        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden flex flex-col relative group" style="transition: all 0.3s ease;">
                            <div class="p-6 flex-grow z-10">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-semibold text-lg text-gray-900 mb-2 group-hover:text-indigo-600">
                                        {{ $event->title }}
                                    </h3>
                                    @if ($event->status == 'Terjadwal')
                                        <x-bladewind::tag label="{{ $event->status }}" color="blue" />
                                    @elseif($event->status == 'Berlangsung')
                                        <x-bladewind::tag label="{{ $event->status }}" color="green" />
                                    @elseif($event->status == 'Selesai')
                                        <x-bladewind::tag label="{{ $event->status }}" color="gray" />
                                    @else
                                        <x-bladewind::tag label="{{ $event->status }}" color="red" />
                                    @endif
                                </div>

                                <div class="space-y-3 mt-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M10.5 3v2.25M14.25 3v2.25M18 3v2.25M4.5 7.5h15M4.5 12h15m-7.5 4.5h.008v.008H12v-.008z" /></svg>
                                        <span>{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>{{ $event->countdown_status }}</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-3 text-gray-400 mt-1 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-l from-yellow-300/20 via-yellow-100/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0" style="animation: sunlightMove 4s infinite linear;"></div>
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                        <x-bladewind::empty-state
                            message="Tidak ada event yang dijadwalkan saat ini."
                            button_label="Buat Event Baru"
                            button_action="window.location.href='{{ route('admin.events.create') }}'"
                        />
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    @keyframes sunlightMove {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
    }
</style>