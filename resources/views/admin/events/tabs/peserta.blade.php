<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    {{-- Left Section - Add Participants Forms --}}
    <div class="xl:col-span-2 space-y-6">
        {{-- Individual Internal Participants --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-5-2a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Undang Peserta Internal</h3>
                </div>
                <p class="text-blue-100 text-sm mt-1">{{ $potentialParticipants->count() }} calon peserta tersedia</p>
            </div>
            <form action="{{ route('admin.events.participants.store', $event) }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="user_ids" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Peserta Internal
                        </label>
                        <div class="relative">
                            <select name="user_ids[]" id="user_ids" multiple required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" size="8">
                                @forelse ($potentialParticipants as $user)
                                <option value="{{ $user->id }}" class="py-2 px-3 hover:bg-blue-50">
                                    {{ $user->full_name }} ({{ $user->nip }})
                                    @if ($user->division)
                                    - {{ $user->division }}
                                    @endif
                                </option>
                                @empty
                                <option disabled class="py-2 px-3 text-gray-500">Tidak ada calon peserta internal.
                                </option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mt-2 flex items-center text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tahan <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Ctrl</kbd>
                            atau <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg">Cmd</kbd>
                            untuk memilih multiple
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <x-bladewind::button can_submit="true" color="blue" icon="plus">
                            Undang Peserta Terpilih
                        </x-bladewind::button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Bulk Invite --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Undang Peserta Massal</h3>
                </div>
                <p class="text-green-100 text-sm mt-1">Undang berdasarkan divisi atau semua sekaligus</p>
            </div>
            <form id="bulk-invite-form" action="{{ route('admin.events.participants.store.bulk', $event) }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="invite_method" id="invite_method_input">
                <div class="space-y-4">
                    <div>
                        <label for="division" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Divisi (Opsional)
                        </label>
                        <select name="division" id="division" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Pilih Divisi Tertentu --</option>
                            @php $divisions = $potentialParticipants->pluck('division')->unique()->filter()->sort(); @endphp
                            @foreach ($divisions as $division)
                            <option value="{{ $division }}">{{ $division }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <x-bladewind::button type="button" color="green" size="small" onclick="submitBulkInvite('division')" icon="user-group">
                            Undang Per Divisi
                        </x-bladewind::button>
                        <x-bladewind::button type="button" color="yellow" size="small" onclick="submitBulkInvite('all')" icon="users">
                            Undang Semua Internal
                        </x-bladewind::button>
                    </div>
                </div>
            </form>
        </div>

        {{-- External Participants --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Undang Peserta Eksternal</h3>
                </div>
                <p class="text-purple-100 text-sm mt-1">Tambahkan peserta dari luar organisasi</p>
            </div>
            <form action="{{ route('admin.events.participants.store.external', $event) }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="full_name" id="full_name" required value="{{ old('full_name') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="nama@email.com">
                    </div>
                    <div>
                        <label for="institution" class="block text-sm font-medium text-gray-700 mb-1">
                            Instansi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="institution" id="institution" required value="{{ old('institution') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Nama instansi/perusahaan">
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">
                            Posisi/Jabatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="position" id="position" required value="{{ old('position') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Jabatan/posisi">
                    </div>
                    <div class="md:col-span-2">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                            No. Telepon
                        </label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="08123456789 (opsional)">
                    </div>
                </div>
                @if ($errors->external->any())
                <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Error pada form eksternal:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->external->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="flex justify-end pt-6">
                    <x-bladewind::button can_submit="true" color="purple" icon="plus">
                        Undang Peserta Eksternal
                    </x-bladewind::button>
                </div>
            </form>
        </div>
    </div>

    {{-- Participants List Section --}}
    <div class="col-span-1 xl:col-span-3 mt-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Daftar Peserta</h3>
                </div>
            </div>

            <div class="p-6">
                {{-- Bulk Action Buttons --}}
                <div class="mb-4 flex items-center space-x-4">
                    <x-bladewind::button id="bulk-attendance-btn" color="green" size="small" icon="check-circle" disabled="true">
                        Hadirkan Terpilih
                    </x-bladewind::button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="participantsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status Kehadiran
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- DataTables will populate this section --}}
                        </tbody>
                    </table>
                </div>

                {{-- Export Button (jika perlu) --}}
                <div class="mt-6 border-t pt-4 text-center">
                    <button onclick="exportAllParticipants()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Daftar Peserta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function exportAllParticipants() {
        // Implementasi fungsi ekspor Anda di sini
        alert('Fungsi ekspor akan diimplementasikan.');
    }

    $(document).ready(function() {
        // Inisialisasi DataTables
        var table = $('#participantsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.events.participants.list', $event) }}",
                type: "GET",
                error: function(xhr, error, thrown) {
                    console.log('DataTables error:', error, thrown);
                    alert('Terjadi kesalahan saat memuat data peserta');
                }
            },
            columns: [{
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                width: '5%'
            }, {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                width: '5%'
            }, {
                data: 'full_name',
                name: 'full_name'
            }, {
                data: 'email',
                name: 'email'
            }, {
                data: 'attendance_status',
                name: 'attendance_status',
                orderable: false,
                searchable: false
            }, {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }, ],
            // Mengatur elemen-elemen DataTables (Search, Pagination, Info, dll)
            // 'l' = length changing input, 'f' = filtering input, 
            // 't' = table, 'i' = info, 'p' = pagination, 'r' = processing display
            dom: '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari peserta...",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ peserta",
                infoEmpty: "Tidak ada peserta",
                infoFiltered: "(disaring dari _MAX_ total)",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "›",
                    previous: "‹"
                }
            },
            drawCallback: function() {
                // Reset select all checkbox pada setiap draw (ganti halaman, search, dll)
                $('#select-all').prop('checked', false);
                updateBulkButtons();
            }
        });

        // Fungsi untuk mengupdate status tombol bulk action
        function updateBulkButtons() {
            var selectedCount = $('.participant-checkbox:checked').length;
            if (selectedCount > 0) {
                $('#bulk-attendance-btn').prop('disabled', false);
            } else {
                $('#bulk-attendance-btn').prop('disabled', true);
            }
        }

        // Event handler untuk checkbox 'select all'
        $('#select-all').on('click', function() {
            var rows = table.rows({
                'search': 'applied'
            }).nodes();
            $('input.participant-checkbox', rows).prop('checked', this.checked);
            updateBulkButtons();
        });

        // Event handler untuk checkbox individual di dalam tabel
        $('#participantsTable tbody').on('change', 'input.participant-checkbox', function() {
            if (!this.checked) {
                var el = $('#select-all').get(0);
                if (el && el.checked && ('indeterminate' in el)) {
                    el.indeterminate = true;
                }
            }
            updateBulkButtons();
        });

        // Event handler untuk tombol "Hadirkan Terpilih"
        $('#bulk-attendance-btn').on('click', function() {
            var userIds = [];
            $('.participant-checkbox:checked').each(function() {
                userIds.push($(this).val());
            });

            if (userIds.length > 0) {
                Swal.fire({
                    title: 'Anda yakin?',
                    text: `Anda akan menghadirkan ${userIds.length} peserta terpilih.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hadirkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.events.participants.bulk-attendance', $event) }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                user_ids: userIds
                            },
                            success: function(response) {
                                Swal.fire('Berhasil!', response.success, 'success');
                                table.ajax.reload(null, false); // Reload tabel tanpa reset pagination
                            },
                            error: function(xhr) {
                                let errorMsg = 'Terjadi kesalahan.';
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    errorMsg = xhr.responseJSON.error;
                                }
                                Swal.fire('Gagal!', errorMsg, 'error');
                            }
                        });
                    }
                });
            }
        });
    });
</script>
@endpush