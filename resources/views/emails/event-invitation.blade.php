<x-mail::message>
    # 🎉 Undangan Event

    Halo {{ $participant->full_name }},

    Anda diundang menghadiri acara:

    **{{ $event->title }}**
    🗓 {{ $event->start_time->format('d F Y, H:i') }} WIB
    📍 {{ $event->location }}

    <x-mail::button :url="route('participant.events.show', $event->id)" color="success">
        Lihat Detail
    </x-mail::button>

    Sampai jumpa!
    {{ config('app.name') }}
</x-mail::message>
