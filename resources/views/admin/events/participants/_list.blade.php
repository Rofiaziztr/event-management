<div class="flex justify-between items-center mb-3 md:mb-4">
    <div class="flex items-center">
        <!-- Placeholder untuk filter atau opsi lainnya jika diperlukan -->
    </div>
</div>

<div class="mb-4">
    <div class="relative">
        <div class="absolute inset-y-0 start-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" id="search-participants" placeholder="Cari nama atau email..."
            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500 pl-10 p-2.5 text-sm md:text-base">
    </div>
</div>

<div class="overflow-x-auto overflow-y-auto max-h-96" id="participants-list">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-2 sm:px-3 py-2 text-left">
                    <input type="checkbox" id="select-all"
                        class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                </th>
                <th
                    class="px-2 sm:px-3 py-2 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    No</th>
                <th
                    class="px-2 sm:px-3 py-2 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    Nama</th>
                <th
                    class="px-2 sm:px-3 py-2 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                    Email</th>
                <th
                    class="px-2 sm:px-3 py-2 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    Status</th>
                <th
                    class="px-2 sm:px-3 py-2 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    Calendar</th>
                <th
                    class="px-2 sm:px-3 py-2 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($participants as $participant)
                <tr class="hover:bg-gray-50 cursor-pointer transition-colors duration-150"
                    onclick="toggleRowCheckbox(this, {{ $participant->id }})">
                    <td class="px-3 py-2">
                        @php $attendance = $participant->attendances->where('event_id', $event->id)->first(); @endphp
                        @if (!$attendance)
                            <input type="checkbox" value="{{ $participant->id }}"
                                class="bulk-checkbox rounded border-gray-300 text-yellow-600 focus:ring-yellow-500"
                                onclick="event.stopPropagation();">
                        @endif
                    </td>
                    <td class="px-3 py-2 text-sm md:text-base text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-3 py-2 font-medium text-gray-900 text-sm md:text-base">{{ $participant->full_name }}
                    </td>
                    <td class="px-3 py-2 text-sm md:text-base text-gray-500 hidden sm:table-cell">
                        {{ $participant->email }}</td>
                    <td class="px-3 py-2">
                        @if ($attendance)
                            <span
                                class="px-2.5 py-1 text-xs md:text-sm bg-green-100 text-green-800 rounded-full font-medium">Hadir</span>
                        @else
                            <span
                                class="px-2.5 py-1 text-xs md:text-sm bg-red-100 text-red-800 rounded-full font-medium">Belum</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        @if ($participant->hasGoogleCalendarAccess())
                            @if ($participant->isEventSyncedToCalendar($event))
                                <span
                                    class="px-2.5 py-1 text-xs md:text-sm bg-blue-100 text-blue-800 rounded-full font-medium flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Tersinkron
                                </span>
                            @else
                                <span
                                    class="px-2.5 py-1 text-xs md:text-sm bg-yellow-100 text-yellow-800 rounded-full font-medium flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Belum Sync
                                </span>
                            @endif
                        @else
                            <span
                                class="px-2.5 py-1 text-xs md:text-sm bg-gray-100 text-gray-600 rounded-full font-medium flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                Belum Hubung
                            </span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex items-center space-x-2">
                            @php $attendance = $participant->attendances->where('event_id', $event->id)->first(); @endphp
                            @if (!$attendance)
                                <form action="{{ route('admin.events.participants.manual', [$event, $participant]) }}"
                                    method="POST" class="inline" onclick="event.stopPropagation();">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 
                                        {{ $event->status === 'Selesai' ? 'bg-orange-500 text-white' : 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white' }}
                                        text-xs md:text-sm font-medium rounded-md hover:shadow-md transition-all duration-200 shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $event->status === 'Selesai' ? 'Hadirkan (R)' : 'Hadirkan' }}
                                    </button>
                                </form>
                            @endif

                            {{-- Individual Calendar Sync Button --}}
                            @if ($participant->hasGoogleCalendarAccess() && !$participant->isEventSyncedToCalendar($event))
                                @php
                                    $syncRecord = \App\Models\EventCalendarSync::where('event_id', $event->id)
                                        ->where('user_id', $participant->id)
                                        ->latest()
                                        ->first();
                                    $hasFailedSync = $syncRecord && $syncRecord->sync_status === 'failed';
                                @endphp

                                @if ($hasFailedSync)
                                    <button type="button"
                                        onclick="retrySyncIndividualParticipant({{ $participant->id }}, '{{ $participant->full_name }}', event)"
                                        class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs md:text-sm font-medium rounded-md hover:bg-orange-200 transition-colors duration-150 shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Retry Sync
                                    </button>
                                @else
                                    <button type="button"
                                        onclick="syncIndividualParticipant({{ $participant->id }}, '{{ $participant->full_name }}', event)"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs md:text-sm font-medium rounded-md hover:bg-blue-200 transition-colors duration-150 shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Sync Calendar
                                    </button>
                                @endif
                            @elseif ($participant->hasGoogleCalendarAccess() && $participant->isEventSyncedToCalendar($event))
                                <span
                                    class="inline-flex items-center px-3 py-1.5 text-xs md:text-sm text-green-600 font-medium">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Sudah Sync
                                </span>
                            @endif

                            <form action="{{ route('admin.events.participants.destroy', [$event, $participant]) }}"
                                method="POST" class="inline" onclick="event.stopPropagation();"
                                onsubmit="event.stopPropagation(); return confirm('Yakin ingin menghapus peserta ini dari event?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs md:text-sm font-medium rounded-md hover:bg-red-200 transition-colors duration-150 shadow-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6"
                        class="px-2 sm:px-4 py-4 sm:py-6 text-center text-gray-500 text-sm md:text-base">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
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
        if (event.target.closest('button') || event.target.closest('form') || event.target.closest(
                'input[type="checkbox"]')) {
            return;
        }

        const checkbox = row.querySelector('.bulk-checkbox');
        if (checkbox && !checkbox.disabled) {
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change', {
                bubbles: true
            }));
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
                newRow.innerHTML =
                    `<td colspan="6" class="px-2 sm:px-4 py-6 sm:py-8 text-center text-gray-500 text-sm md:text-base lg:text-lg">Tidak ada peserta yang cocok dengan pencarian.</td>`;
                tbody.appendChild(newRow);
            } else {
                emptyRow.style.display = '';
            }
        } else if (emptyRow) {
            emptyRow.style.display = 'none';
        }
    });

    // Individual participant calendar sync
    async function syncIndividualParticipant(participantId, participantName, event) {
        event.stopPropagation();

        // Show loading state on button
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = `
            <svg class="w-4 h-4 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Menyinkronkan...
        `;
        button.disabled = true;

        try {
            const response = await fetch(
            `{{ route('admin.events.show', $event) }}/${participantId}/sync-calendar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (data.success) {
                window.showSuccess(`Berhasil menyinkronkan calendar ${participantName}!`, 5000, {
                    title: 'Sinkronisasi Berhasil',
                    icon: '📅'
                });

                // Update button to show success state
                button.innerHTML = `
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Sudah Sync
                `;
                button.className =
                    'inline-flex items-center px-3 py-1.5 text-xs md:text-sm text-green-600 font-medium';

                // Update status badge
                const statusCell = button.closest('tr').querySelector('td:nth-child(6)');
                if (statusCell) {
                    statusCell.innerHTML = `
                        <span class="px-2.5 py-1 text-xs md:text-sm bg-blue-100 text-blue-800 rounded-full font-medium flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Tersinkron
                        </span>
                    `;
                }
            } else {
                window.showError(data.message || `Gagal menyinkronkan calendar ${participantName}`, 6000, {
                    title: 'Sinkronisasi Gagal',
                    icon: '❌'
                });

                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
            }
        } catch (error) {
            window.showError(`Terjadi error saat menyinkronkan calendar ${participantName}`, 6000, {
                title: 'Error Sinkronisasi',
                icon: '❌'
            });

            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;

            console.error('Sync error:', error);
        }
    }

    // Retry individual participant calendar sync
    async function retrySyncIndividualParticipant(participantId, participantName, event) {
        event.stopPropagation();

        // Show loading state on button
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = `
            <svg class="w-4 h-4 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Mencoba lagi...
        `;
        button.disabled = true;

        try {
            const response = await fetch(
            `{{ route('admin.events.show', $event) }}/${participantId}/sync-calendar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    retry: true
                })
            });

            const data = await response.json();

            if (data.success) {
                window.showSuccess(`Berhasil mencoba ulang sinkronisasi calendar ${participantName}!`, 5000, {
                    title: 'Retry Berhasil',
                    icon: '🔄'
                });

                // Update button to show success state
                button.innerHTML = `
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Sudah Sync
                `;
                button.className =
                    'inline-flex items-center px-3 py-1.5 text-xs md:text-sm text-green-600 font-medium';

                // Update status badge
                const statusCell = button.closest('tr').querySelector('td:nth-child(6)');
                if (statusCell) {
                    statusCell.innerHTML = `
                        <span class="px-2.5 py-1 text-xs md:text-sm bg-blue-100 text-blue-800 rounded-full font-medium flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Tersinkron
                        </span>
                    `;
                }
            } else {
                window.showWarning(`Retry gagal untuk ${participantName}. ${data.message || 'Coba lagi nanti.'}`,
                    6000, {
                        title: 'Retry Gagal',
                        icon: '⚠️'
                    });

                // Keep retry button for another attempt
                button.innerHTML = originalText;
                button.disabled = false;
            }
        } catch (error) {
            window.showError(`Terjadi error saat retry sync calendar ${participantName}`, 6000, {
                title: 'Error Retry',
                icon: '❌'
            });

            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;

            console.error('Retry sync error:', error);
        }
    }
</script>
