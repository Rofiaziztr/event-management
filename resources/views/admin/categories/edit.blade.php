<x-app-layout>
    <x-slot name="header">
        <div
            class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-3 md:space-y-4 lg:space-y-0">
            <div class="w-full lg:w-auto">
                <h2 class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-tight">
                    Edit Kategori</h2>
                <p class="text-gray-600 mt-1.5 text-sm md:text-base">Perbarui informasi kategori "{{ $category->name }}"
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.categories.index') }}"
                    class="inline-flex items-center px-3 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 border border-gray-300 rounded-xl text-sm md:font-medium text-white shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-8">
            <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Form Edit Kategori</h3>
                    <p class="mt-1 text-sm text-gray-600">Perbarui informasi kategori.</p>
                </div>

                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $category->name) }}"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm"
                                placeholder="Masukkan nama kategori" required>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Slug akan otomatis diperbarui: <code
                                class="bg-gray-100 px-2 py-1 rounded text-xs">{{ \Illuminate\Support\Str::slug(old('name', $category->name)) }}</code>
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Informasi Kategori</h4>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Slug Saat Ini</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->slug }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jumlah Event</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->events()->count() }} event</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->created_at->format('d M Y H:i') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Terakhir Diubah</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->updated_at->format('d M Y H:i') }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.categories.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Perbarui Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
