<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Manajemen Pengguna') }}
                </h2>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.export') }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-500 border border-transparent rounded-xl font-semibold text-white hover:bg-green-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export Data
                </a>
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-yellow-500 border border-transparent rounded-xl font-semibold text-white hover:bg-yellow-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pengguna
                </a>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes sunlightMove {
            0% { background-position: 100% 0; }
            100% { background-position: -100% 0; }
        }
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
    </style>
    @endpush

    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-8">

            @if (session('success'))
                <div class="animate-fade-in">
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="stats-icon stats-icon-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Pengguna</p>
                            <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="stats-icon stats-icon-purple">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Divisi Terdaftar</p>
                            <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::distinct('division')->whereNotNull('division')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-xl border border-yellow-100 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="stats-icon stats-icon-yellow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Bergabung Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-yellow-500 rounded-t-xl p-4 animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Filter & Pencarian Pengguna</h3>
                </div>
            </div>
            <div class="bg-white rounded-b-xl shadow-xl border border-yellow-200 border-t-0 overflow-hidden animate-fade-in">

                <form method="GET" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pengguna</label>
                            <input type="text" name="search" placeholder="Nama atau email"
                                value="{{ request('search') }}"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan Berdasarkan</label>
                            <select name="sort"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                <option value="full_name" {{ request('sort') === 'full_name' ? 'selected' : '' }}>Nama</option>
                                <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>Email</option>
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Arah Urutan</label>
                            <select name="direction"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>A-Z (Naik)</option>
                                <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Z-A (Turun)</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <x-bladewind::button tag="a" href="{{ route('admin.events.index') }}" color="gray"
                            size="small" class="flex-1" icon="x-circle">
                            Clear Filter
                        </x-bladewind::button>
                        <x-bladewind::button can_submit="true" color="indigo" size="small" class="flex-1" icon="search">
                            Terapkan Filter
                        </x-bladewind::button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Daftar Pengguna -->
            <div class="animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($users as $user)
                        <a href="{{ route('admin.users.show', $user) }}" class="block">
                            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden flex flex-col relative group h-full transition-all duration-300 card-hover">
                                <div class="p-6 flex-grow z-10">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <span class="text-yellow-600 font-bold text-lg">
                                                {{ substr($user->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-gray-900 truncate group-hover:text-yellow-600"
                                                title="{{ $user->full_name }}">
                                                {{ \Illuminate\Support\Str::limit($user->full_name, 20) }}
                                            </h3>
                                            <p class="text-sm text-gray-500 truncate"
                                               title="{{ $user->email }}">
                                                {{ \Illuminate\Support\Str::limit($user->email, 25) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 space-y-2 text-sm text-gray-600">
                                        @if ($user->position)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 114 0v12.5" />
                                                </svg>
                                                <span>{{ $user->position }}</span>
                                            </div>
                                        @endif

                                        @if ($user->division)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h4M9 7h6m-6 4h6m-6 4h6" />
                                                </svg>
                                                <span>{{ $user->division }}</span>
                                            </div>
                                        @endif

                                        @if ($user->phone_number)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span>{{ $user->phone_number }}</span>
                                            </div>
                                        @endif

                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Bergabung {{ $user->created_at->format('M Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="grid grid-cols-2 gap-4 text-center">
                                            <div>
                                                <div class="text-lg font-bold text-blue-600">
                                                    {{ $user->participatedEvents->count() ?? 0 }}</div>
                                                <div class="text-xs text-gray-500">Event Diikuti</div>
                                            </div>
                                            <div>
                                                <div class="text-lg font-bold text-green-600">
                                                    {{ $user->attendances->count() ?? 0 }}</div>
                                                <div class="text-xs text-gray-500">Kehadiran</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-l from-yellow-300/20 via-yellow-100/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"
                                    style="animation: sunlightMove 4s infinite linear;"></div>
                            </div>
                        </a>
                    @empty
                        <div class="md:col-span-3 text-center py-12">
                            <x-bladewind::empty-state message="Tidak ada pengguna yang ditemukan." />
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    <x-yellow-pagination :paginator="$users" />
                </div>
            </div>
    </div>
</x-app-layout>