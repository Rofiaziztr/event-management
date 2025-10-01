<div class="flex items-center space-x-2">
    {{-- Manual Attendance Button --}}
    @if (!$p->attendances->where('event_id', $event->id)->count())
        <form action="{{ route('admin.events.participants.manual', [$event, $p]) }}" method="POST" class="inline">
            @csrf
            <button type="submit"
                class="inline-flex items-center px-2 py-1 md:px-3 md:py-1.5 lg:px-3 lg:py-1.5 bg-green-100 text-green-700 text-xs md:text-sm lg:text-sm font-medium rounded hover:bg-green-200 transition-colors">
                <svg class="w-3 h-3 md:w-4 md:h-4 lg:w-4 lg:h-4 mr-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Hadirkan
            </button>
        </form>
    @endif

    {{-- Delete Button --}}
    <form action="{{ route('admin.events.participants.destroy', [$event, $p]) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Yakin hapus peserta ini?')"
            class="inline-flex items-center px-2 py-1 md:px-3 md:py-1.5 lg:px-3 lg:py-1.5 bg-red-100 text-red-700 text-xs md:text-sm lg:text-sm font-medium rounded hover:bg-red-200 transition-colors">
            <svg class="w-3 h-3 md:w-4 md:h-4 lg:w-4 lg:h-4 mr-1" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Hapus
        </button>
    </form>
</div>
