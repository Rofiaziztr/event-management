<x-app-layout>
    <x-slot name="header">
        <div
            class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-3 md:space-y-4 lg:space-y-0">
            <div class="w-full lg:w-auto">
                <h2 class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-tight truncate">
                    {{ $event->title }}</h2>
                <p class="text-gray-600 mt-1.5 text-sm md:text-base">Dibuat oleh {{ $event->creator->full_name }}</p>
                @if ($event->category)
                    <p class="text-sm md:text-base text-yellow-600 font-medium mt-0.5">{{ $event->category->name }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $dynamicStatus = $event->status;
                @endphp
                @if ($dynamicStatus == 'Terjadwal')
                    <span
                        class="inline-flex items-center px-3 py-1 md:px-4 md:py-1.5 lg:px-4 lg:py-1.5 rounded-full text-xs md:text-sm lg:text-base font-medium bg-cyan-100 text-cyan-800">
                        <div class="w-2 h-2 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3 bg-cyan-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Berlangsung')
                    <span
                        class="inline-flex items-center px-3 py-1 md:px-4 md:py-1.5 lg:px-4 lg:py-1.5 rounded-full text-xs md:text-sm lg:text-base font-medium bg-emerald-100 text-emerald-800">
                        <div
                            class="w-2 h-2 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3 bg-emerald-400 rounded-full mr-2 animate-pulse">
                        </div>{{ $dynamicStatus }}
                    </span>
                @elseif($dynamicStatus == 'Selesai')
                    <span
                        class="inline-flex items-center px-3 py-1 md:px-4 md:py-1.5 lg:px-4 lg:py-1.5 rounded-full text-xs md:text-sm lg:text-base font-medium bg-gray-100 text-gray-800">
                        <div class="w-2 h-2 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3 bg-gray-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 md:px-4 md:py-1.5 lg:px-4 lg:py-1.5 rounded-full text-xs md:text-sm lg:text-base font-medium bg-red-100 text-red-800">
                        <div class="w-2 h-2 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3 bg-red-400 rounded-full mr-2"></div>
                        {{ $dynamicStatus }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    @push('styles')
        <style>
            /* Transisi yang lebih halus untuk navigasi */
            .tab-transition {
                transition: all 0.3s ease;
            }

            /* Animasi mengalir untuk tab aktif */
            .active-tab-border {
                position: relative;
                overflow: hidden;
            }

            .active-tab-border::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 2px;
                background: linear-gradient(90deg, #f59e0b, #eab308, #f59e0b);
                background-size: 200% 100%;
                animation: flowingBorder 2s linear infinite;
            }

            @keyframes flowingBorder {
                0% {
                    background-position: 0% 0;
                }

                100% {
                    background-position: 200% 0;
                }
            }

            .content-transition {
                opacity: 0;
                transform: translateY(5px);
                animation: fadeUp 0.4s forwards;
            }

            @keyframes fadeUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush

    @push('styles')
        <style>
            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            }

            .animate-fade-in {
                animation: fadeIn 0.6s ease-out;
            }

            .stats-icon {
                width: 38px;
                height: 38px;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }

            .stats-icon-yellow {
                background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            }

            .stats-icon-green {
                background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
            }

            .stats-icon-blue {
                background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            }

            .stats-icon-purple {
                background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
            }

            .stats-icon-orange {
                background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            }
        </style>
    @endpush

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div
            class="max-w-full md:max-w-7xl lg:max-w-[90%] xl:max-w-[95%] 2xl:max-w-full mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">
            {{-- Event Info Cards --}}
            <div
                class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-3 md:gap-4 lg:gap-6 animate-fade-in">
                <div class="bg-white rounded-2xl p-6 md:p-7 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-6">
                            <div class="stats-icon stats-icon-yellow w-14 h-14">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm md:text-base font-medium text-gray-600 uppercase tracking-wide">Total
                                Peserta</p>
                            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-1.5">
                                {{ $event->participants->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 md:p-7 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-6">
                            <div class="stats-icon stats-icon-green w-14 h-14">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm md:text-base font-medium text-gray-600 uppercase tracking-wide">Sudah
                                Hadir</p>
                            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-1.5">
                                {{ $event->attendances->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 md:p-7 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-6">
                            <div class="stats-icon stats-icon-purple w-14 h-14">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm md:text-base font-medium text-gray-600 uppercase tracking-wide">Dokumen
                            </p>
                            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-1.5">
                                {{ $event->documents->whereNotNull('file_path')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 md:p-7 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-6">
                            <div class="stats-icon stats-icon-blue w-14 h-14">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm md:text-base font-medium text-gray-600 uppercase tracking-wide">Waktu
                                Tersisa</p>
                            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-1.5">
                                {{ $event->countdown_status }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Tabs --}}
            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden">
                <div class="border-b border-gray-100 overflow-x-auto">
                    <nav class="flex md:space-x-8 lg:space-x-10 xl:space-x-12 px-6 md:px-8 lg:px-10 xl:px-12 min-w-max"
                        aria-label="Tabs">
                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'detail']) }}"
                            class="py-4 md:py-5 px-3 md:px-3 lg:px-4 border-b-2 font-medium text-sm md:text-base tab-transition whitespace-nowrap {{ $activeTab == 'detail' ? 'border-yellow-500 text-yellow-600 active-tab-border' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-2 md:space-x-3">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Detail</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'peserta']) }}"
                            class="py-4 md:py-5 px-3 md:px-3 lg:px-4 border-b-2 font-medium text-sm md:text-base tab-transition whitespace-nowrap {{ $activeTab == 'peserta' ? 'border-yellow-500 text-yellow-600 active-tab-border' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-2 md:space-x-3">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <span>Peserta ({{ $event->participants->count() }})</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'lampiran']) }}"
                            class="py-4 md:py-5 px-3 md:px-3 lg:px-4 border-b-2 font-medium text-sm md:text-base tab-transition whitespace-nowrap {{ $activeTab == 'lampiran' ? 'border-yellow-500 text-yellow-600 active-tab-border' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-2 md:space-x-3">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span>Lampiran</span>
                                <span
                                    class="text-xs md:text-sm bg-yellow-100 text-yellow-800 px-2 py-0.5 md:px-2.5 md:py-1 rounded-full">{{ $event->documents->whereNotNull('file_path')->count() }}</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.events.show', ['event' => $event, 'tab' => 'notulensi']) }}"
                            class="py-4 md:py-5 px-3 md:px-3 lg:px-4 border-b-2 font-medium text-sm md:text-base tab-transition whitespace-nowrap {{ $activeTab == 'notulensi' ? 'border-yellow-500 text-yellow-600 active-tab-border' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <div class="flex items-center space-x-2 md:space-x-3">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Notulensi</span>
                            </div>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Tab Content --}}
            <div class="content-transition">
                @if ($activeTab == 'detail')
                    @include('admin.events.tabs.detail', ['event' => $event])
                @elseif ($activeTab == 'peserta')
                    @include('admin.events.tabs.peserta', [
                        'event' => $event,
                        'potentialParticipants' => $potentialParticipants,
                        'errors' => $errors,
                    ])
                @elseif ($activeTab == 'lampiran')
                    @include('admin.events.tabs.lampiran', ['event' => $event])
                @elseif ($activeTab == 'notulensi')
                    @include('admin.events.tabs.notulensi', ['event' => $event])
                @endif
            </div>

            {{-- Navigation Action Bar --}}
            <div class="flex flex-wrap gap-3 justify-end">
                {{-- Tombol Kembali --}}
                <a href="{{ route('admin.events.index') }}"
                    class="inline-flex items-center px-3 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 border border-gray-300 rounded-xl text-sm md:font-medium text-white shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>

                {{-- Tombol Edit --}}
                <a href="{{ route('admin.events.edit', $event) }}"
                    class="inline-flex items-center px-3 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl text-sm md:font-medium text-white shadow-sm hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit
                </a>

                {{-- Tombol Sinkronisasi Google Calendar dengan Alpine.js --}}
                <div x-data="syncButton()" class="inline-block relative">
                    <button @click="syncCalendar()" :disabled="syncing"
                        class="inline-flex items-center px-3 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl text-sm md:font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                        @mouseenter="showTooltip = true" @mouseleave="showTooltip = false">

                        <!-- Loading spinner -->
                        <svg x-show="syncing" class="w-4 h-4 md:w-5 md:h-5 mr-2 animate-spin" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>

                        <!-- Default sync icon -->
                        <svg x-show="!syncing" class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>

                        <span x-text="syncing ? 'Memproses...' : 'Sinkronisasi Google Calendar'"
                            class="whitespace-nowrap"></span>
                    </button>

                    <!-- Tooltip -->
                    <div x-show="showTooltip"
                        class="absolute bottom-full mb-2 px-4 py-2 bg-gray-800 text-white text-xs rounded-lg shadow-lg z-50 max-w-xs whitespace-normal">
                        <div>Sinkronisasi penuh: Segarkan event ke Google Calendar semua peserta dan bersihkan event
                            orphan dari semua akun</div>
                    </div>
                </div> {{-- Tombol Hapus --}}
                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline"
                    onsubmit="return confirm('Yakin ingin menghapus event ini? Data terkait akan hilang permanen.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-3 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-red-500 to-red-600 rounded-xl text-sm md:font-medium text-white shadow-sm hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function submitBulkInvite(method) {
                document.getElementById('invite_method_input').value = method;
                if (method === 'all') {
                    const divisionSelect = document.getElementById('division');
                    if (divisionSelect) divisionSelect.value = '';
                }
                document.getElementById('bulk-invite-form').submit();
            }

            // Notification helper function
            function showNotification(type, message) {
                console.log('[Notification] Type:', type, 'Message:', message);

                // Create notification container if not exists
                let notificationsContainer = document.getElementById('notifications-container');
                if (!notificationsContainer) {
                    console.log('[Notification] Creating container');
                    notificationsContainer = document.createElement('div');
                    notificationsContainer.id = 'notifications-container';
                    // Use inline styles untuk ensure visibility
                    notificationsContainer.style.cssText =
                        'position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; align-items: flex-end;';
                    document.body.appendChild(notificationsContainer);
                    console.log('[Notification] Container added to body');
                }

                // Create notification div
                const div = document.createElement('div');
                let bgColor = '#ef4444'; // red
                if (type === 'error') bgColor = '#ef4444';
                else if (type === 'warning') bgColor = '#f97316';
                else if (type === 'success') bgColor = '#22c55e';
                else if (type === 'info') bgColor = '#3b82f6';

                // Use inline styles untuk ensure visibility
                div.style.cssText = `
                    background-color: ${bgColor};
                    color: white;
                    padding: 20px 24px;
                    border-radius: 8px;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 16px;
                    min-width: 300px;
                    animation: slideIn 0.3s ease-out;
                `;

                div.innerHTML = `
                    <span style="flex: 1;">${message}</span>
                    <button style="background: none; border: none; color: white; font-size: 18px; cursor: pointer; padding: 0; line-height: 1;" onclick="this.parentElement.remove()">✕</button>
                `;

                notificationsContainer.appendChild(div);
                console.log('[Notification] Appended to container. Container HTML:', notificationsContainer.innerHTML);
                console.log('[Notification] Div computed style:', window.getComputedStyle(div));

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (div.parentNode) {
                        div.parentNode.removeChild(div);
                        console.log('[Notification] Auto-removed');
                    }
                }, 5000);
            }

            // Add animation keyframes
            if (!document.getElementById('notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                    @keyframes slideIn {
                        from {
                            transform: translateX(400px);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                `;
                document.head.appendChild(style);
                console.log('[Notification] Animation styles added');
            }

            function syncButton() {
                return {
                    syncing: false,
                    showTooltip: false,

                    async syncCalendar() {
                        console.log('[SyncButton] Click handler triggered');

                        if (this.syncing) {
                            console.log('[SyncButton] Already syncing, ignoring click');
                            return;
                        }

                        this.syncing = true;
                        console.log('[SyncButton] Starting sync...');

                        try {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            const url = '{{ route('admin.events.sync-calendar', $event) }}';

                            console.log('[SyncButton] CSRF Token:', csrfToken ? 'OK' : 'MISSING');
                            console.log('[SyncButton] URL:', url);
                            console.log('[SyncButton] Sending fetch request...');

                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({})
                            });

                            console.log('[SyncButton] Response Status:', response.status);

                            if (!response.ok) {
                                throw new Error('HTTP ' + response.status);
                            }

                            const data = await response.json();
                            console.log('[SyncButton] Response Data:', data);

                            if (data.success) {
                                let msg = 'Refresh penuh dimulai: ';
                                if (data.stats) {
                                    msg += data.stats.synced_count + ' peserta akan disinkronkan';
                                    if (data.stats.cleaned_count) msg += ', ' + data.stats.cleaned_count +
                                        ' user akan dibersihkan';
                                }
                                console.log('[SyncButton] Calling showNotification with success');
                                showNotification('success', msg);
                            } else {
                                console.log('[SyncButton] Response not successful');
                                showNotification('warning', data.message || 'Sinkronisasi gagal');
                            }
                        } catch (error) {
                            console.error('[SyncButton] Error caught:', error);
                            showNotification('error', 'Error: ' + error.message);
                        } finally {
                            this.syncing = false;
                            console.log('[SyncButton] Sync ended');
                        }
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
