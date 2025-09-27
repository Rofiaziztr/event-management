<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Buat Event Baru
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            @if ($errors->any())
                <div class="animate-fade-in">
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                            </svg>
                            <p class="text-red-800 font-medium">Terdapat kesalahan dalam input. Periksa kembali form di bawah.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 animate-fade-in">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.events.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="title" :value="__('Judul Event *')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Kategori *')" />
                            <select name="category_id" id="category_id" class="block w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada kategori tersedia. Tambahkan kategori terlebih dahulu.</option>
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description"
                                class="block w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="start_time" :value="__('Waktu Mulai *')" />
                            <x-date-picker name="start_time" id="start_time" :value="old('start_time')" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="end_time" :value="__('Waktu Selesai *')" />
                            <x-date-picker name="end_time" id="end_time" :value="old('end_time')" required />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="location" :value="__('Lokasi *')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location"
                                :value="old('location')" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-bladewind::button tag="a" href="{{ route('admin.events.index') }}"
                                color="secondary" outline="true" class="mr-3">
                                Batal
                            </x-bladewind::button>
                            <x-bladewind::button can_submit="true" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white">
                                Simpan Event
                            </x-bladewind::button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>