<form action="{{ route('admin.events.participants.store.external', $event) }}" method="POST" class="space-y-4">
    @csrf
    <label class="block text-lg font-semibold text-gray-800 mb-2">Tambah & Undang Peserta Eksternal</label>
    <p class="text-sm text-gray-500 mb-4">Jika email sudah ada, data akan diperbarui. Jika belum, akun baru akan dibuatkan.</p>
    
    <div>
        <label for="full_name_ext" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
        <input type="text" name="full_name" id="full_name_ext" required value="{{ old('full_name') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
    </div>
    <div>
        <label for="email_ext" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email *</label>
        <input type="email" name="email" id="email_ext" required value="{{ old('email') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
    </div>
    <div>
        <label for="institution_ext" class="block text-sm font-medium text-gray-700 mb-1">Instansi *</label>
        <input type="text" name="institution" id="institution_ext" required value="{{ old('institution') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
    </div>
    <div>
        <label for="position_ext" class="block text-sm font-medium text-gray-700 mb-1">Posisi/Jabatan</label>
        <input type="text" name="position" id="position_ext" value="{{ old('position') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
    </div>
    <div>
        <label for="phone_number_ext" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon (Opsional)</label>
        <input type="text" name="phone_number" id="phone_number_ext" value="{{ old('phone_number') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
    </div>
    <div class="flex justify-end pt-4">
        <x-bladewind::button can_submit="true" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white">
            Tambah & Undang
        </x-bladewind::button>
    </div>
</form>