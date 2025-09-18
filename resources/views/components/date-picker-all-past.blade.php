<div class="relative">
    <input 
        type="text" 
        name="{{ $name }}" 
        id="{{ $id }}" 
        value="{{ $value }}"
        class="flatpickr-date-only block w-full py-3 px-4 border border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500"
        placeholder="{{ $placeholder }}"
        data-enable-all-dates="true"
    >
    <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none z-10">
        <svg class="flex-shrink-0 size-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
    </div>
</div>