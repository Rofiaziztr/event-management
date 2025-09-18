<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard Admin') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Selamat datang, {{ auth()->user()->full_name }}</p>
            </div>
            <x-bladewind::button tag="a" href="{{ route('admin.events.create') }}" size="small" color="indigo">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Event Baru
            </x-bladewind::button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="mb-6 px-4 sm:px-0">
                    <x-bladewind::alert type="success" class="shadow-sm">
                        {{ session('success') }}
                    </x-bladewind::alert>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Filter Analytics</h3>
                            <p class="text-indigo-100 text-sm mt-1">Sesuaikan tampilan data berdasarkan periode waktu</p>
                        </div>
                    </div>
                </div>

                <form method="GET" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                   class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                   class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Chart</label>
                            <select name="chart_type"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="bar" {{ request('chart_type') === 'bar' ? 'selected' : '' }}>Bar Chart</option>
                                <option value="line" {{ request('chart_type') === 'line' ? 'selected' : '' }}>Line Chart</option>
                                <option value="pie" {{ request('chart_type') === 'pie' ? 'selected' : '' }}>Pie Chart</option>
                                <option value="doughnut" {{ request('chart_type') === 'doughnut' ? 'selected' : '' }}>Doughnut Chart</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Grup Data</label>
                            <select name="group_by"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="events" {{ request('group_by') === 'events' ? 'selected' : '' }}>Per Event</option>
                                <option value="daily" {{ request('group_by') === 'daily' ? 'selected' : '' }}>Harian</option>
                                <option value="weekly" {{ request('group_by') === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                <option value="monthly" {{ request('group_by') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            </select>
                        </div>

                            <div>
        <label class="blocktext-smfont-mediumtext-gray-700 mb-2">Top N Event</label>
        <input type="number" name="top_n" min="1" max="10" value="{{request('top_n', 5) }}"
               class="block w-fullborder-gray-300rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"/>
    </div>
                        
                        <div class="flex items-end">
                            <x-bladewind::button can_submit="true" color="indigo" size="small" class="w-full" icon="refresh">
                                Terapkan
                            </x-bladewind::button>
                        </div>
                    </div>

                    <!-- Quick Filters -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.dashboard', ['start_date' => now()->startOfDay()->format('Y-m-d'), 'end_date' => now()->endOfDay()->format('Y-m-d')]) }}"
                               class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors">
                                Hari Ini
                            </a>
                            <a href="{{ route('admin.dashboard', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}"
                               class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors">
                                Minggu Ini
                            </a>
                            <a href="{{ route('admin.dashboard', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}"
                               class="px-3 py-1 text-xs bg-purple-100 text-purple-800 rounded-full hover:bg-purple-200 transition-colors">
                                Bulan Ini
                            </a>
                            <a href="{{ route('admin.dashboard') }}"
                               class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-blue-100 text-sm">Total Event</p>
                            <p class="text-2xl font-bold">{{ $totalEvents }}</p>
                            @if(request('start_date') && request('end_date'))
                                <p class="text-blue-200 text-xs">{{ request('start_date') }} - {{ request('end_date') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-green-100 text-sm">Total Peserta</p>
                            <p class="text-2xl font-bold">{{ $totalParticipants }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-purple-100 text-sm">Total Kehadiran</p>
                            <p class="text-2xl font-bold">{{ $totalAttendances }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-orange-100 text-sm">Rata-rata Kehadiran</p>
                            <p class="text-2xl font-bold">{{ $averageAttendance }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-white">
                                @php
                                    $groupBy = request('group_by', 'events');
                                    $chartType = request('chart_type', 'bar');

                                    $title = match($groupBy) {
                                        'daily' => 'Kehadiran Harian',
                                        'weekly' => 'Kehadiran Mingguan',
                                        'monthly' => 'Kehadiran Bulanan',
                                        default => 'Top Event Berdasarkan Kehadiran'
                                    };
                                @endphp
                                {{ $title }}
                            </h3>
                        </div>
                        <span class="text-emerald-100 text-sm">{{ ucfirst($chartType) }} Chart</span>
                    </div>
                </div>

                <div class="p-6">
                    <div style="height: 400px;">
                        <canvas id="attendanceChart"></canvas>
                    </div>

                    @if($eventAttendance->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p>Tidak ada data untuk periode ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Additional Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-gradient-to-r from-slate-500 to-slate-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Aktivitas Terbaru</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @forelse($recentEvents->take(5) as $event)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-sm">{{ $event->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $event->start_time->format('d M Y, H:i') }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">
                                    {{ $event->participants_count }} peserta
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Belum ada aktivitas.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-gradient-to-r from-violet-500 to-violet-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Statistik Cepat</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Event Bulan Ini:</span>
                            <span class="font-semibold">{{ $monthlyEvents }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Kehadiran Bulan Ini:</span>
                            <span class="font-semibold">{{ $monthlyAttendances }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Event Aktif:</span>
                            <span class="font-semibold">{{ $activeEvents }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Dokumen:</span>
                            <span class="font-semibold">{{ $totalDocuments }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('attendanceChart');
                if (!ctx) return;

                const chartType = '{{ request("chart_type", "bar") }}';
                const data = {
                    labels: @json($eventAttendance->pluck('title')),
                    datasets: [{
                        label: 'Jumlah Kehadiran',
                        data: @json($eventAttendance->pluck('attendance_count')),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 2
                    }]
                };

                new Chart(ctx, {
                    type: chartType,
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: chartType === 'pie' || chartType === 'doughnut' },
                            tooltip: { backgroundColor: '#1f2937' }
                        },
                        scales: chartType !== 'pie' && chartType !== 'doughnut' ? {
                            y: { beginAtZero: true, ticks: { color: '#4b5563' } },
                            x: { ticks: { color: '#4b5563' } }
                        } : {}
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>