<span class="mt-2 sm:mt-0 bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">
    @if($documents->count() === 1)
        1 File
    @else
        {{ $documents->count() }} File
    @endif
</span>