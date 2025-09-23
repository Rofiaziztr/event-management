<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Peserta') }}
        </h2>
    </x-slot>

    @push('styles')
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes slideUp {
                from {
                    transform: translateY(20px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .gradient-bg {
                background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
            }

            .glass-effect {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .animate-pulse-slow {
                animation: pulse 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            .animate-fade-in {
                animation: fadeIn 0.5s ease-in-out;
            }

            .animate-slide-up {
                animation: slideUp 0.3s ease-out;
            }
        </style>
    @endpush

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-7xl mt-10 mx-auto px-6 space-y-8 pb-8">

            {{-- CTA for Ongoing Events --}}
            @if ($ongoingEvents->isNotEmpty())
                <div class="animate-slide-up">
                    @foreach ($ongoingEvents as $event)
                        @if (!in_array($event->id, $attendedEventIds))
                            <div
                                class="bg-gradient-to-r from-red-500 to-orange-500 rounded-2xl p-6 text-white shadow-2xl animate-pulse-slow mb-4">
                                <div class="flex items-center justify-between flex-wrap gap-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold">Event Sedang Berlangsung!</h3>
                                            <p class="text-red-100">"{{ $event->title }}" sedang berjalan. Segera
                                                lakukan presensi.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('scan.index') }}"
                                        class="bg-white text-red-500 px-6 py-3 rounded-xl font-semibold hover:bg-red-50 transition-colors duration-200 shadow-lg">
                                        Presensi Sekarang
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-slide-up">
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Undangan</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalInvitations }}</p>
                            <p class="text-xs text-blue-600 mt-1">Event selesai</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Kehadiran</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $attendedCount }}</p>
                            <p class="text-xs text-green-600 mt-1">{{ $attendanceRate }}% tingkat hadir</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="p-4 bg-gradient-to-br from-red-500 to-red-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Event Terlewat</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $missedEventsCount }}</p>
                            <p class="text-xs text-red-600 mt-1">
                                {{ $totalInvitations > 0 ? round(($missedEventsCount / $totalInvitations) * 100) : 0 }}%
                                dari total</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-200 card-hover">
                    <div class="flex items-center space-x-4">
                        <div class="p-4 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Tingkat Kehadiran</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $attendanceRate }}%</p>
                            <p class="text-xs text-yellow-600 mt-1">
                                @if ($attendanceRate >= 90)
                                    Excellent!
                                @elseif($attendanceRate >= 80)
                                    Good!
                                @elseif($attendanceRate >= 70)
                                    Fair
                                @else
                                    Needs Improvement
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming Events List --}}
            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-slide-up">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900">Event Akan Datang</h3>
                    <p class="text-gray-500 mt-1">Jadwal event yang akan segera dimulai. Pastikan untuk hadir dan
                        tingkatkan tingkat kehadiran Anda!</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($upcomingEvents as $event)
                        <a href="{{ route('participant.events.show', $event) }}"
                            class="block p-6 hover:bg-yellow-50 transition-colors duration-200">
                            <div
                                class="flex flex-col lg:flex-row justify-between lg:items-center space-y-3 lg:space-y-0">
                                <div class="flex items-start space-x-4">
                                    <div class="p-3 bg-yellow-100 rounded-xl">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $event->title }}</h4>
                                        <p class="text-gray-500 mt-1">{{ $event->location }}</p>
                                        <div class="flex items-center space-x-4 mt-2">
                                            @if ($event->category)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $event->category->name }}
                                                </span>
                                            @endif
                                            <span class="text-sm text-gray-500">{{ $event->participants->count() }}
                                                peserta</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-left lg:text-right">
                                    <p class="text-lg font-bold text-yellow-600">
                                        {{ $event->start_time->format('d M Y, H:i') }} WIB
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $event->status }}</p>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-2">
                                        {{ $event->status }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Event Mendatang</h3>
                            <p class="text-gray-500">Tidak ada event yang dijadwalkan dalam waktu dekat.</p>
                        </div>
                    @endforelse
                </div>
                @if ($upcomingEvents->isNotEmpty())
                    <div class="p-6 border-t border-gray-100 text-center">
                        <a href="{{ route('participant.events.index') }}"
                            class="text-yellow-600 hover:text-yellow-700 font-semibold transition-colors duration-200">
                            Lihat Semua Event →
                        </a>
                    </div>
                @endif
            </div>

            {{-- Riwayat Event Anda --}}
            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-slide-up">
                <div class="p-6 border-b border-gray-100">
                    <div
                        class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Riwayat Event Anda
                                ({{ $totalHistoryEvents }})</h3>
                            <p class="text-gray-500 mt-1">Event yang pernah Anda ikuti atau diundang, dengan status
                                kehadiran. Gunakan pencarian untuk menemukan event spesifik berdasarkan judul, lokasi,
                                atau kategori.</p>
                        </div>
                        <form method="GET" action="{{ route('participant.dashboard') }}" class="flex space-x-3">
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Cari title, lokasi, kategori..."
                                class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 w-64" />
                            <select name="history_period"
                                class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                <option value="all" {{ $historyPeriod == 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="last_year" {{ $historyPeriod == 'last_year' ? 'selected' : '' }}>1
                                    Tahun Terakhir</option>
                                <option value="last_6_months"
                                    {{ $historyPeriod == 'last_6_months' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                                <option value="this_year" {{ $historyPeriod == 'this_year' ? 'selected' : '' }}>Tahun
                                    Ini</option>
                            </select>
                            <button type="submit"
                                class="bg-yellow-500 text-white px-4 py-2 rounded-xl hover:bg-yellow-600 transition-colors">Cari</button>
                            <a href="{{ route('participant.dashboard') }}"
                                class="bg-gray-500 text-white px-4 py-2 rounded-xl hover:bg-gray-600 transition-colors">Reset</a>
                        </form>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @forelse ($historyEvents as $index => $event)
                        <a href="{{ route('participant.events.show', $event) }}"
                            class="block p-6 hover:bg-yellow-50 transition-colors duration-200">
                            <div
                                class="flex flex-col lg:flex-row justify-between lg:items-center space-y-3 lg:space-y-0">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full text-gray-700 font-semibold">
                                        {{ $historyEvents->firstItem() + $index }}
                                    </div>
                                    <div
                                        class="p-3 {{ $event->start_time->isFuture() ? 'bg-gray-100' : ($event->attendances->isNotEmpty() ? 'bg-green-100' : 'bg-red-100') }} rounded-xl">
                                        <svg class="w-6 h-6 {{ $event->start_time->isFuture() ? 'text-gray-600' : ($event->attendances->isNotEmpty() ? 'text-green-600' : 'text-red-600') }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $event->start_time->isFuture() ? 'M8 7V3m8 4V3m-9 8h10' : ($event->attendances->isNotEmpty() ? 'M9 12l2 2 4-4' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2') }}m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-900 text-lg truncate">{{ $event->title }}</h4>
                                        <p class="text-gray-500 mt-1 truncate">{{ $event->location }}</p>
                                        <div class="flex items-center space-x-4 mt-2">
                                            @if ($event->category)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $event->category->name }}
                                                </span>
                                            @endif
                                            <span
                                                class="text-sm {{ $event->start_time->isFuture() ? 'text-gray-600' : ($event->attendances->isNotEmpty() ? 'text-green-600' : 'text-red-600') }} font-medium">
                                                {{ $event->start_time->isFuture() ? 'Belum Berlangsung' : ($event->attendances->isNotEmpty() ? 'Hadir' : 'Tidak Hadir') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-left lg:text-right">
                                    <p class="text-lg font-bold text-yellow-600">
                                        {{ $event->start_time->format('d M Y, H:i') }} WIB
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $event->status }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Event</h3>
                            <p class="text-gray-500">Riwayat akan muncul setelah Anda diundang ke event.</p>
                        </div>
                    @endforelse
                </div>
                @if ($historyEvents->hasPages())
                    <div class="p-6 border-t border-gray-100">
                        <x-yellow-pagination :paginator="$historyEvents" />
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Animation observer
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, observerOptions);

                const animatedElements = document.querySelectorAll('.animate-slide-up');
                animatedElements.forEach(el => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(20px)';
                    el.style.transition = 'all 0.6s ease-out';
                    observer.observe(el);
                });
            });
        </script>
    @endpush
</x-app-layout>
