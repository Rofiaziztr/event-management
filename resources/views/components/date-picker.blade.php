@props(['name', 'id', 'value' => ''])

<input type="text" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
    class="flatpickr block w-full py-2 sm:py-3 px-3 sm:px-4 border border-gray-300 rounded-lg text-sm focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 disabled:opacity-50 disabled:pointer-events-none bg-white"
    placeholder="Pilih Tanggal dan Waktu">
