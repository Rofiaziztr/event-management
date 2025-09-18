<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Pengguna') }}
            </h2>
            <div class="flex space-x-3">
                <x-bladewind::button tag="a" href="{{ route('admin.users.export') }}" size="small" color="green"
                    icon="download">
                    Export Data
                </x-bladewind::button>
                <x-bladewind::button tag="a" href="{{ route('admin.users.create') }}" size="small"
                    color="indigo" icon="plus">
                    Tambah Pengguna
                </x-bladewind::button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto">

            <!-- Alert -->
            @if (session('success'))
                <div class="mb-6 px-4 sm:px-0">
                    <x-bladewind::alert type="success">
                        {{ session('success') }}
                    </x-bladewind::alert>
                </div>
            @endif

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-4 sm:px-0">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-blue-100 text-sm">Total Pengguna</p>
                            <p class="text-2xl font-bold">{{ App\Models\User::count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h4M9 7h6m-6 4h6m-6 4h6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-purple-100 text-sm">Divisi Terdaftar</p>
                            <p class="text-2xl font-bold">
                                {{ App\Models\User::distinct('division')->whereNotNull('division')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-orange-100 text-sm">Bergabung Bulan Ini</p>
                            <p class="text-2xl font-bold">
                                {{ App\Models\User::where('created_at', '>=', now()->startOfMonth())->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8 mx-4 sm:mx-0">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Filter & Pencarian Pengguna</h3>
                    </div>
                </div>

                <form method="GET" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pengguna</label>
                            <input type="text" name="search" placeholder="Nama atau email"
                                value="{{ request('search') }}"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan Berdasarkan</label>
                            <select name="sort"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="full_name" {{ request('sort') === 'full_name' ? 'selected' : '' }}>Nama</option>
                                <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>Email</option>
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Arah Urutan</label>
                            <select name="direction"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>A-Z (Naik)</option>
                                <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Z-A (Turun)</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <x-bladewind::button can_submit="true" color="indigo" size="small" class="flex-1"
                                icon="search">
                                Terapkan
                            </x-bladewind::button>

                            <x-bladewind::button tag="a" href="{{ route('admin.users.index') }}"
                                color="gray" size="small" class="flex-1" icon="x-circle">
                                Reset
                            </x-bladewind::button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Daftar Pengguna -->
            <div class="px-4 sm:px-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($users as $user)
                        <a href="{{ route('admin.users.show', $user) }}" class="block">
                            <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden flex flex-col relative group h-full transition-all duration-300 hover:shadow-xl">
                                <div class="p-6 flex-grow z-10">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-600 font-bold text-lg">
                                                {{ substr($user->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-gray-900 truncate group-hover:text-indigo-600"
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
                                <div class="absolute inset-0 bg-gradient-to-l from-yellow-300/20 via-yellow-100/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0" style="animation: sunlightMove 4s infinite linear;"></div>
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
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    @keyframes sunlightMove {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
    }
</style>