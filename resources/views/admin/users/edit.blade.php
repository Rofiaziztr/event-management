<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold text-lg">
                        {{ substr($user->full_name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Pengguna</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $user->full_name }} • {{ $user->email }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <x-bladewind::button tag="a" href="{{ route('admin.users.show', $user) }}" color="blue" 
                    size="small" icon="eye">
                    Lihat Detail
                </x-bladewind::button>
                <x-bladewind::button tag="a" href="{{ route('admin.users.index') }}" color="gray" 
                    size="small" icon="arrow-left">
                    Kembali
                </x-bladewind::button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Edit Informasi Pengguna</h3>
                    </div>
                    <p class="text-indigo-100 text-sm mt-1">Perbarui informasi pengguna dengan formulir di bawah</p>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Left Column --}}
                        <div class="space-y-6">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="full_name" id="full_name" required 
                                    value="{{ old('full_name', $user->full_name) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('full_name') border-red-300 @enderror"
                                    placeholder="Masukkan nama lengkap">
                                @error('full_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" required 
                                    value="{{ old('email', $user->email) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror"
                                    placeholder="nama@example.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <input type="password" name="password" id="password" minlength="8"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-300 @enderror"
                                    placeholder="Kosongkan jika tidak ingin mengubah password">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password Baru
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Ulangi password baru">
                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="space-y-6">
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                    Posisi/Jabatan
                                </label>
                                <input type="text" name="position" id="position" 
                                    value="{{ old('position', $user->position) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Contoh: Manager, Staff, Supervisor">
                            </div>

                            <div>
                                <label for="division" class="block text-sm font-medium text-gray-700 mb-2">
                                    Divisi/Departemen
                                </label>
                                <input type="text" name="division" id="division" 
                                    value="{{ old('division', $user->division) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Contoh: IT, HR, Finance">
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <input type="text" name="phone_number" id="phone_number" 
                                    value="{{ old('phone_number', $user->phone_number) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Contoh: 08123456789">
                            </div>

                            <input type="hidden" name="role" value="participant">

                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Statistik Pengguna</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-blue-600">{{ $user->participatedEvents->count() ?? 0 }}</div>
                                        <div class="text-xs text-gray-500">Event Diikuti</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-green-600">{{ $user->attendances->count() ?? 0 }}</div>
                                        <div class="text-xs text-gray-500">Total Kehadiran</div>
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    <div class="text-sm text-gray-600">
                                        Bergabung: {{ $user->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        Terakhir update: {{ $user->updated_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.users.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Pengguna
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>