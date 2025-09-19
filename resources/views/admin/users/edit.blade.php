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
                    <h3 class="text-lg font-semibold text-white">Edit Informasi Pengguna</h3>
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
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('full_name') border-red-300 @enderror">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" required
                                    value="{{ old('email', $user->email) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror">
                            </div>

                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                                    NIP
                                </label>
                                <input type="text" name="nip" id="nip"
                                    value="{{ old('nip', $user->nip) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="institution" class="block text-sm font-medium text-gray-700 mb-2">
                                    Institusi
                                </label>
                                <input type="text" name="institution" id="institution"
                                    value="{{ old('institution', $user->institution) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
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
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="division" class="block text-sm font-medium text-gray-700 mb-2">
                                    Divisi/Departemen
                                </label>
                                <input type="text" name="division" id="division"
                                    value="{{ old('division', $user->division) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <input type="password" name="password" id="password" minlength="8"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-300 @enderror"
                                    placeholder="Kosongkan jika tidak ingin mengubah password">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password Baru
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-end space-x-3">
                             <input type="hidden" name="role" value="participant">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700">
                                Update Pengguna
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>