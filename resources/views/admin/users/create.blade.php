<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tambah Pengguna Baru</h2>
                <p class="text-sm text-gray-600 mt-1">Buat akun pengguna baru untuk sistem</p>
            </div>
            <x-bladewind::button tag="a" href="{{ route('admin.users.index') }}" color="gray" 
                size="small" icon="arrow-left">
                Kembali
            </x-bladewind::button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Informasi Pengguna Baru</h3>
                    </div>
                    <p class="text-indigo-100 text-sm mt-1">Lengkapi formulir di bawah untuk membuat akun pengguna baru</p>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Left Column --}}
                        <div class="space-y-6">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="full_name" id="full_name" required 
                                    value="{{ old('full_name') }}"
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
                                    value="{{ old('email') }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror"
                                    placeholder="nama@example.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password" required minlength="8"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-300 @enderror"
                                    placeholder="Minimal 8 karakter">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Ulangi password">
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="space-y-6">
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                    Posisi/Jabatan
                                </label>
                                <input type="text" name="position" id="position" 
                                    value="{{ old('position') }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Contoh: Manager, Staff, Supervisor">
                            </div>

                            <div>
                                <label for="division" class="block text-sm font-medium text-gray-700 mb-2">
                                    Divisi/Departemen
                                </label>
                                <input type="text" name="division" id="division" 
                                    value="{{ old('division') }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Contoh: IT, HR, Finance">
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <input type="text" name="phone_number" id="phone_number" 
                                    value="{{ old('phone_number') }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Contoh: 08123456789">
                            </div>

                            {{-- Role (Hidden) --}}
                            <input type="hidden" name="role" value="participant">

                            {{-- Info Box --}}
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Password harus minimal 8 karakter</li>
                                                <li>Email akan digunakan untuk login</li>
                                                <li>Pengguna akan memiliki role "Participant"</li>
                                                <li>Posisi dan divisi bersifat opsional</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-end space-x-3">
                            <x-bladewind::button tag="a" href="{{ route('admin.users.index') }}" 
                                color="gray" size="small">
                                Batal
                            </x-bladewind::button>
                            <x-bladewind::button can_submit="true" color="indigo" size="small" icon="save">
                                Simpan Pengguna
                            </x-bladewind::button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>