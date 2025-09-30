<div class="flex justify-between items-center mb-3 md:mb-4">
    <h4 class="font-medium text-sm md:text-base">Daftar Peserta</h4>
</div>

<div class="mb-4">
    <div class="relative">
        <div class="absolute inset-y-0 start-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" id="search-participants" placeholder="Cari nama atau email..." class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500 pl-10 p-2 text-sm">
    </div>
</div>

<div class="overflow-x-auto overflow-y-auto max-h-96" id="participants-list">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-2 sm:px-3 py-2 text-left">
                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                </th>
                <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Email</th>
                <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($participants as $participant)
                <tr class="hover:bg-gray-50 cursor-pointer transition-colors duration-150" 
                    onclick="toggleRowCheckbox(this, {{ $participant->id }})">
                    <td class="px-3 py-2">
                        @php $attendance = $participant->attendances->where('event_id', $event->id)->first(); @endphp
                        @if (!$attendance)
                            <input type="checkbox" value="{{ $participant->id }}" class="bulk-checkbox rounded border-gray-300 text-yellow-600 focus:ring-yellow-500" onclick="event.stopPropagation();">
                        @endif
                    </td>
                    <td class="px-3 py-2 text-xs sm:text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-3 py-2 font-medium text-gray-900 text-xs sm:text-sm">{{ $participant->full_name }}</td>
                    <td class="px-3 py-2 text-xs sm:text-sm text-gray-500 hidden sm:table-cell">{{ $participant->email }}</td>
                    <td class="px-3 py-2">
                        @if ($attendance)
                            <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full font-medium">Hadir</span>
                        @else
                            <span class="px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded-full font-medium">Belum</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex items-center space-x-2">
                            @php $attendance = $participant->attendances->where('event_id', $event->id)->first(); @endphp
                            @if(!$attendance)
                                <form action="{{ route('admin.events.participants.manual', [$event, $participant]) }}" method="POST" class="inline" onclick="event.stopPropagation();">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2 py-1 
                                        {{ $event->status === 'Selesai' ? 'bg-orange-500 text-white' : 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white' }}
                                        text-xs font-medium rounded hover:shadow-md transition-all duration-200 shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $event->status === 'Selesai' ? 'Hadirkan (R)' : 'Hadirkan' }}
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.events.participants.destroy', [$event, $participant]) }}" method="POST" class="inline" onclick="event.stopPropagation();" onsubmit="event.stopPropagation(); return confirm('Yakin ingin menghapus peserta ini dari event?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded hover:bg-red-200 transition-colors duration-150 shadow-sm">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-2 sm:px-4 py-4 sm:py-6 text-center text-gray-500 text-xs sm:text-sm">
                        <div class="flex flex-col items-center justify-center space-y-1">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Belum ada peserta yang diundang.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function toggleRowCheckbox(row, userId) {
    // Mencegah tindakan jika klik pada tombol atau form
    if (event.target.closest('button') || event.target.closest('form') || event.target.closest('input[type="checkbox"]')) {
        return;
    }
    
    const checkbox = row.querySelector('.bulk-checkbox');
    if (checkbox && !checkbox.disabled) {
        checkbox.checked = !checkbox.checked;
        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
        updateBulkButton(); // Pastikan tombol bulk diperbarui setelah toggle
    }
}

document.getElementById('search-participants')?.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    let visibleCount = 0;
    
    document.querySelectorAll('#participants-list tbody tr').forEach(row => {
        // Lewati baris "tidak ada peserta"
        if (row.querySelector('td[colspan]')) {
            return;
        }
        
        if (row.querySelector('td:nth-child(3), td:nth-child(4)')) {
            const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const visible = (name.includes(term) || email.includes(term));
            
            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        }
    });
    
    // Tampilkan pesan jika tidak ada hasil pencarian
    const emptyRow = document.getElementById('no-search-results');
    if (visibleCount === 0) {
        if (!emptyRow) {
            const tbody = document.querySelector('#participants-list tbody');
            const newRow = document.createElement('tr');
            newRow.id = 'no-search-results';
            newRow.innerHTML = `<td colspan="6" class="px-2 sm:px-4 py-6 sm:py-8 text-center text-gray-500 text-sm sm:text-base">Tidak ada peserta yang cocok dengan pencarian.</td>`;
            tbody.appendChild(newRow);
        } else {
            emptyRow.style.display = '';
        }
    } else if (emptyRow) {
        emptyRow.style.display = 'none';
    }
});
</script>