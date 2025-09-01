@props(['name', 'id', 'value' => ''])

<div class="relative">
    <input type="text" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
        class="flatpickr py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
        placeholder="Pilih Tanggal dan Waktu">
    <div class="absolute inset-y-0 end-0 flex items-center pe-3 datepicker-icon" style="cursor: pointer; z-index: 1;">
        <svg class="flex-shrink-0 size-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M8 2v4"></path>
            <path d="M16 2v4"></path>
            <rect width="18" height="18" x="3" y="4" rx="2"></rect>
            <path d="M3 10h18"></path>
            <path d="M10 14h.01"></path>
            <path d="M14 14h.01"></path>
            <path d="M18 14h.01"></path>
            <path d="M10 18h.01"></path>
            <path d="M14 18h.01"></path>
            <path d="M18 18h.01"></path>
        </svg>
    </div>
</div>
