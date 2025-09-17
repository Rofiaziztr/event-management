@props(['status'])

@if ($status)
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p {{ $attributes->merge(['class' => 'text-green-700 text-sm font-medium']) }}>
                {{ $status }}
            </p>
        </div>
    </div>
@endif