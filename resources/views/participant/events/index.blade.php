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

                                <div class="flex items-center gap-4"> {{-- Bungkus status dalam div baru --}}
                                    @if (in_array($event->id, $attendedEventIds))
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Sudah Hadir
                                        </span>
                                    @endif

                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
            @if ($event->status == 'Scheduled') bg-blue-100 text-blue-800 
            @else bg-gray-100 text-gray-800 @endif">
                                        {{ $event->status }}
                                    </span>
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
