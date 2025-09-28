<div class="space-y-8">
    {{-- Form Undang Peserta --}}
    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-2xl font-bold text-gray-900">Undang Peserta</h3>
            <p class="text-gray-500 mt-1">Undang peserta internal yang sudah terdaftar atau tambahkan peserta eksternal baru.</p>
        </div>
        <div class="border-b border-gray-100">
            <nav class="flex px-6">
                <button type="button" data-tab="internal" class="tab-btn w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm border-yellow-500 text-yellow-600">Internal</button>
                <button type="button" data-tab="external" class="tab-btn w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Eksternal</button>
            </nav>
        </div>
        <div class="p-6">
            <div id="tab-internal" class="tab-content">
                @include('admin.events.participants._internal', ['event' => $event, 'potentialParticipants' => $potentialParticipants])
            </div>
            <div id="tab-external" class="tab-content hidden">
                @include('admin.events.participants._external', ['event' => $event])
            </div>
        </div>
    </div>

    {{-- Daftar Peserta --}}
    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-900">Daftar Peserta ({{ $event->participants->count() }})</h3>
            <button id="bulk-attendance-btn" class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg disabled:opacity-50" disabled>
                Hadirkan Terpilih
            </button>
        </div>
        <div class="p-6">
            @include('admin.events.participants._list', ['participants' => $event->participants->sortBy('full_name'), 'event' => $event])
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('border-yellow-500', 'text-yellow-600'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        btn.classList.add('border-yellow-500', 'text-yellow-600');
        document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
    });
});

// Bulk attendance
document.getElementById('bulk-attendance-btn')?.addEventListener('click', async () => {
    const ids = Array.from(document.querySelectorAll('.bulk-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) return;

    if (!confirm(`Hadirkan ${ids.length} peserta?`)) return;

    const btn = document.getElementById('bulk-attendance-btn');
    const originalText = btn.innerHTML;
    btn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memproses...
    `;
    btn.disabled = true;

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    ids.forEach(id => formData.append('user_ids[]', id));

    try {
        const res = await fetch('{{ route('admin.events.participants.bulk-attendance', $event) }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        const data = await res.json();
        if (res.ok) {
            showNotification('success', data.success);
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('error', data.error || 'Gagal.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        showNotification('error', 'Gagal menghubungi server.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

// Select all
document.getElementById('select-all')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.bulk-checkbox:not(:disabled)');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkButton();
});

// Update bulk button state
document.addEventListener('change', (e) => {
    if (e.target.classList.contains('bulk-checkbox')) {
        updateBulkButton();
    }
});

function updateBulkButton() {
    const hasChecked = document.querySelector('.bulk-checkbox:checked');
    document.getElementById('bulk-attendance-btn')?.toggleAttribute('disabled', !hasChecked);
}

// Notification helper
function showNotification(type, message) {
    const div = document.createElement('div');
    div.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white z-50 ${
        type === 'error' ? 'bg-red-500' : 'bg-gradient-to-r from-yellow-500 to-yellow-600'
    }`;
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-white">&times;</button>
        </div>
    `;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 5000);
}

// Auto-show Laravel session messages
document.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
        showNotification('success', "{{ session('success') }}");
    @elseif(session('error'))
        showNotification('error', "{{ session('error') }}");
    @elseif(session('info'))
        showNotification('info', "{{ session('info') }}");
    @endif
});
</script>
@endpush