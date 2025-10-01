<div class="space-y-8">
    {{-- Form Undang Peserta --}}
    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
        <div class="p-4 md:p-6 border-b border-gray-100">
            <h3 class="text-xl md:text-2xl font-bold text-gray-900">Undang Peserta</h3>
            <p class="text-sm md:text-base text-gray-500 mt-1.5">Undang peserta internal yang sudah terdaftar atau
                tambahkan peserta eksternal baru.</p>
        </div>
        <div class="border-b border-gray-100">
            <nav class="flex px-6">
                <button type="button" data-tab="internal"
                    class="tab-btn w-1/2 py-3 md:py-4 px-1 text-center border-b-2 font-medium text-sm md:text-base border-yellow-500 text-yellow-600 tab-transition hover:bg-yellow-50">Internal</button>
                <button type="button" data-tab="external"
                    class="tab-btn w-1/2 py-3 md:py-4 px-1 text-center border-b-2 font-medium text-sm md:text-base border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 tab-transition hover:bg-gray-50">Eksternal</button>
            </nav>
        </div>
        <div class="p-4 md:p-6">
            <div id="tab-internal" class="tab-content content-transition">
                @include('admin.events.participants._internal', [
                    'event' => $event,
                    'potentialParticipants' => $potentialParticipants,
                ])
            </div>
            <div id="tab-external" class="tab-content hidden">
                @include('admin.events.participants._external', ['event' => $event])
            </div>
        </div>
    </div>

    {{-- Daftar Peserta --}}
    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200">
        <div
            class="p-4 md:p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-gray-900">Daftar Peserta <span
                        class="text-yellow-600 bg-yellow-100 px-2.5 py-1 rounded-full text-sm font-medium">{{ $event->participants->count() }}</span>
                </h3>
            </div>
            <button id="bulk-attendance-btn"
                class="w-full sm:w-auto px-4 py-2.5 md:px-5 md:py-3 
                       {{ $event->status === 'Selesai' ? 'bg-orange-500' : 'bg-gradient-to-r from-yellow-500 to-yellow-600' }}
                       text-white rounded-md text-sm md:text-base font-medium shadow-sm hover:shadow-md transition-shadow duration-200 disabled:opacity-50"
                disabled>
                @if ($event->status === 'Selesai')
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Hadirkan Terpilih (Retroaktif)
                    </span>
                @else
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Hadirkan Terpilih
                    </span>
                @endif
            </button>
        </div>
        <div class="p-4 md:p-6">
            @include('admin.events.participants._list', [
                'participants' => $event->participants->sortBy('full_name'),
                'event' => $event,
            ])
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Tambahkan CSS khusus untuk animasi tab */
        .tab-content {
            transition: opacity 0.3s ease, transform 0.3s ease;
            position: relative;
            backface-visibility: hidden;
            will-change: opacity, transform;
            transform-origin: top center;
        }

        .tab-content.hidden {
            display: none !important;
        }

        .tab-btn {
            transition: all 0.2s ease;
        }

        .tab-btn:hover {
            transform: translateY(-1px);
        }

        .tab-btn {
            transition: color 0.3s ease, border-color 0.3s ease;
            position: relative;
        }

        .tab-content.content-transition {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Tab switching dengan transisi yang lebih halus
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Hindari trigger ulang jika tab ini sudah aktif
                if (btn.classList.contains('border-yellow-500')) {
                    return;
                }

                // Menghapus kelas aktif dari semua tab
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('border-yellow-500', 'text-yellow-600');
                    b.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                        'hover:border-gray-300');
                });

                // Aktifkan tab yang diklik
                btn.classList.add('border-yellow-500', 'text-yellow-600');
                btn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                    'hover:border-gray-300');

                const tabId = btn.dataset.tab;
                const newTabContent = document.getElementById('tab-' + tabId);

                // Sembunyikan semua tab content dan tunjukkan yang dipilih
                document.querySelectorAll('.tab-content').forEach(content => {
                    if (content.id === 'tab-' + tabId) {
                        // Siapkan tab baru sebelum ditampilkan
                        content.style.opacity = '0';
                        content.style.transform = 'translateY(10px)';
                        content.classList.remove('hidden');

                        // Force reflow untuk memastikan transisi berjalan
                        void content.offsetWidth;

                        // Tampilkan dengan efek fade
                        content.style.opacity = '1';
                        content.style.transform = 'translateY(0)';
                        content.classList.add('content-transition');
                    } else {
                        content.classList.add('hidden');
                        content.classList.remove('content-transition');
                    }
                });
            });
        });

        // Bulk attendance
        document.getElementById('bulk-attendance-btn')?.addEventListener('click', async () => {
            const ids = Array.from(document.querySelectorAll('.bulk-checkbox:checked')).map(cb => cb.value);
            if (!ids.length) return;

            const eventStatus = '{{ $event->status }}';
            let confirmMessage = `Hadirkan ${ids.length} peserta?`;

            // Tambahkan peringatan khusus jika event sudah selesai
            if (eventStatus === 'Selesai') {
                confirmMessage =
                    `Event ini sudah selesai. Anda akan menambahkan kehadiran secara retroaktif untuk ${ids.length} peserta. Lanjutkan?`;
            }

            if (!confirm(confirmMessage)) return;

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
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok) {
                    const eventStatus = '{{ $event->status }}';
                    let successMessage = data.success;
                    let notificationType = 'success';

                    // Tambahkan informasi tambahan jika event sudah selesai
                    if (eventStatus === 'Selesai' || data.event_status === 'Selesai') {
                        successMessage =
                            `${data.success} (Kehadiran ditambahkan secara retroaktif karena event sudah selesai)`;
                        // Gunakan tipe notifikasi warning untuk event yang sudah selesai
                        notificationType = 'warning';
                        // Tambahkan notifikasi khusus untuk admin
                        showNotification('warning',
                            'PERHATIAN: Anda telah menambahkan kehadiran secara retroaktif karena event ini sudah selesai. Ini adalah fitur khusus admin.'
                        );
                    }

                    showNotification(notificationType, successMessage);
                    setTimeout(() => location.reload(), 1500); // Sedikit lebih lama agar pesan bisa dibaca
                } else {
                    // Tampilkan pesan yang lebih informatif jika event tidak sedang berlangsung
                    if (data.error === 'Event tidak sedang berlangsung.') {
                        const eventStatus = '{{ $event->status }}';
                        if (eventStatus === 'Terjadwal') {
                            showNotification('error',
                                'Event belum dimulai. Kehadiran hanya dapat dicatat saat event sedang berlangsung.'
                            );
                        } else {
                            showNotification('error', data.error || 'Gagal.');
                        }
                    } else {
                        showNotification('error', data.error || 'Gagal.');
                    }
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

        // Pastikan status tombol diperbarui saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            updateBulkButton();
        });

        function updateBulkButton() {
            const hasChecked = document.querySelector('.bulk-checkbox:checked');
            const bulkBtn = document.getElementById('bulk-attendance-btn');
            if (bulkBtn) {
                if (hasChecked) {
                    bulkBtn.removeAttribute('disabled');
                } else {
                    bulkBtn.setAttribute('disabled', 'disabled');
                }
            }
        }

        // Notification helper yang memanfaatkan sistem Alpine alert
        function showNotification(type, message) {
            // Gunakan Alpine store untuk alert jika tersedia
            if (window.Alpine && Alpine.store('app')) {
                Alpine.store('app').addAlert(type, message);
            } else {
                // Fallback jika Alpine store tidak tersedia
                // Buat container untuk notifikasi jika belum ada
                let notificationsContainer = document.getElementById('notifications-container');
                if (!notificationsContainer) {
                    notificationsContainer = document.createElement('div');
                    notificationsContainer.id = 'notifications-container';
                    notificationsContainer.className = 'fixed top-4 right-4 z-50 flex flex-col items-end space-y-3';
                    document.body.appendChild(notificationsContainer);
                }

                const div = document.createElement('div');
                let bgColor;
                if (type === 'error') {
                    bgColor = 'bg-red-500';
                } else if (type === 'warning') {
                    bgColor = 'bg-orange-500';
                } else {
                    bgColor = 'bg-gradient-to-r from-yellow-500 to-yellow-600';
                }

                // Tambahkan CSS untuk animasi
                const animationStyles = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .slide-in-right {
                animation: slideInRight 0.5s ease-out forwards;
            }
            .slide-out-right {
                animation: slideOutRight 0.5s ease-in forwards;
            }
        `;

                // Tambahkan style untuk animasi
                if (!document.getElementById('notification-animations')) {
                    const styleElement = document.createElement('style');
                    styleElement.id = 'notification-animations';
                    styleElement.innerHTML = animationStyles;
                    document.head.appendChild(styleElement);
                }

                div.className = `px-6 py-4 rounded-lg shadow-lg text-white z-50 ${bgColor} slide-in-right`;
                // Tambahkan ikon berdasarkan tipe notifikasi
                let icon = '';
                if (type === 'error') {
                    icon =
                        '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                } else if (type === 'warning') {
                    icon =
                        '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                } else {
                    icon =
                        '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                }

                div.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    ${icon}
                    <span>${message}</span>
                </div>
                <button onclick="closeNotification(this.parentElement.parentElement)" class="ml-4 w-5 h-5 flex items-center justify-center rounded-full bg-white bg-opacity-25 hover:bg-opacity-40 text-white transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

                // Tambahkan notifikasi ke container alih-alih langsung ke body
                notificationsContainer.appendChild(div);

                // Auto dismiss dengan animasi
                setTimeout(() => closeNotification(div), 5000);
            }
        }

        // Fungsi untuk menutup notifikasi dengan animasi
        function closeNotification(element) {
            if (!element) return;

            // Hapus kelas animasi masuk dan tambahkan animasi keluar
            element.classList.remove('slide-in-right');
            element.classList.add('slide-out-right');

            // Hapus elemen setelah animasi selesai
            setTimeout(() => {
                if (element && element.parentNode) {
                    element.parentNode.removeChild(element);

                    // Periksa apakah container kosong dan hapus jika perlu
                    const container = document.getElementById('notifications-container');
                    if (container && container.children.length === 0) {
                        container.parentNode.removeChild(container);
                    }
                }
            }, 500); // Durasi animasi
        }

        // Initialize tabs on load
        function initializeTabs() {
            // Pastikan tab default (internal) aktif saat pertama kali memuat halaman
            const activeTab = document.querySelector('.tab-btn.border-yellow-500') || document.querySelector(
                '.tab-btn[data-tab="internal"]');
            if (activeTab) {
                const tabId = activeTab.dataset.tab;

                // Pastikan tab button yang aktif memiliki styling yang benar
                activeTab.classList.add('border-yellow-500', 'text-yellow-600');
                activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                    'hover:border-gray-300');

                // Siapkan tampilan tab content yang sesuai
                document.querySelectorAll('.tab-content').forEach(content => {
                    if (content.id === 'tab-' + tabId) {
                        content.classList.remove('hidden');
                        content.style.opacity = '1';
                        content.style.transform = 'translateY(0)';
                        content.classList.add('content-transition');
                    } else {
                        content.classList.add('hidden');
                        content.classList.remove('content-transition');
                    }
                });
            }
        }

        // Initialize tabs on load
        document.addEventListener('DOMContentLoaded', () => {
            initializeTabs();

            // Notifikasi dari session sudah ditangani oleh komponen alert-handler
            // Sehingga tidak perlu lagi ditampilkan disini untuk menghindari notifikasi ganda
        });
    </script>
@endpush
