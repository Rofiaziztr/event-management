<div class="flex justify-between items-center mb-4">
    <h4 class="font-medium">Daftar Peserta</h4>
</div>

<div class="mb-4">
    <input type="text" id="search-participants" placeholder="Cari nama atau email..." class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
</div>

<div class="overflow-y-auto max-h-96" id="participants-list">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left">
                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($participants as $participant)
                <tr class="hover:bg-gray-50 cursor-pointer" 
                    onclick="toggleRowCheckbox(this, {{ $participant->id }})">
                    <td class="px-4 py-3">
                        @php $attendance = $participant->attendances->where('event_id', $event->id)->first(); @endphp
                        @if (!$attendance)
                            <input type="checkbox" value="{{ $participant->id }}" class="bulk-checkbox rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $participant->full_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $participant->email }}</td>
                    <td class="px-4 py-3">
                        @if ($attendance)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Hadir</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Belum Hadir</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form action="{{ route('admin.events.participants.destroy', [$event, $participant]) }}" method="POST" onsubmit="event.stopPropagation(); return confirm('Yakin ingin menghapus peserta ini dari event?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-full hover:bg-red-50 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada peserta yang diundang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function toggleRowCheckbox(row, userId) {
    const checkbox = row.querySelector('.bulk-checkbox');
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
    }
}

document.getElementById('search-participants')?.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll('#participants-list tr').forEach(row => {
        if (row.querySelector('td:nth-child(3), td:nth-child(4)')) {
            const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            row.style.display = (name.includes(term) || email.includes(term)) ? '' : 'none';
        }
    });
});
</script>