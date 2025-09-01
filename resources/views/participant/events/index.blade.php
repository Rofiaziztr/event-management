<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Daftar Event yang Anda Ikuti</h3>
                    <div class="space-y-4">
                        @forelse ($events as $event)
                            <div class="border p-4 rounded-lg flex justify-between items-center">
                                <div>
                                    <a href="{{ route('participant.events.show', $event) }}">
                                        <h4 class="font-bold text-indigo-600 hover:underline">{{ $event->title }}</h4>
                                    </a>
                                    <p class="text-sm text-gray-600">{{ $event->start_time->format('d M Y, H:i') }} -
                                        {{ $event->location }}</p>
                                </div>

                                <div class="flex items-center gap-4">
                                    {{-- Tag Status Kehadiran --}}
                                    @if (in_array($event->id, $attendedEventIds))
                                        <x-bladewind::tag label="Sudah Hadir" color="green" />
                                    @endif

                                    {{-- Tag Status Event --}}
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
                            </div>
                        @empty
                            <p class="text-gray-500">Anda belum terdaftar di event mana pun.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
