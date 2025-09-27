<form action="{{ route('admin.events.participants.store', $event) }}" method="POST">
    @csrf
    <label class="block text-lg font-semibold text-gray-800 mb-2">Undang Peserta Pilihan</label>
    <div class="space-y-2">
        <input type="text" id="search-internal" placeholder="Cari nama atau divisi..." class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
        <div class="border border-gray-200 rounded-lg max-h-60 overflow-y-auto p-2">
            @php $counter = 1; @endphp
            @forelse ($potentialParticipants as $user)
                <div class="participant-item" data-name="{{ strtolower($user->full_name) }}" data-division="{{ strtolower($user->division ?? '') }}">
                    <label class="flex items-center p-3 space-x-3 hover:bg-yellow-50 rounded-lg cursor-pointer">
                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="h-4 w-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                        <span class="text-gray-500 text-sm w-6 text-right">{{ $counter++ }}.</span>
                        <div class="flex-1">
                            <div class="font-medium text-gray-800">{{ $user->full_name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->division ?? 'Divisi tidak diketahui' }}</div>
                        </div>
                    </label>
                </div>
            @empty
                <p class="p-4 text-center text-gray-500">Semua peserta internal sudah diundang.</p>
            @endforelse
        </div>
    </div>
    <div class="flex justify-end pt-4">
        <x-bladewind::button can_submit="true" class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white">
            Undang Terpilih
        </x-bladewind::button>
    </div>
</form>

<div class="border-t border-gray-100 pt-8 space-y-4">
    @if($potentialParticipants->isNotEmpty())
        {{-- Undang Semua --}}
        <form action="{{ route('admin.events.participants.invite-all-available', $event) }}" method="POST" onsubmit="return confirm('Yakin ingin mengundang SEMUA peserta yang tersedia?')">
            @csrf
            <x-bladewind::button 
                can_submit="true" 
                has_spinner="true"
                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white">
                Undang Semua Peserta Tersedia ({{ $potentialParticipants->count() }})
            </x-bladewind::button>
        </form>

        {{-- Undang Per Divisi --}}
        @php
            $divisions = $potentialParticipants->pluck('division')->filter()->unique()->sort();
            $divisionCounts = $potentialParticipants->groupBy('division')->map->count();
        @endphp

        @if($divisions->count() > 1)
            <form action="{{ route('admin.events.participants.invite-by-division', $event) }}" method="POST" class="mt-4" onsubmit="return confirmDivisionInvite(event)">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <select name="division" required class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisions as $div)
                            <option value="{{ $div }}">{{ $div }} ({{ $divisionCounts[$div] }} peserta)</option>
                        @endforeach
                    </select>
                    <x-bladewind::button 
                        can_submit="true" 
                        has_spinner="true"
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                        Undang Per Divisi
                    </x-bladewind::button>
                </div>
            </form>
        @endif
    @else
        <p class="text-sm text-gray-500 bg-gray-50 p-3 rounded-lg text-center">
            Tidak ada peserta internal yang tersedia.
        </p>
    @endif
</div>

<script>
document.getElementById('search-internal')?.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll('.participant-item').forEach(row => {
        const name = row.dataset.name || '';
        const division = row.dataset.division || '';
        row.style.display = (name.includes(term) || division.includes(term)) ? '' : 'none';
    });
});

function confirmDivisionInvite(e) {
    const select = e.target.querySelector('select[name="division"]');
    if (select && select.value) {
        return confirm(`Yakin ingin mengundang semua peserta dari divisi "${select.options[select.selectedIndex].text}"?`);
    }
    return true;
}
</script>