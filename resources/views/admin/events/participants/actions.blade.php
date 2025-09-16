<form action="{{ route('admin.events.participants.destroy', [$event, $participant]) }}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    <x-bladewind::button can_submit="true" color="red" size="tiny" icon="trash" />
</form>
@if (!$attendance)
    <form action="{{ route('admin.events.participants.manual', [$event, $participant]) }}" method="POST" class="inline">
        @csrf
        <x-bladewind::button can_submit="true" color="green" size="tiny" icon="check-circle">
            Hadirkan
        </x-bladewind::button>
    </form>
@endif